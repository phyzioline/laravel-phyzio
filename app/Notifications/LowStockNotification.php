<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Product $product, public int $currentStock, public int $threshold)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $productName = $notifiable->locale === 'ar' 
            ? $this->product->product_name_ar 
            : $this->product->product_name_en;
        
        $urgency = $this->currentStock <= 3 ? 'URGENT' : 'Warning';
        
        return (new MailMessage)
                    ->subject("{$urgency}: Low Stock Alert - {$productName}")
                    ->greeting('Low Stock Alert!')
                    ->line("Your product **{$productName}** is running low on stock.")
                    ->line("**Current Stock:** {$this->currentStock} units")
                    ->line("**Alert Threshold:** {$this->threshold} units")
                    ->line("**SKU:** {$this->product->sku}")
                    ->action('Update Stock', route('dashboard.products.edit', $this->product->id))
                    ->line('Please restock soon to avoid out-of-stock situations.')
                    ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->product_name_en,
            'sku' => $this->product->sku,
            'current_stock' => $this->currentStock,
            'threshold' => $this->threshold,
            'urgency' => $this->currentStock <= 3 ? 'urgent' : 'warning',
            'message' => "Product {$this->product->product_name_en} has only {$this->currentStock} units remaining.",
        ];
    }
}

