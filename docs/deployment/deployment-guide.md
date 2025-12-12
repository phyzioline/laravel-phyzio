# 🚀 SAFE SERVER DEPLOYMENT GUIDE
## Phyzioline E-Commerce Updates

**Server IP:** 147.93.85.27  
**SSH User:** phyziolinegit  
**Control Panel:** CloudPanel (https://147.93.85.27:8443)

---

## ⚠️ IMPORTANT - READ FIRST

Before deploying, understand what we're uploading:

### **Changes Being Deployed:**
1. ✅ Footer: "Brmja Tech" → "Phyzioline"
2. ✅ Hero sections reduced (70vh → 35vh/30vh)
3. ✅ New prominent search bar
4. ✅ Category dropdown menu
5. ✅ "Order Now" (اطلب الآن) button on products
6. ✅ All prices now show "EGP"
7. ✅ Products sorted by best selling
8. ✅ 50 products per page
9. ✅ Enhanced product images

### **Files That Will Be Updated:**
```
resources/views/web/
├── layouts/footer.blade.php          ← Branding change
├── pages/index.blade.php             ← Major updates
├── pages/show.blade.php               ← Minor updates
└── pages/showDetails.blade.php        ← Minor updates

app/Http/Controllers/Web/
└── HomeController.php                 ← Sorting & pagination

app/Models/
└── Product.php                        ← Relationship added
```

---

## 📋 PRE-DEPLOYMENT CHECKLIST

Before touching the server:

- [ ] **BACKUP CURRENT FILES** (Most Important!)
- [ ] Test all features locally
- [ ] Review all changes
- [ ] Have rollback plan ready
- [ ] Know your database credentials
- [ ] Have CloudPanel login ready

---

## 🔒 DEPLOYMENT METHOD 1: CloudPanel File Manager (SAFEST)

### **Step 1: Backup Current Files**

1. **Login to CloudPanel:**
   ```
   URL: https://147.93.85.27:8443
   Use your CloudPanel credentials
   ```

2. **Create Backup:**
   - Navigate to: Files → File Manager
   - Go to your Laravel root directory
   - **Right-click** on `resources` folder → **Download**
   - **Right-click** on `app` folder → **Download**
   - Save to your computer (e.g., `backup_2025-12-02/`)

### **Step 2: Upload Modified Files**

Upload these files one by one through File Manager:

#### **File 1: Footer Branding**
- **Path:** `resources/views/web/layouts/footer.blade.php`
- **Line 109:** Change from "Brmja Tech" to "Phyzioline"
- **Action:** 
  1. Navigate to file in File Manager
  2. Click "Edit"
  3. Find line 109
  4. Replace: `Brmja Tech` with `Phyzioline`
  5. Replace: `https://brmja.tech/` with `https://phyzioline.com/`
  6. Click "Save"

#### **File 2: HomePage (IMPORTANT)**
- **Path:** `resources/views/web/pages/index.blade.php`
- **Action:** Upload the entire file from your local copy
  1. Download from local: `d:\laravel\phyzioline.com\resources\views\web\pages\index.blade.php`
  2. Upload to server in same location
  3. **OR** Copy-paste content via "Edit" in File Manager

#### **File 3: Shop Page**
- **Path:** `resources/views/web/pages/show.blade.php`
- **Changes:** Currency + hero height
- **Action:** Upload or edit manually

#### **File 4: Product Details**
- **Path:** `resources/views/web/pages/showDetails.blade.php`
- **Changes:** Currency + hero height
- **Action:** Upload or edit manually

#### **File 5: Controller**
- **Path:** `app/Http/Controllers/Web/HomeController.php`
- **Changes:** Pagination + sorting
- **Action:** Upload or copy-paste content

#### **File 6: Product Model**
- **Path:** `app/Models/Product.php`
- **Changes:** Added orderItems relationship
- **Action:** Upload or copy-paste content

### **Step 3: Clear Cache**

Via CloudPanel Terminal or SSH:

```bash
cd /home/phyziolinegit/htdocs/phyzioline.com  # Adjust path as needed
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **Step 4: Test**

Visit your website and verify:
- Footer shows "Phyzioline"
- Search bar is visible
- Category dropdown works
- Prices show "EGP"
- "Order Now" buttons appear

---

## 🔒 DEPLOYMENT METHOD 2: Git (RECOMMENDED IF GIT IS SET UP)

### **Step 1: Check if Git Repository Exists on Server**

```bash
ssh phyziolinegit@147.93.85.27
cd /home/phyziolinegit/htdocs/phyzioline.com  # Or your path
git status
```

If git exists, continue. If not, use Method 1 or 3.

### **Step 2: Push from Local to Remote**

On your local machine:

```bash
cd d:\laravel\phyzioline.com

# Add all changes (already done)
git add .

# Commit (already done)
git commit -m "All e-commerce enhancements + footer update"

# Add remote if not exists
git remote add production ssh://phyziolinegit@147.93.85.27/home/phyziolinegit/repo/phyzioline.git

# Push
git push production main
```

### **Step 3: Pull on Server**

```bash
ssh phyziolinegit@147.93.85.27
cd /home/phyziolinegit/htdocs/phyzioline.com
git pull origin main

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🔒 DEPLOYMENT METHOD 3: FTP/SFTP (ALTERNATIVE)

### **Using FileZilla or WinSCP:**

**Connection Details:**
- **Protocol:** SFTP
- **Host:** 147.93.85.27
- **Port:** 22
- **Username:** phyziolinegit
- **Password:** [Your SSH password]

**Upload These Files:**
1. `resources/views/web/layouts/footer.blade.php`
2. `resources/views/web/pages/index.blade.php`
3. `resources/views/web/pages/show.blade.php`
4. `resources/views/web/pages/showDetails.blade.php`
5. `app/Http/Controllers/Web/HomeController.php`
6. `app/Models/Product.php`

**After Upload:**
SSH into server and clear cache (see Step 3 in Method 1)

---

## 🔧 TROUBLESHOOTING SSH CONNECTION

Your SSH keeps disconnecting. Here's why and how to fix:

### **Issue:** `Connection closed by 147.93.85.27 port 22`

**Possible Causes:**
1. Session timeout
2. Firewall rules
3. Idle timeout settings

**Solutions:**

#### **Solution 1: Keep Connection Alive**

Create/edit file on your local PC:
```
File: C:\Users\[YourUsername]\.ssh\config
```

Add:
```
Host phyzioline
    HostName 147.93.85.27
    User phyziolinegit
    ServerAliveInterval 60
    ServerAliveCountMax 3
```

Then connect with:
```bash
ssh phyzioline
```

#### **Solution 2: Use Screen/Tmux**

Once connected:
```bash
ssh phyziolinegit@147.93.85.27

# Start screen session
screen -S deployment

# If disconnected, reconnect and resume:
ssh phyziolinegit@147.93.85.27
screen -r deployment
```

#### **Solution 3: Execute Commands Quickly**

Prepare commands in a file first, then paste quickly:
```bash
ssh phyziolinegit@147.93.85.27
# Paste all these at once:
cd /home/phyziolinegit/htdocs/phyzioline.com && php artisan config:clear && php artisan cache:clear && php artisan view:clear && exit
```

---

## 📁 FINDING YOUR LARAVEL DIRECTORY ON SERVER

Common paths in CloudPanel:
```bash
/home/phyziolinegit/htdocs/phyzioline.com
/home/phyziolinegit/htdocs/www.phyzioline.com
/home/phyziolinegit/public_html/
/var/www/phyzioline.com
```

**To find it:**
```bash
ssh phyziolinegit@147.93.85.27
ls -la
cd htdocs
ls -la
```

---

## ✅ POST-DEPLOYMENT VERIFICATION

After deployment, test these:

### **1. Visual Check:**
- [ ] Footer says "Phyzioline"
- [ ] Search bar is large and visible
- [ ] Category dropdown appears
- [ ] "اطلب الآن" buttons visible
- [ ] Prices show "EGP"

### **2. Functionality Check:**
- [ ] Search actually works
- [ ] Category dropdown filters products
- [ ] "Order Now" adds to cart
- [ ] "Order Now" redirects to cart
- [ ] All existing features still work

### **3. Performance Check:**
- [ ] Page loads quickly
- [ ] No errors in browser console (F12)
- [ ] Images load properly
- [ ] Animations are smooth

---

## 🆘 EMERGENCY ROLLBACK PLAN

If something goes wrong:

### **Quick Rollback:**

1. **Via CloudPanel File Manager:**
   - Upload the backup files you downloaded
   - Overwrite the new files

2. **Via SSH:**
   ```bash
   ssh phyziolinegit@147.93.85.27
   cd /home/phyziolinegit/htdocs/phyzioline.com
   git reset --hard HEAD~1  # If using git
   php artisan cache:clear
   ```

3. **Manual Restore:**
   - Re-upload old `footer.blade.php` from backup
   - Re-upload old controller and views
   - Clear cache

---

## 📞 SUPPORT CONTACTS

**CloudPanel Support:** https://www.cloudpanel.io/docs/v2/  
**Laravel Docs:** https://laravel.com/docs  

---

## 🎯 RECOMMENDED DEPLOYMENT STRATEGY

**For Maximum Safety:**

1. **Phase 1 - Footer Only (Low Risk):**
   - Update ONLY footer.blade.php
   - Test
   - If works, proceed

2. **Phase 2 - Visual Updates (Medium Risk):**
   - Upload view files (index, show, showDetails)
   - Clear cache
   - Test
   - If works, proceed

3. **Phase 3 - Backend Updates (Medium Risk):**
   - Upload HomeController.php
   - Upload Product.php model
   - Clear cache
   - Test

---

## 💡 IMPORTANT NOTES

1. **Database:** No database changes required - safe deployment!
2. **Downtime:** Minimal to zero if done correctly
3. **Backup:** ALWAYS backup before touching production
4. **Test First:** If possible, test on staging environment
5. **Peak Hours:** Avoid deployment during peak traffic

---

## 📝 DEPLOYMENT COMMAND SUMMARY

**Quickest Safe Deployment (via SSH):**

```bash
# Connect
ssh phyziolinegit@147.93.85.27

# Navigate to Laravel directory
cd /home/phyziolinegit/htdocs/phyzioline.com  # Adjust path!

# Pull changes (if using git)
git pull origin main

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Exit
exit
```

**If SSH keeps disconnecting, use CloudPanel File Manager instead!**

---

## ✅ FINAL CHECKLIST

Before starting deployment:

- [ ] Read this entire guide
- [ ] Choose deployment method (recommend CloudPanel if SSH unstable)
- [ ] Backup current files
- [ ] Have rollback plan ready
- [ ] Know your server paths
- [ ] Test locally first
- [ ] Schedule during low-traffic time
- [ ] Have CloudPanel access ready

---

**Created:** December 2, 2025  
**Server:** 147.93.85.27 (CloudPanel)  
**Status:** Ready for Deployment ✅  
**Risk Level:** LOW (No database changes)

**Good luck with your deployment! 🚀**
