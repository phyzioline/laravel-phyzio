<?php

namespace App\Services\Notifications;

use App\Models\ClinicAppointment;
use App\Models\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AppointmentReminderService
{
    /**
     * Create reminder for appointment
     * 
     * @param ClinicAppointment $appointment
     * @param string $type
     * @param int $minutesBefore
     * @return AppointmentReminder
     */
    public function createReminder(ClinicAppointment $appointment, string $type = 'email', int $minutesBefore = 1440): AppointmentReminder
    {
        $scheduledFor = $appointment->appointment_date->copy()->subMinutes($minutesBefore);

        return AppointmentReminder::create([
            'appointment_id' => $appointment->id,
            'reminder_type' => $type,
            'minutes_before' => $minutesBefore,
            'status' => 'pending',
            'scheduled_for' => $scheduledFor
        ]);
    }

    /**
     * Send pending reminders
     * 
     * @return int Number of reminders sent
     */
    public function sendPendingReminders(): int
    {
        $reminders = AppointmentReminder::where('status', 'pending')
            ->where('scheduled_for', '<=', now())
            ->with('appointment.patient')
            ->get();

        $sentCount = 0;

        foreach ($reminders as $reminder) {
            try {
                $this->sendReminder($reminder);
                $reminder->update([
                    'status' => 'sent',
                    'sent_at' => now()
                ]);
                $sentCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send appointment reminder', [
                    'reminder_id' => $reminder->id,
                    'error' => $e->getMessage()
                ]);
                $reminder->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }

    /**
     * Send individual reminder
     */
    protected function sendReminder(AppointmentReminder $reminder): void
    {
        $appointment = $reminder->appointment;
        $patient = $appointment->patient;

        switch ($reminder->reminder_type) {
            case 'email':
                $this->sendEmailReminder($appointment, $patient);
                break;
            case 'sms':
                $this->sendSMSReminder($appointment, $patient);
                break;
            case 'push':
                $this->sendPushReminder($appointment, $patient);
                break;
            case 'phone':
                $this->sendPhoneReminder($appointment, $patient);
                break;
        }
    }

    /**
     * Send email reminder
     */
    protected function sendEmailReminder(ClinicAppointment $appointment, $patient): void
    {
        if ($patient->email) {
            Mail::send('emails.appointment_reminder', [
                'appointment' => $appointment,
                'patient' => $patient
            ], function($message) use ($patient) {
                $message->to($patient->email)
                    ->subject('Appointment Reminder');
            });
        }
    }

    /**
     * Send SMS reminder
     */
    protected function sendSMSReminder(ClinicAppointment $appointment, $patient): void
    {
        // Integration with SMS service (Twilio, etc.)
        // Placeholder
        Log::info('SMS reminder would be sent', [
            'appointment_id' => $appointment->id,
            'patient_phone' => $patient->phone ?? 'N/A'
        ]);
    }

    /**
     * Send push notification
     */
    protected function sendPushReminder(ClinicAppointment $appointment, $patient): void
    {
        // Integration with push notification service
        // Placeholder
        Log::info('Push reminder would be sent', [
            'appointment_id' => $appointment->id
        ]);
    }

    /**
     * Send phone call reminder
     */
    protected function sendPhoneReminder(ClinicAppointment $appointment, $patient): void
    {
        // Integration with voice call service (Twilio, etc.)
        // Placeholder
        Log::info('Phone reminder would be sent', [
            'appointment_id' => $appointment->id,
            'patient_phone' => $patient->phone ?? 'N/A'
        ]);
    }
}

