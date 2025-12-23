# Payout System Documentation

## Overview
The payout system manages vendor earnings and withdrawal requests. It tracks vendor payments from orders, manages wallet balances, and handles payout requests through an approval workflow.

---

## How the Payout System Works

### 1. **Vendor Wallet System**
The system uses a multi-balance wallet structure:

- **Pending Balance**: Earnings from orders that are on hold (14-day hold period)
- **Available Balance**: Funds ready for withdrawal after hold period expires
- **On Hold Balance**: Funds frozen due to disputes/returns
- **Total Earned**: Lifetime earnings tracker

**Location**: `app/Models/VendorWallet.php`, `app/Services/WalletService.php`

### 2. **Payment Flow**

#### Step 1: Order Payment
When a customer pays for an order:
- Commission is calculated (default: 15%)
- Vendor earnings are calculated: `subtotal - commission`
- A `VendorPayment` record is created with status `pending`
- Funds are added to vendor's **pending balance**
- A 14-day hold period is set (`hold_until` date)

**Location**: `app/Services/Web/OrderService.php::calculateVendorPayments()`

#### Step 2: Settlement (Automatic)
After the hold period expires:
- Daily cron job processes settlements (`WalletService::processSettlements()`)
- Funds move from **pending** → **available balance**
- Vendor can now request payouts

**Location**: `app/Services/WalletService.php::processSettlements()`

#### Step 3: Payout Request (Vendor)
Vendor requests withdrawal:
- Minimum payout: **100** (currency units)
- Amount deducted from **available balance**
- `Payout` record created with status `pending`

**Location**: `app/Services/PayoutService.php::requestPayout()`

#### Step 4: Admin Approval
Admin reviews and approves:
- Status changes: `pending` → `processing`
- Admin ID and timestamp recorded

**Location**: `app/Services/PayoutService.php::approvePayout()`

#### Step 5: Mark as Paid
After bank transfer:
- Admin enters reference number
- Status changes: `processing` → `paid`
- Payment timestamp recorded

**Location**: `app/Services/PayoutService.php::markAsPaid()`

---

## Admin Management Interface

### 1. **Vendor Payouts Management**
**Route**: `/dashboard/payouts`  
**Controller**: `app/Http/Controllers/Dashboard/PayoutController.php`  
**View**: `resources/views/dashboard/payouts/index.blade.php`

**Features**:
- View all payout requests with filters (status, vendor)
- Statistics dashboard:
  - Pending requests count & amount
  - Processing count & amount
  - Paid today & this month totals
- Filter by status: All, Pending, Processing, Paid
- Filter by vendor

**Actions Available**:
1. **View Details**: Click "Details" to see full payout information
2. **Approve**: Move pending → processing
3. **Mark as Paid**: After bank transfer, add reference number
4. **Cancel/Reject**: Return funds to vendor wallet

### 2. **Payout Details Page**
**Route**: `/dashboard/payouts/{id}`  
**View**: `resources/views/dashboard/payouts/show.blade.php`

**Shows**:
- Payout amount, method, request date
- Vendor information
- Vendor wallet summary (available, pending balances)
- Status-specific action buttons:
  - **Pending**: Approve button
  - **Processing**: Mark as Paid form (with reference number)
  - **Pending/Processing**: Cancel/Reject form

### 3. **Vendor Payments Tracking**
**Route**: `/dashboard/payments`  
**Controller**: `app/Http/Controllers/Dashboard/PaymentController.php`  
**View**: `resources/views/dashboard/pages/payments/index.blade.php`

**Features**:
- View all vendor payment transactions
- Statistics:
  - Total earnings (paid)
  - Pending clearance amount
  - Last payout date
- Filters:
  - By vendor
  - By status (pending, paid, cancelled)
  - By date range
  - By order number/reference

**Transaction Details Shown**:
- Order number
- Date
- Vendor name (admin only)
- Product/item
- Quantity
- Subtotal
- Commission (15%)
- Net earnings
- Status

**Admin Actions**:
- Mark pending payments as "Paid"
- View payment details

---

## Navigation & Access Points

### Sidebar Menu Locations:

1. **Financials → Earnings & Payouts**
   - Route: `dashboard.payments.index`
   - Shows vendor payment transactions
   - Available to: Admins with `financials-index` permission

2. **Multi-Vendor → Vendor Payouts**
   - Route: `dashboard.payouts.index`
   - Shows payout requests management
   - Available to: Admins only

3. **Financials → My Wallet** (Vendor only)
   - Route: `dashboard.vendor.wallet`
   - Vendors can view their wallet and request payouts

---

## Database Structure

### Tables:

1. **`vendor_wallets`**
   - `vendor_id`, `pending_balance`, `available_balance`, `on_hold_balance`, `total_earned`

2. **`vendor_payments`**
   - Tracks earnings from each order item
   - Fields: `vendor_id`, `order_id`, `order_item_id`, `vendor_earnings`, `commission_amount`, `status`, `hold_until`, `settled`

