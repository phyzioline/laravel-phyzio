<?php

namespace App\Services\Clinic;

use App\Models\ClinicAppointment;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingAutomationService
{
    protected $paymentCalculator;

    public function __construct(PaymentCalculatorService $paymentCalculator)
    {
        $this->paymentCalculator = $paymentCalculator;
    }

    /**
     * Automatically generate invoice when appointment is completed
     * 
     * @param ClinicAppointment $appointment
     * @return array|null Returns invoice data or null if creation failed
     */
    public function generateInvoiceOnCompletion(ClinicAppointment $appointment): ?array
    {
        try {
            // Calculate appointment price
            $pricing = $this->paymentCalculator->calculateAppointmentPrice($appointment);
            $totalAmount = $pricing['total_price'] ?? 0;

            if ($totalAmount <= 0) {
                Log::info('Skipping invoice generation - zero amount', [
                    'appointment_id' => $appointment->id
                ]);
                return null;
            }

            // Check if invoice already exists
            if (\Schema::hasTable('invoices')) {
                $existingInvoice = DB::table('invoices')
                    ->where('appointment_id', $appointment->id)
                    ->first();

                if ($existingInvoice) {
                    Log::info('Invoice already exists for appointment', [
                        'appointment_id' => $appointment->id,
                        'invoice_id' => $existingInvoice->id
                    ]);
                    return (array) $existingInvoice;
                }
            }

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($appointment->clinic_id);

            // Create invoice record
            $invoiceData = [
                'clinic_id' => $appointment->clinic_id,
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $totalAmount,
                'status' => $appointment->payment_method === 'insurance' ? 'pending' : 'pending',
                'due_date' => now()->addDays(30), // 30 days payment terms
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add breakdown if available
            if (isset($pricing['breakdown'])) {
                $invoiceData['breakdown'] = json_encode($pricing['breakdown']);
            }

            // Create invoice in database
            if (\Schema::hasTable('invoices')) {
                $invoiceId = DB::table('invoices')->insertGetId($invoiceData);
                $invoiceData['id'] = $invoiceId;

                Log::info('Invoice generated automatically', [
                    'appointment_id' => $appointment->id,
                    'invoice_id' => $invoiceId,
                    'amount' => $totalAmount
                ]);

                // If payment method is cash/card and already paid, mark invoice as paid
                if (in_array($appointment->payment_method, ['cash', 'card']) && $appointment->status === 'completed') {
                    $this->markInvoiceAsPaid($invoiceId, $appointment->payment_method);
                }

                return $invoiceData;
            } else {
                // Fallback: Create payment record if invoices table doesn't exist
                if (\Schema::hasTable('payments')) {
                    $paymentId = DB::table('payments')->insertGetId([
                        'clinic_id' => $appointment->clinic_id,
                        'patient_id' => $appointment->patient_id,
                        'appointment_id' => $appointment->id,
                        'amount' => $totalAmount,
                        'status' => $appointment->payment_method === 'insurance' ? 'pending' : 'paid',
                        'payment_method' => $appointment->payment_method ?? 'cash',
                        'reference' => $invoiceNumber,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Payment record created (invoices table not found)', [
                        'appointment_id' => $appointment->id,
                        'payment_id' => $paymentId
                    ]);

                    return [
                        'id' => $paymentId,
                        'type' => 'payment',
                        'invoice_number' => $invoiceNumber,
                        'amount' => $totalAmount,
                        'status' => $appointment->payment_method === 'insurance' ? 'pending' : 'paid',
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice on appointment completion', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Mark invoice as paid
     * 
     * @param int $invoiceId
     * @param string $paymentMethod
     * @return bool
     */
    public function markInvoiceAsPaid(int $invoiceId, string $paymentMethod = 'cash'): bool
    {
        try {
            if (\Schema::hasTable('invoices')) {
                DB::table('invoices')
                    ->where('id', $invoiceId)
                    ->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method' => $paymentMethod,
                        'updated_at' => now()
                    ]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Failed to mark invoice as paid', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate unique invoice number
     * 
     * @param int $clinicId
     * @return string
     */
    protected function generateInvoiceNumber(int $clinicId): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        
        // Get last invoice number for this clinic this month
        $lastInvoice = null;
        if (\Schema::hasTable('invoices')) {
            $lastInvoice = DB::table('invoices')
                ->where('clinic_id', $clinicId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->first();
        }

        $sequence = 1;
        if ($lastInvoice && isset($lastInvoice->invoice_number)) {
            // Extract sequence from last invoice number (format: INV-YYYYMM-XXX)
            $parts = explode('-', $lastInvoice->invoice_number);
            if (count($parts) >= 3) {
                $sequence = (int) $parts[2] + 1;
            }
        }

        return sprintf('INV-%s%s-%03d', $year, $month, $sequence);
    }

    /**
     * Process insurance claim if payment method is insurance
     * 
     * @param ClinicAppointment $appointment
     * @param array $invoiceData
     * @return bool
     */
    public function processInsuranceClaim(ClinicAppointment $appointment, array $invoiceData): bool
    {
        if ($appointment->payment_method !== 'insurance') {
            return false;
        }

        try {
            // Create insurance claim record
            if (\Schema::hasTable('insurance_claims')) {
                DB::table('insurance_claims')->insert([
                    'clinic_id' => $appointment->clinic_id,
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'invoice_id' => $invoiceData['id'] ?? null,
                    'amount' => $invoiceData['amount'] ?? 0,
                    'status' => 'pending',
                    'submitted_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Insurance claim created', [
                    'appointment_id' => $appointment->id,
                    'invoice_id' => $invoiceData['id'] ?? null
                ]);

                return true;
            }
        } catch (\Exception $e) {
            Log::error('Failed to create insurance claim', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }
}

