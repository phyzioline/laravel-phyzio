<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $order)
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
        return (new MailMessage)
                    ->subject('New Order Received - Order #' . $this->order->id)
                    ->greeting('New Order Alert!')
                    ->line('A new order has been placed.')
                    ->line('**Order ID:** ' . $this->order->id)
                    ->line('**Customer:** ' . $this->order->name)
                    ->line('**Total:** ' . $this->order->total . ' EGP')
                    ->line('**Payment Method:** ' . $this->order->payment_method)
                    ->action('View Order', route('dashboard.orders.show', $this->order->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->name,
            'total' => $this->order->total,
            'status' => $this->order->status,
            'payment_method' => $this->order->payment_method,
        ];
    }
}
