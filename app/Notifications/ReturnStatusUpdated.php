<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $returnRequest;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($returnRequest, $status)
    {
        $this->returnRequest = $returnRequest;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Return Request Update: ' . ucfirst($this->status))
                    ->line('Your return request status has changed.')
                    ->line('New Status: ' . ucfirst($this->status))
                    ->action('View Return', url('/returns/' . $this->returnRequest->id))
                    ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'return_request_update',
            'return_id' => $this->returnRequest->id,
            'status' => $this->status,
            'message' => 'Return request #' . $this->returnRequest->id . ' is now ' . $this->status,
            'url' => route('returns.show.' . app()->getLocale(), $this->returnRequest->id),
        ];
    }
}
