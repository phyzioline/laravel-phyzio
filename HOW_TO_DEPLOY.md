# 🚀 Easy Deployment Instructions

## You Have 3 Options:

---

## ⚡ OPTION 1: Automated Script (Recommended)

### **For Footer Only (Safest - 2 minutes):**

1. Open PowerShell **as Administrator**
2. Navigate to project:
   ```powershell
   cd d:\laravel\phyzioline.com
   ```
3. Run script:
   ```powershell
   .\deploy_footer_only.ps1
   ```
4. Enter your password when prompted (2 times)
5. Check your website!

### **For All Features (Full Update - 5 minutes):**

1. Open PowerShell **as Administrator**
2. Navigate to project:
   ```powershell
   cd d:\laravel\phyzioline.com
   ```
3. Run script:
   ```powershell
   .\deploy.ps1
   ```
4. Enter your password when prompted (multiple times)
5. Check your website!

---

## 🖱️ OPTION 2: WinSCP (GUI - Easiest)

### **Download WinSCP:**
https://winscp.net/eng/download.php

### **Steps:**

1. **Install and Open WinSCP**

2. **Connect to Server:**
   - **File protocol:** SFTP
   - **Host name:** 147.93.85.27
   - **Port:** 22
   - **User name:** phyziolinegit
   - **Password:** [Your password]
   - Click **Login**

3. **Upload Files:**
   - **Left panel:** Navigate to `d:\laravel\phyzioline.com`
   - **Right panel:** Navigate to `/home/phyziolinegit/htdocs/phyzioline.com`
   
   **Drag and drop these files from left to right:**
   - `resources/views/web/layouts/footer.blade.php` ← MUST UPLOAD
   - `resources/views/web/pages/index.blade.php`
   - `resources/views/web/pages/show.blade.php`
   - `resources/views/web/pages/showDetails.blade.php`
   - `app/Http/Controllers/Web/HomeController.php`
   - `app/Models/Product.php`

4. **Clear Cache:**
   - In WinSCP, click **Commands** → **Open Terminal**
   - Type:
     ```bash
     cd /home/phyziolinegit/htdocs/phyzioline.com
     php artisan config:clear
     php artisan cache:clear
     php artisan view:clear
     ```
   - Press Enter

5. **Done!** Check your website

---

## 🌐 OPTION 3: CloudPanel File Manager (No Downloads Needed)

### **Steps:**

1. **Login:** https://147.93.85.27:8443

2. **Navigate:** Files → File Manager

3. **Upload Files:**
   - Click on each folder to navigate
   - Click **Upload** button
   - Select files from `d:\laravel\phyzioline.com`
   
   **Files to upload:**
   - To `resources/views/web/layouts/`: → `footer.blade.php`
   - To `resources/views/web/pages/`: → `index.blade.php`, `show.blade.php`, `showDetails.blade.php`
   - To `app/Http/Controllers/Web/`: → `HomeController.php`
   - To `app/Models/`: → `Product.php`

4. **Clear Cache (Manual):**
   - Delete files in: `bootstrap/cache/`
   - Delete files in: `storage/framework/views/`
   - Delete files in: `storage/framework/cache/`

5. **Done!** Check your website

---

## 🎯 My Recommendation for You:

**Try Option 1 first (Automated Script):**
- Fastest
- Least error-prone
- Handles everything automatically

**If SSH issues persist, use Option 2 (WinSCP):**
- GUI interface
- Easy to use
- Reliable

**If both fail, use Option 3 (CloudPanel):**
- Always works
- No SSH needed
- Just slower

---

## ✅ After Deployment - Check These:

Visit: **https://phyzioline.com**

- [ ] Footer says "Phyzioline" (not "Brmja Tech")
- [ ] Search bar is large and visible
- [ ] Category dropdown appears
- [ ] All prices show "EGP"
- [ ] "اطلب الآن" buttons visible
- [ ] Products display correctly

---

## 🆘 If Something Goes Wrong:

**Quick Rollback via CloudPanel:**
1. Login: https://147.93.85.27:8443
2. Files → File Manager
3. Edit `footer.blade.php` and change back to "Brmja Tech"
4. Or upload your backup files

---

## 📞 Need Help?

All detailed instructions are in:
- `CLOUDPANEL_DEPLOYMENT.md`
- `DEPLOYMENT_GUIDE.md`

---

**Ready to deploy? Choose your option above and follow the steps!** 🚀
