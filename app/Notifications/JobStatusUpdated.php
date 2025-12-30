<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($application, $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Add 'mail' later
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Job Application Update: ' . ucfirst($this->status))
                    ->line('The status of your application for "' . $this->application->job->title . '" has changed.')
                    ->line('New Status: ' . ucfirst($this->status))
                    ->action('View Application', url('/jobs/applications')) // Adjust route
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
            'type' => 'job_application_update',
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title ?? 'Unknown Job',
            'status' => $this->status,
            'message' => 'Your application status for ' . ($this->application->job->title ?? 'Job') . ' has been updated to ' . $this->status,
            'url' => route('web.jobs.show.' . app()->getLocale(), $this->application->job_id),
        ];
    }
}
