<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupplierOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $items;

    // Pass specific items for this vendor
    public function __construct(Order $order, $items)
    {
        $this->order = $order;
        $this->items = $items;
    }

    public function build()
    {
        return $this->subject('New Order Received - PhyzioLine')
                    ->view('mail.supplier_order');
    }
}
