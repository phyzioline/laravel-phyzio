<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'invoice_number',
        'treatment_plan',
        'total_cost',
        'discount',
        'final_amount',
        'invoice_date',
        'due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2'
    ];

    // Auto-generate invoice number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
            
            // Calculate final amount if not set
            if (empty($invoice->final_amount)) {
                $invoice->final_amount = $invoice->total_cost - $invoice->discount;
            }
        });

        static::saved(function ($invoice) {
            // Update status based on payments
            $invoice->updateStatus();
        });
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function payments()
    {
        return $this->hasMany(PatientPayment::class);
    }

    // Calculate total paid amount
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('payment_amount');
    }

    // Calculate remaining balance
    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->final_amount - $this->total_paid);
    }

    // Update invoice status based on payments
    public function updateStatus()
    {
        $totalPaid = $this->total_paid;
        
        if ($totalPaid == 0) {
            $this->status = 'unpaid';
        } elseif ($totalPaid >= $this->final_amount) {
            $this->status = 'paid';
        } else {
            $this->status = 'partially_paid';
        }
        
        $this->saveQuietly(); // Save without triggering events
    }

    // Status translations
    public function getStatusNameAttribute()
    {
        $statuses = [
            'unpaid' => __('Unpaid'),
            'partially_paid' => __('Partially Paid'),
            'paid' => __('Paid')
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}

