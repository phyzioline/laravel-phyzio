# Database Migration Instructions

## ✅ Database Layer Complete

I've successfully created the complete database foundation for the multi-vendor shipping system:

### New Tables Created (7 migrations):
1. **shipments** - Track vendor shipments with courier and tracking info
2. **vendor_wallets** - Escrow balance management (pending, available, on-hold)
3. **payouts** - Vendor withdrawal requests and payment history
4. **tracking_logs** - Audit trail for shipment status changes
5. **returns** - Customer return requests and refunds

### Modified Tables (2 migrations):
6. **items_orders** - Added `shipment_id` foreign key
7. **vendor_payments** - Added `hold_until` and `settled` fields

### Models Created (5 new + 2 updated):
- `Shipment.php` - Full shipment management
- `VendorWallet.php` - Balance operations (addPending, releasePending, etc.)
- `Payout.php` - Payout tracking
- `TrackingLog.php` - Status history
- `ReturnModel.php` - Return handling
- Updated `Order.php` - Added shipments() and returns() relationships
- Updated `ItemsOrder.php` - Added shipment() and return() relationships

## 🚀 How to Run Migrations

**IMPORTANT**: These migrations will NOT break existing functionality. They only ADD new tables and columns.

### Option 1: Using Terminal (Recommended)

Open PowerShell in your Laravel directory and run:

```powershell
$env:PATH = "d:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;$env:PATH"
php artisan migrate
```

### Option 2: Via Command Prompt

```cmd
set PATH=d:\laravel\bin\php;d:\laravel\bin\node;d:\laravel\bin;%PATH%
php artisan migrate
```

### Option 3: Direct Path

```powershell
d:\laravel\bin\php\php.exe d:\laravel\artisan migrate
```

## ⚠️ Safety Checks

Before running migrations:

✅ **Backup your database** (just in case):
```powershell
.\export_mysql_database.ps1
```

✅ **Preview migrations** (see what will happen without actually running):
```powershell
$env:PATH = "d:\laravel\bin\php;$env:PATH"
php artisan migrate --pretend
```

## 🔍 What Will Happen

The migrations will:
1. Create 5 new tables (no impact on existing data)
2. Add `shipment_id` column to `items_orders` (nullable, safe)
3. Add `hold_until` and `settled` columns to `vendor_payments` (with defaults, safe)

**No existing data will be modified or deleted.**

## ✅After Migration

Once migrations run successfully, verify with:

```sql
SHOW TABLES; -- Should see: shipments, vendor_wallets, payouts, tracking_logs, returns
DESCRIBE shipments; -- Check structure
```

## Next Steps

After migrations are complete, we'll move to:
1. Building the OrderService (order splitting logic)
2. Creating the vendor dashboard
3. Adding admin controls

---
**Status**: Ready to migrate safely ✅
