# Fix Vendor Dashboard 404 Error

## The Problem
The vendor routes exist in code but Laravel hasn't registered them yet.

## Solution - Clear Route Cache

Run these commands on your server:

```bash
ssh phyziolinegit@147.93.85.27
cd /home/phyziolinegit/htdocs/phyzioline.com

# Clear ALL caches
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optional: Re-cache routes for performance
php artisan route:cache
php artisan config:cache
```

## After Cache Clear

Test these URLs (as vendor user):

✅ **Vendor Dashboard**: `https://phyzioline.com/en/vendor/dashboard`
✅ **Shipments**: `https://phyzioline.com/en/vendor/shipments`
✅ **Wallet**: `https://phyzioline.com/en/vendor/wallet`
✅ **Orders**: `https://phyzioline.com/en/vendor/orders`

## If Admin Dashboard Also 404

The admin dashboard (`/en/dashboard/home`) should work at:
- `https://phyzioline.com/dashboard/home` (without `/en/`)

If it's broken with `/en/`, that's a separate routing issue we can fix.

## Verify Routes Are Loaded

```bash
php artisan route:list | grep vendor
```

Should show:
```
GET|HEAD  en/vendor/dashboard  ......  vendor.dashboard
GET|HEAD  en/vendor/orders  ........  vendor.orders.index
GET|HEAD  en/vendor/shipments  .....  vendor.shipments.index
GET|HEAD  en/vendor/wallet  ........  vendor.wallet
```
