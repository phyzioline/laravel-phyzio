# Deployment Instructions - EMR & Scheduling System

## 🚀 Server Deployment Steps

### 1. Pull Latest Changes
```bash
cd /path/to/your/laravel/project
git pull origin main
```

### 2. Run Database Migrations
```bash
php artisan migrate
```

This will create the following new tables:
- `clinical_notes`
- `clinical_templates`
- `clinical_timeline`
- `waitlists`
- `calendar_syncs`
- `intake_forms`
- `intake_form_responses`
- `appointment_reminders`
- `equipment_inventory`
- `equipment_reservations`

### 3. Seed Clinical Templates
```bash
php artisan db:seed --class=ClinicalTemplateSeeder
```

This will create 7 default clinical note templates for all specialties.

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 5. Optimize (Production Only)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Up Scheduled Tasks (Optional but Recommended)

Add to your server's crontab:
```bash
* * * * * cd /path/to/your/laravel/project && php artisan schedule:run >> /dev/null 2>&1
```

Then add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Send appointment reminders
    $schedule->call(function () {
        app(\App\Services\Notifications\AppointmentReminderService::class)->sendPendingReminders();
    })->everyMinute();
    
    // Check waitlist for available slots
    $schedule->call(function () {
        // Auto-book from waitlist logic
    })->hourly();
}
```

## 📋 What Was Added

### New Features in Sidebar
- ✅ **Clinical Notes (EMR)** - Added to sidebar after Clinical Episodes
- ✅ **Waitlist** - Added to sidebar after Appointments (placeholder for now)

### New Routes
- `/clinic/clinical-notes` - Clinical notes listing
- `/clinic/clinical-notes/create` - Create new note
- `/clinic/clinical-notes/{id}` - View note
- `/clinic/clinical-notes/{id}/edit` - Edit note
- `/clinic/clinical-notes/{id}/sign` - Sign note
- `/clinic/clinical-notes/templates` - Get templates (AJAX)
- `/clinic/clinical-notes/validate-coding` - Validate coding (AJAX)
- `/clinic/waitlist` - Waitlist (placeholder)

### New Database Tables
1. **Clinical EMR:**
   - `clinical_notes` - SOAP notes
   - `clinical_templates` - Note templates
   - `clinical_timeline` - Patient history

2. **Scheduling:**
   - `waitlists` - Patient waitlist
   - `calendar_syncs` - Calendar integration
   - `intake_forms` - Intake form templates
   - `intake_form_responses` - Form responses
   - `appointment_reminders` - Reminder tracking

3. **Equipment:**
   - `equipment_inventory` - Equipment tracking
   - `equipment_reservations` - Equipment bookings

## ⚙️ Configuration Needed

### 1. Voice-to-Text (Optional)
Currently uses browser Web Speech API (no server config needed).
For production cloud service:
- Add Google Cloud Speech-to-Text credentials (if using)
- Or AWS Transcribe credentials
- Or Azure Speech Services credentials

### 2. Calendar Sync (Future)
When implementing calendar sync:
- Google Calendar API credentials
- Microsoft Outlook API credentials
- OAuth redirect URLs

### 3. SMS/Push Notifications (Future)
When implementing reminders:
- Twilio credentials (for SMS)
- Firebase credentials (for push)
- Email service configuration

## ✅ Verification Checklist

After deployment, verify:

- [ ] Migrations ran successfully
- [ ] Clinical templates seeded
- [ ] Can access `/clinic/clinical-notes`
- [ ] Can create a clinical note
- [ ] Voice-to-text works in browser
- [ ] Coding validation works
- [ ] Can sign a note
- [ ] Sidebar shows "Clinical Notes (EMR)"
- [ ] Sidebar shows "Waitlist"

## 🔧 Troubleshooting

### Migration Errors
If migrations fail:
```bash
php artisan migrate:rollback --step=1
php artisan migrate
```

### Template Seeder Issues
If seeder fails:
```bash
php artisan db:seed --class=ClinicalTemplateSeeder --force
```

### Route Not Found
Clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

### View Not Found
Clear view cache:
```bash
php artisan view:clear
php artisan view:cache
```

## 📝 Notes

- **Clinical Notes (EMR)** is fully functional
- **Waitlist** route is a placeholder (controller/views pending)
- **Calendar Sync** services are ready but need OAuth setup
- **Appointment Reminders** service is ready but needs email/SMS config
- **Equipment Inventory** is ready for use

## 🎯 Next Steps (Optional)

1. Set up scheduled tasks for reminders
2. Configure email service for reminders
3. Set up calendar OAuth (when ready)
4. Create waitlist controller/views (when ready)

---

**Deployment Date:** January 2025  
**Version:** 1.0
