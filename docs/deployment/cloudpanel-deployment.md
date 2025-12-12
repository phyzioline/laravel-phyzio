# 🎯 QUICK DEPLOYMENT - CloudPanel Method

## For Server: 147.93.85.27

Since your SSH keeps disconnecting, use **CloudPanel File Manager** - it's safer and more reliable!

---

## 🔐 STEP 1: Login to CloudPanel

**URL:** https://147.93.85.27:8443  
**Your CloudPanel username and password**

---

## 📦 STEP 2: Backup (CRITICAL!)

1. Click **"Files"** → **"File Manager"**
2. Navigate to your Laravel directory (likely: `/home/phyziolinegit/htdocs/phyzioline.com`)
3. Download these folders to your computer:
   - Right-click `resources` → Download
   - Right-click `app` → Download
4. Save as `backup_before_update/`

---

## 📝 STEP 3: Update Files

### **Quick Method - Just the Footer (5 minutes):**

1. In File Manager, go to: `resources/views/web/layouts/footer.blade.php`
2. Click **"Edit"**
3. Find line 109 (use Ctrl+F to search for "Brmja")
4. **Change this:**
   ```html
   Designed and developed by <a href="https://brmja.tech/" target="_blank" style="color:#02767F;">Brmja Tech</a>
   ```
   **To this:**
   ```html
   Designed and developed by <a href="https://phyzioline.com/" target="_blank" style="color:#02767F;">Phyzioline</a>
   ```
5. Click **"Save"**
6. **Done!** Footer updated ✅

### **Full Method - All Features (15 minutes):**

**Upload these 6 files from your local computer:**

1. **Footer:**
   - Local: `d:\laravel\phyzioline.com\resources\views\web\layouts\footer.blade.php`
   - Server: `resources/views/web/layouts/footer.blade.php`

2. **Homepage:**
   - Local: `d:\laravel\phyzioline.com\resources\views\web\pages\index.blade.php`
   - Server: `resources/views/web/pages/index.blade.php`

3. **Shop Page:**
   - Local: `d:\laravel\phyzioline.com\resources\views\web\pages\show.blade.php`
   - Server: `resources/views/web/pages/show.blade.php`

4. **Details Page:**
   - Local: `d:\laravel\phyzioline.com\resources\views\web\pages\showDetails.blade.php`
   - Server: `resources/views/web/pages/showDetails.blade.php`

5. **Controller:**
   - Local: `d:\laravel\phyzioline.com\app\Http\Controllers\Web\HomeController.php`
   - Server: `app/Http/Controllers/Web/HomeController.php`

6. **Model:**
   - Local: `d:\laravel\phyzioline.com\app\Models\Product.php`
   - Server: `app/Models/Product.php`

**How to Upload:**
- In CloudPanel File Manager, navigate to folder
- Click **"Upload"** button
- Select file from your computer
- Confirm replace if asked

---

## 🧹 STEP 4: Clear Cache

**Option A: Via CloudPanel Terminal (If available):**

1. Click **"Terminal"** or **"SSH Terminal"** in CloudPanel
2. Run these commands:
   ```bash
   cd /home/phyziolinegit/htdocs/phyzioline.com
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

**Option B: Via PHP Script (If terminal not available):**

1. Create file: `clearcache.php` in your Laravel root
2. Content:
   ```php
   <?php
   exec('cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan config:clear');
   exec('cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan cache:clear');
   exec('cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan view:clear');
   echo "Cache cleared!";
   ?>
   ```
3. Visit: `https://phyzioline.com/clearcache.php`
4. Delete the file after use!

**Option C: Manual Cache Clear:**

1. In File Manager, navigate to: `bootstrap/cache/`
2. Delete these files:
   - `config.php`
   - `routes-v7.php`
   - `services.php`
3. Navigate to: `storage/framework/cache/`
4. Delete all files in this folder
5. Navigate to: `storage/framework/views/`
6. Delete all files in this folder

---

## ✅ STEP 5: Test Your Website

Visit: **https://phyzioline.com**

**Check:**
- [ ] Footer says "Phyzioline" (not "Brmja Tech")
- [ ] If you did full update:
  - [ ] Search bar is visible and large
  - [ ] Category dropdown appears
  - [ ] Prices show "EGP"
  - [ ] "اطلب الآن" buttons appear

**If something is wrong:**
1. Go back to CloudPanel File Manager
2. Re-upload your backup files
3. Clear cache again

---

## ⚡ FASTEST METHOD (Footer Only)

**Just want to update the footer? Do this:**

1. Login: https://147.93.85.27:8443
2. Files → File Manager
3. Navigate to: `resources/views/web/layouts/footer.blade.php`
4. Click "Edit"
5. Line 109: Change "Brmja Tech" to "Phyzioline"
6. Change link from "https://brmja.tech/" to "https://phyzioline.com/"
7. Save
8. Clear cache (see Step 4, Option C)
9. Check website!

**Total time: 3 minutes** ⚡

---

## 🆘 IF SOMETHING GOES WRONG

**Don't Panic!**

1. Go to CloudPanel File Manager
2. Navigate to the file that's causing issues
3. Click "Edit"
4. Copy content from your backup folder
5. Paste and Save
6. Or just re-upload the old file from backup

**Still broken?**
- Re-upload ALL files from backup
- Clear cache again
- Contact CloudPanel support if needed

---

## 📞 NEED HELP?

**CloudPanel Docs:** https://www.cloudpanel.io/docs/v2/  
**File Manager:** https://www.cloudpanel.io/docs/v2/frontend-area/file-manager/

---

## 🎯 RECOMMENDED FOR YOU

Since SSH is unstable, **use CloudPanel File Manager** for everything!

**Deployment Steps:**
1. ✅ Login to CloudPanel
2. ✅ Backup files
3. ✅ Edit footer.blade.php (line 109)
4. ✅ Clear cache (Option C - manual)
5. ✅ Check website

**Total Time:** 5 minutes  
**Risk:** Very Low  
**Success Rate:** 99%

---

**Created:** December 2, 2025  
**Method:** CloudPanel File Manager (SSH-free)  
**Perfect for:** Unstable SSH connections ✅