3. **`payouts`**
   - Tracks withdrawal requests
   - Fields: `vendor_id`, `amount`, `status`, `payout_method`, `reference_number`, `approved_by`, `approved_at`, `paid_at`

---

## Key Services & Methods

### PayoutService (`app/Services/PayoutService.php`)
- `requestPayout()` - Vendor creates payout request
- `approvePayout()` - Admin approves payout
- `markAsPaid()` - Admin marks as paid after transfer
- `cancelPayout()` - Cancel and return funds
- `getAllPayouts()` - Get all payouts with filters
- `getPayoutStatistics()` - Dashboard statistics

### WalletService (`app/Services/WalletService.php`)
- `getVendorWallet()` - Get or create wallet
- `addToPending()` - Add earnings to pending balance
- `settlePendingBalance()` - Move pending → available
- `processSettlements()` - Daily cron job for settlements
- `getWalletSummary()` - Get wallet stats for vendor

---

## Workflow Summary

```
Order Payment
    ↓
VendorPayment Created (pending)
    ↓
Funds → Pending Balance
    ↓
[14-day hold period]
    ↓
Daily Settlement Cron
    ↓
Funds → Available Balance
    ↓
Vendor Requests Payout
    ↓
Payout Created (pending)
    ↓
Admin Approves (processing)
    ↓
Admin Marks as Paid (paid)
```

---

## Admin Actions Checklist

### Daily Tasks:
1. Check `/dashboard/payouts` for pending requests
2. Review payout details and vendor wallet status
3. Approve legitimate requests
4. Process bank transfers
5. Mark payouts as paid with reference numbers

### Weekly Tasks:
1. Review vendor payment transactions at `/dashboard/payments`
2. Check for any cancelled/rejected payouts
3. Monitor settlement process

### Monthly Tasks:
1. Review payout statistics
2. Check for any stuck payouts
3. Verify commission calculations

---

## Important Notes

- **Minimum Payout**: Configurable via admin settings (default: 100 currency units)
- **Hold Period**: Configurable via admin settings (default: 7 days, changed from 14)
- **Commission Rate**: Default 15% (can be customized per vendor)
- **Settlement**: Runs daily via cron job
- **Auto-Payout**: Can be enabled/disabled by admin, automatically creates payout requests
- **Cancellation**: Returns funds to vendor's available balance

---

## Routes Reference

```php
// Payout Management
GET  /dashboard/payouts              - List all payouts
GET  /dashboard/payouts/{id}         - View payout details
POST /dashboard/payouts/{id}/approve - Approve payout
POST /dashboard/payouts/{id}/mark-paid - Mark as paid
POST /dashboard/payouts/{id}/cancel  - Cancel payout
POST /dashboard/payouts/bulk-approve - Bulk approve

// Payout Settings (Admin Only)
GET  /dashboard/payouts/settings     - View/Edit payout settings
POST /dashboard/payouts/settings    - Update payout settings
POST /dashboard/payouts/settings/trigger - Manually trigger auto-payout

// Payment Tracking
GET  /dashboard/payments             - List vendor payments (with view parameter)
GET  /dashboard/payments?view=statement - Statement view
GET  /dashboard/payments?view=transaction - Transaction view
GET  /dashboard/payments?view=all-statements - All statements view
GET  /dashboard/payments?view=disbursements - Disbursements view
GET  /dashboard/payments?view=advertising - Advertising invoice history
GET  /dashboard/payments?view=reports - Reports repository
GET  /dashboard/payments/{id}        - View payment details
POST /dashboard/payments/{id}/status - Update payment status
```

## New Features (Latest Update)

### 1. Payment Dashboard Tabs
All payment views are now accessible via tabs:
- **Statement View**: Overview with earnings trend chart
- **Transaction View**: Detailed transaction history table
- **All Statements**: Monthly grouped statements
- **Disbursements**: Payout history and tracking
- **Advertising Invoice History**: Placeholder for future advertising features
- **Reports Repository**: Report generation and analytics

### 2. Auto-Payout System
Automated payout creation system:
- **Configurable Hold Period**: Admin can set hold period (default: 7 days)
- **Auto-Payout Enabled/Disabled**: Toggle via admin settings
- **Automatic Creation**: System automatically creates payout requests when:
  - Vendor has available balance >= minimum payout
  - No existing pending/processing payout exists
  - Auto-payout is enabled
- **Console Command**: `php artisan payouts:process-auto`
  - Processes settlements (moves pending → available)
  - Creates auto-payouts for eligible vendors
- **Admin Dashboard**: `/dashboard/payouts/settings`
  - Configure hold period, minimum payout, frequency
  - Enable/disable auto-payout
  - Manually trigger auto-payout process
  - View recent auto-payout transactions

### 3. Payout Settings Management
New admin interface for managing payout system:
- **Hold Period**: 1-30 days (default: 7)
- **Minimum Payout**: Configurable amount (default: 100)
- **Auto-Payout Toggle**: Enable/disable automatic payout creation
- **Frequency**: Weekly, Bi-weekly, or Monthly
- **Manual Trigger**: Button to manually run auto-payout process
- **Statistics**: Quick stats on pending/processing/paid payouts

