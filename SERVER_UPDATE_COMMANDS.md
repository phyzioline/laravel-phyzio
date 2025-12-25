# Server Update Commands - Quick Copy & Paste

## 🚀 All-in-One Command Block

Copy and paste this entire block into your server SSH terminal:

```bash
cd /home/phyziolinegit/htdocs/phyzioline.com && \
git pull origin main && \
php artisan migrate --force && \
php artisan cache:clear && \
php artisan config:clear && \
php artisan route:clear && \
php artisan view:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
echo "✅ Update completed successfully!"
```

---

## 📋 Step-by-Step Commands (If you prefer to run one by one)

```bash
# Navigate to project directory
cd /home/phyziolinegit/htdocs/phyzioline.com

# 1. Pull the latest changes
git pull origin main

# 2. Run migration (if any new migrations exist)
php artisan migrate --force

# 3. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 4. Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ What This Update Includes

1. **Selected Text Color Fix** - Text selection now shows in teal color instead of white
2. **Payment Method Styling** - Larger, more colorful dropdown with hover effects
3. **Payment Method Validation** - Shows error if customer tries to order without selecting payment method
4. **Cash Order Route Fix** - Fixed redirect issue for cash orders

---

## 🔍 Verification

After running the commands, verify:

1. ✅ Home page text selection shows in teal color
2. ✅ Payment method dropdown is larger and styled
3. ✅ Order form shows error if payment method not selected
4. ✅ Cash orders redirect correctly after completion

---

**Status:** Ready to Deploy ✅  
**Risk Level:** LOW (UI improvements only, no breaking changes)

