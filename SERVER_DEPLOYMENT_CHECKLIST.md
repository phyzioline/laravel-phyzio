# Server Deployment Checklist

## Required Steps on Server

### 1. Pull Latest Changes
```bash
cd /path/to/your/laravel/project
git pull origin main
```

### 2. Run Database Migrations
```bash
php artisan migrate
```

**New Migration:**
- `2025_12_30_000001_add_guest_fields_to_home_visits_table.php`
  - Makes `patient_id` nullable for guest bookings
  - Adds `guest_name`, `guest_email`, `guest_phone`, `is_guest_booking` fields

### 3. Clear and Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# For production, optimize:
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set Up Laravel Scheduler (CRITICAL for Payment Cycle Management)

The scheduler is required for automatic earnings settlements. Add this to your server's crontab:

```bash
crontab -e
```

Add this line (replace `/path/to/your/laravel/project` with your actual path):
```bash
* * * * * cd /path/to/your/laravel/project && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Commands:**
- `earnings:settle` - Runs daily at 1:00 AM UTC (processes pending earnings settlements)
- `payouts:process-auto` - Runs weekly on Sundays at 2:00 AM UTC (processes auto-payouts)

### 5. Test Scheduled Commands (Optional but Recommended)

Test the earnings settlement command manually:
```bash
php artisan earnings:settle
```

Expected output:
```
Starting earnings settlement process...
Settled X transactions
Total amount settled: $X.XX
Earnings settlement process completed!
```

### 6. Verify Storage Link (if not already done)
```bash
php artisan storage:link
```

## Summary of Changes

### New Features:
1. ✅ Guest bookings for home visits (no login required)
2. ✅ Payment cycle management system
3. ✅ Automatic earnings settlements (daily cron job)
4. ✅ Admin controls for settlement management

### New Files:
- `app/Services/EarningsSettlementService.php` - Settlement processing service
- `app/Console/Commands/ProcessEarningsSettlements.php` - Scheduled command
- `database/migrations/2025_12_30_000001_add_guest_fields_to_home_visits_table.php` - Guest booking migration

### Updated Files:
- `app/Services/TherapistPayoutService.php` - Now creates EarningsTransaction records
- `app/Http/Controllers/Dashboard/EarningsController.php` - Added settlement management
- `routes/console.php` - Added earnings settlement schedule
- Various controllers updated to use new earnings system

## Important Notes

⚠️ **CRITICAL**: The Laravel scheduler must be set up in crontab for automatic settlements to work. Without it, earnings will remain in "pending" status and won't automatically move to "available".

✅ **Optional**: You can manually process settlements from the admin dashboard at `/dashboard/financials/earnings` using the "Process Settlements Now" button.

## Verification Steps

After deployment, verify:
1. ✅ Guest bookings work without login
2. ✅ Earnings transactions are created with `hold_until` dates
3. ✅ Admin can see settlement statistics
4. ✅ Admin can manually process settlements
5. ✅ Scheduled command runs (check logs: `storage/logs/laravel.log`)