### 4. Enhanced Sidebar Navigation
- Added "Payout Settings" link in Financials section (admin)
- Added "Payout Settings" link in Multi-Vendor section (admin)
- Improved font scaling for better readability

### 5. Console Command Scheduling
To enable automatic payout processing, schedule the command in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Process settlements and create auto-payouts
    // Run weekly (adjust frequency based on settings)
    $schedule->command('payouts:process-auto')
        ->weekly()
        ->sundays()
        ->at('02:00');
}
```

Or run manually:
```bash
php artisan payouts:process-auto
```

---

## Troubleshooting Guide

### Issue: Payout Stuck in "Pending"
**Symptoms**: Payout request remains in pending status and doesn't progress.

**Solutions**:
1. Check if vendor has sufficient available balance
   - Go to vendor wallet: `/dashboard/vendor/wallet`
   - Verify `available_balance >= payout amount`
2. Verify payout amount meets minimum threshold
   - Check settings: `/dashboard/payouts/settings`
   - Default minimum: 100 currency units
3. Check application logs for errors
   - Location: `storage/logs/laravel.log`
   - Search for "Payout" or "payout" entries
4. Verify auto-payout is enabled (if expecting automatic creation)
   - Admin → Payout Settings → Enable Auto-Payout checkbox
5. Check if payout was manually created vs auto-generated
   - Auto payouts have `payout_method = 'auto_weekly'`
   - Manual payouts have method like `bank_transfer`, `payoneer`, etc.

### Issue: Payments Not Settling
**Symptoms**: Funds remain in pending balance and don't move to available.

**Solutions**:
1. Verify cron job is running
   ```bash
   # Check if Laravel scheduler is running
   crontab -l | grep schedule:run
   # Should show: * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
   ```
2. Check `hold_until` dates in `vendor_payments` table
   ```sql
   SELECT id, vendor_id, hold_until, settled, status 
   FROM vendor_payments 
   WHERE settled = 0 AND hold_until <= NOW();
   ```
3. Ensure `settled` flag is false for eligible payments
   - Payments with `hold_until <= today` should be settled
4. Verify hold period setting in admin panel
   - Admin → Payout Settings → Hold Period (Days)
   - Default: 7 days
5. Manually trigger settlement process
   ```bash
   php artisan tinker
   >>> app(\App\Services\WalletService::class)->processSettlements();
   ```

### Issue: Cannot Cancel Paid Payout
**Symptoms**: Attempting to cancel a payout that's already paid.

**Solutions**:
- Paid payouts cannot be cancelled (by design for audit trail)
- Use refund process if payment needs to be reversed
- Contact vendor directly to resolve payment issues
- Create a new payout entry if refund is processed

### Issue: Auto-Payout Not Working
**Symptoms**: Auto-payouts are not being created automatically.

**Solutions**:
1. Check if auto-payout is enabled in settings
   - Navigate: `/dashboard/payouts/settings`
   - Verify "Enable Auto-Payout" checkbox is checked
2. Verify minimum payout threshold is met
   - Vendor's `available_balance` must be >= minimum payout amount
   - Check vendor wallet balance
3. Check if vendor already has pending/processing payout
   - System won't create duplicate payouts
   - Review existing payouts: `/dashboard/payouts`
4. Review console command execution
   ```bash
   # Test command manually
   php artisan payouts:process-auto
   
   # Check output for errors
   # Should show: "Created X auto-payouts"
   ```
5. Verify command is scheduled (if using scheduler)
   - Check `routes/console.php` for scheduled command (Laravel 11)
   - Or check `app/Console/Kernel.php` for scheduled command (Laravel 10)
   - Ensure cron job is running
6. Check application logs
   - Look for "Auto-payout" entries in `storage/logs/laravel.log`
   - Review any error messages

### Issue: Hold Period Not Working
**Symptoms**: Funds become available immediately or after wrong period.

**Solutions**:
1. Verify hold period setting
   - Admin → Payout Settings → Hold Period (Days)
   - Should be set to desired number (default: 7)
2. Check `hold_until` calculation
   - Should be: `order_date + hold_period_days`
   - Review `vendor_payments` table for correct dates
3. Ensure settlement process is running
   - Daily cron job should process eligible payments
4. Check database for correct hold dates
   ```sql
   SELECT id, created_at, hold_until, 
          DATEDIFF(hold_until, created_at) as days_held
   FROM vendor_payments 
   WHERE settled = 0;
   ```

### Issue: Settings Not Saving
**Symptoms**: Payout settings changes are not persisting.

**Solutions**:
1. Verify admin permissions
   - Only admin users can modify settings
2. Check database connection
   - Ensure `payout_settings` table exists
   - Run migration if needed: `php artisan migrate`
3. Review form validation errors
   - Check browser console for JavaScript errors
   - Review Laravel validation messages
4. Verify database write permissions
   - Check file permissions on database
   - Ensure application has write access

