# 🚀 DEPLOYMENT INSTRUCTIONS
## Fix Route Errors - Deploy to Server

**Server:** 147.93.85.27  
**SSH User:** phyziolinegit  
**Project Path:** `/home/phyziolinegit/htdocs/phyzioline.com`

---

## ✅ WHAT WAS FIXED

Fixed route errors by adding locale prefix with fallback to all route calls:
- `view_login` → `(app()->getLocale() ?: 'en') . '.view_login'`
- `web.shop.search` → `(app()->getLocale() ?: 'en') . '.web.shop.search'`
- `login`, `register`, `forget_password`, `verify` routes

**Files Updated (10 files):**
- `resources/views/web/auth/login.blade.php`
- `resources/views/web/auth/register.blade.php`
- `resources/views/web/auth/forget_password.blade.php`
- `resources/views/web/auth/otp.blade.php`
- `resources/views/web/layouts/header.blade.php`
- `resources/views/web/layouts/sidebar.blade.php`
- `resources/views/web/layouts/footer.blade.php`
- `resources/views/web/pages/index.blade.php`
- `resources/views/web/pages/show.blade.php`
- `resources/views/web/pages/jobs/show.blade.php`

---

## 📋 DEPLOYMENT METHODS

### **Method 1: SSH Deployment (Recommended - Fastest)**

#### Step 1: Connect to Server
```bash
ssh phyziolinegit@147.93.85.27
```
*Enter your password when prompted*

#### Step 2: Navigate to Project
```bash
cd /home/phyziolinegit/htdocs/phyzioline.com
```

#### Step 3: Pull Latest Code from GitHub
```bash
git pull origin main
```

#### Step 4: Clear All Caches (IMPORTANT!)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### Step 5: Optimize (Optional but Recommended)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 6: Exit SSH
```bash
exit
```

**✅ Done! Your site should now work correctly.**

---

### **Method 2: Using the Deployment Script**

If you have the `deploy.sh` script on the server:

```bash
# Connect to server
ssh phyziolinegit@147.93.85.27

# Navigate to project
cd /home/phyziolinegit/htdocs/phyzioline.com

# Make script executable (first time only)
chmod +x deploy.sh

# Run deployment script
./deploy.sh
```

---

### **Method 3: CloudPanel File Manager (If SSH Not Working)**

1. **Login to CloudPanel:**
   - URL: `https://147.93.85.27:8443`
   - Use your CloudPanel credentials

2. **Navigate to File Manager:**
   - Go to your Laravel project directory

3. **Pull from Git (if Git is available in CloudPanel):**
   - Use the terminal in CloudPanel
   - Run: `git pull origin main`

4. **OR Upload Files Manually:**
   - Upload the 10 modified view files from your local machine
   - Replace the existing files in:
     - `resources/views/web/auth/`
     - `resources/views/web/layouts/`
     - `resources/views/web/pages/`

5. **Clear Cache via CloudPanel Terminal:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

---

## 🔍 VERIFICATION STEPS

After deployment, verify the fixes work:

1. **Test Homepage:**
   - Visit: `https://phyzioline.com/en` or `https://phyzioline.com/ar`
   - Should load without errors

2. **Test Search:**
   - Try the search bar on homepage
   - Should work without "Route not found" error

3. **Test Login/Register:**
   - Click "Login" or "Register" links
   - Should navigate without errors

4. **Check Browser Console:**
   - Open browser DevTools (F12)
   - Check for any JavaScript errors
   - Check Network tab for failed requests

---

## ⚠️ TROUBLESHOOTING

### If you get "Route not found" errors after deployment:

1. **Clear route cache:**
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

2. **Check if locale is set correctly:**
   ```bash
   php artisan tinker
   >>> app()->getLocale()
   ```
   Should return 'en' or 'ar'

3. **Verify routes exist:**
   ```bash
   php artisan route:list | grep view_login
   php artisan route:list | grep web.shop.search
   ```

### If SSH connection fails:

- Use CloudPanel File Manager method instead
- Or check if your IP is whitelisted on the server

### If site shows blank page:

1. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check file permissions:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

3. **Clear all caches again:**
   ```bash
   php artisan optimize:clear
   ```

---

## 📝 QUICK REFERENCE

**One-Line Deployment (SSH):**
```bash
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && git pull origin main && php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear"
```

**Check Current Git Status:**
```bash
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && git status"
```

**View Recent Commits:**
```bash
ssh phyziolinegit@147.93.85.27 "cd /home/phyziolinegit/htdocs/phyzioline.com && git log --oneline -5"
```

---

## ✅ DEPLOYMENT CHECKLIST

Before deploying:
- [x] Code committed to GitHub
- [ ] Tested locally (if possible)
- [ ] Have SSH access or CloudPanel access
- [ ] Know server password/credentials

After deploying:
- [ ] Clear all caches
- [ ] Test homepage loads
- [ ] Test search functionality
- [ ] Test login/register links
- [ ] Check for errors in browser console
- [ ] Verify routes work in both English and Arabic

---

## 🆘 NEED HELP?

If something goes wrong:

1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Check server error logs:** Usually in CloudPanel
3. **Rollback if needed:**
   ```bash
   git reset --hard HEAD~1
   php artisan optimize:clear
   ```

---

**Last Updated:** December 21, 2025  
**Commit:** b5ffb95 - Fix route errors: Add locale prefix with fallback

