<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visit;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($visit, $status)
    {
        $this->visit = $visit;
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
                    ->subject('Home Visit Update: ' . ucfirst($this->status))
                    ->line('The status of your home visit has changed.')
                    ->line('New Status: ' . ucfirst($this->status))
                    ->action('View Details', url('/home_visits/status/' . $this->visit->id))
                    ->line('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'home_visit_update',
            'visit_id' => $this->visit->id,
            'status' => $this->status,
            'message' => 'Home visit status updated to: ' . $this->status,
            'url' => route('patient.home_visits.show.' . app()->getLocale(), $this->visit->id),
        ];
    }
}
