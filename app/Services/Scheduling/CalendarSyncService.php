<?php

namespace App\Services\Scheduling;

use App\Models\CalendarSync;
use App\Models\ClinicAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CalendarSyncService
{
    /**
     * Sync appointments to external calendar
     * 
     * @param ClinicAppointment $appointment
     * @param CalendarSync $calendarSync
     * @return bool
     */
    public function syncAppointmentToCalendar(ClinicAppointment $appointment, CalendarSync $calendarSync): bool
    {
        if (!$calendarSync->sync_enabled) {
            return false;
        }

        try {
            switch ($calendarSync->provider) {
                case 'google':
                    return $this->syncToGoogleCalendar($appointment, $calendarSync);
                case 'outlook':
                    return $this->syncToOutlookCalendar($appointment, $calendarSync);
                default:
                    Log::warning('Unsupported calendar provider', [
                        'provider' => $calendarSync->provider
                    ]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Calendar sync failed', [
                'appointment_id' => $appointment->id,
                'provider' => $calendarSync->provider,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Sync to Google Calendar
     */
    protected function syncToGoogleCalendar(ClinicAppointment $appointment, CalendarSync $calendarSync): bool
    {
        // Refresh token if expired
        if ($calendarSync->isTokenExpired()) {
            $this->refreshGoogleToken($calendarSync);
        }

        $eventData = [
            'summary' => "PT Appointment: {$appointment->patient->first_name} {$appointment->patient->last_name}",
            'description' => "Physical Therapy Appointment\nSpecialty: {$appointment->specialty}\nVisit Type: {$appointment->visit_type}",
            'start' => [
                'dateTime' => $appointment->appointment_date->toIso8601String(),
                'timeZone' => config('app.timezone', 'UTC')
            ],
            'end' => [
                'dateTime' => $appointment->appointment_date->copy()->addMinutes($appointment->duration_minutes)->toIso8601String(),
                'timeZone' => config('app.timezone', 'UTC')
            ]
        ];

        $response = Http::withToken($calendarSync->access_token)
            ->post("https://www.googleapis.com/calendar/v3/calendars/{$calendarSync->calendar_id}/events", $eventData);

        if ($response->successful()) {
            // Store Google event ID in appointment metadata if needed
            $calendarSync->markAsSynced();
            return true;
        }

        return false;
    }

    /**
     * Sync to Outlook Calendar
     */
    protected function syncToOutlookCalendar(ClinicAppointment $appointment, CalendarSync $calendarSync): bool
    {
        // Refresh token if expired
        if ($calendarSync->isTokenExpired()) {
            $this->refreshOutlookToken($calendarSync);
        }

        $eventData = [
            'subject' => "PT Appointment: {$appointment->patient->first_name} {$appointment->patient->last_name}",
            'body' => [
                'contentType' => 'HTML',
                'content' => "Physical Therapy Appointment<br>Specialty: {$appointment->specialty}<br>Visit Type: {$appointment->visit_type}"
            ],
            'start' => [
                'dateTime' => $appointment->appointment_date->toIso8601String(),
                'timeZone' => config('app.timezone', 'UTC')
            ],
            'end' => [
                'dateTime' => $appointment->appointment_date->copy()->addMinutes($appointment->duration_minutes)->toIso8601String(),
                'timeZone' => config('app.timezone', 'UTC')
            ]
        ];

        $response = Http::withToken($calendarSync->access_token)
            ->post("https://graph.microsoft.com/v1.0/me/calendar/events", $eventData);

        if ($response->successful()) {
            $calendarSync->markAsSynced();
            return true;
        }

        return false;
    }

    /**
     * Refresh Google access token
     */
    protected function refreshGoogleToken(CalendarSync $calendarSync): bool
    {
        // Implementation would use Google OAuth refresh token flow
        // This is a placeholder
        Log::info('Refreshing Google calendar token', ['calendar_sync_id' => $calendarSync->id]);
        return true;
    }

    /**
     * Refresh Outlook access token
     */
    protected function refreshOutlookToken(CalendarSync $calendarSync): bool
    {
        // Implementation would use Microsoft OAuth refresh token flow
        // This is a placeholder
        Log::info('Refreshing Outlook calendar token', ['calendar_sync_id' => $calendarSync->id]);
        return true;
    }

    /**
     * Import events from external calendar
     * 
     * @param CalendarSync $calendarSync
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function importFromCalendar(CalendarSync $calendarSync, Carbon $startDate, Carbon $endDate): array
    {
        // This would fetch events from external calendar and return them
        // Implementation depends on provider
        return [];
    }
}

