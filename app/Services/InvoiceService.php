<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\View;

/**
 * Invoice Service
 * 
 * Generates invoices for orders in HTML format.
 * Can be extended to generate PDF using DomPDF or similar library.
 */
class InvoiceService
{
    /**
     * Generate invoice data for an order.
     * 
     * @param Order $order
     * @return array Invoice data
     */
    public function generateInvoiceData(Order $order): array
    {
        $order->load(['items.product.productImages', 'user', 'shippingAddress']);
        
        $subtotal = $order->items->sum('total');
        $shipping = $order->shipping_total ?? 0;
        $total = $order->total;

        return [
            'order' => $order,
            'invoice_number' => $this->generateInvoiceNumber($order),
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => 0, // Can be added if tax is implemented
            'total' => $total,
            'items' => $order->items,
            'customer' => [
                'name' => $order->user->name ?? $order->name,
                'email' => $order->user->email ?? $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
            ],
            'company' => [
                'name' => config('app.name', 'Phyzioline'),
                'address' => config('app.company_address', ''),
                'phone' => config('app.company_phone', ''),
                'email' => config('app.company_email', ''),
                'tax_id' => config('app.company_tax_id', ''),
            ],
        ];
    }

    /**
     * Generate invoice number from order.
     * 
     * @param Order $order
     * @return string
     */
    protected function generateInvoiceNumber(Order $order): string
    {
        // Format: INV-YYYYMMDD-ORDERID
        return 'INV-' . $order->created_at->format('Ymd') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate HTML invoice view.
     * 
     * @param Order $order
     * @return \Illuminate\Contracts\View\View
     */
    public function generateHtmlInvoice(Order $order)
    {
        $data = $this->generateInvoiceData($order);
        return view('invoices.order', $data);
    }

    /**
     * Generate PDF invoice (requires PDF library).
     * 
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function generatePdfInvoice(Order $order)
    {
        $data = $this->generateInvoiceData($order);
        $html = view('invoices.order', $data)->render();
        
        // For now, return HTML. To enable PDF, install barryvdh/laravel-dompdf:
        // composer require barryvdh/laravel-dompdf
        // Then uncomment below:
        
        /*
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('invoice-' . $data['invoice_number'] . '.pdf');
        */
        
        // Temporary: Return HTML view
        return view('invoices.order', $data);
    }

    /**
     * Send invoice via email.
     * 
     * @param Order $order
     * @param string|null $email
     * @return bool
     */
    public function sendInvoiceEmail(Order $order, ?string $email = null): bool
    {
        try {
            $email = $email ?? $order->user->email ?? $order->email;
            
            if (!$email) {
                return false;
            }

            $data = $this->generateInvoiceData($order);
            
            \Illuminate\Support\Facades\Mail::send('emails.invoice', $data, function($message) use ($email, $data) {
                $message->to($email)
                        ->subject('Invoice #' . $data['invoice_number'] . ' - ' . config('app.name'));
            });

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send invoice email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

