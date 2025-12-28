# Deployment Checklist - Migrations & Cache

**Date:** December 29, 2025  
**Status:** ⚠️ **REQUIRED ACTIONS**

---

## 🔄 **Migrations Required**

The following migrations need to be run for the specialty-based system to work:

### **Specialty System Migrations:**
1. ✅ `2025_12_29_000001_add_specialty_fields_to_clinics_table.php`
   - Adds `primary_specialty`, `specialty_selected`, `specialty_selected_at` to clinics table

2. ✅ `2025_12_29_000002_create_clinic_specialties_table.php`
   - Creates `clinic_specialties` table for multi-specialty support

3. ✅ `2025_12_29_000003_enhance_clinic_appointments_with_specialty_fields.php`
   - Adds specialty fields to `clinic_appointments` table

4. ✅ `2025_12_29_000004_create_reservation_additional_data_table.php`
   - Creates `reservation_additional_data` table for specialty-specific appointment data

5. ✅ `2025_12_29_000005_create_clinic_pricing_configs_table.php`
   - Creates `clinic_pricing_configs` table for pricing configuration

6. ✅ `2025_12_29_000006_create_weekly_programs_table.php`
   - Creates `weekly_programs` table for treatment programs

7. ✅ `2025_12_29_000007_create_program_sessions_table.php`
   - Creates `program_sessions` table for program session tracking

8. ✅ `2025_12_29_000003_update_clinics_require_approval.php`
   - Updates clinics to require approval (sets `is_active` default to false)

---

## 📋 **Commands to Run**

### **1. Run Migrations**
```bash
php artisan migrate
```

**Or if you want to see what will be migrated:**
```bash
php artisan migrate:status
```

### **2. Clear All Caches (Recommended)**
```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache (IMPORTANT - Views were modified)
php artisan view:clear

# Clear compiled classes
php artisan clear-compiled
```

### **3. Optimize (Optional - For Production)**
```bash
# Cache config for better performance
php artisan config:cache

# Cache routes for better performance
php artisan route:cache

# Optimize autoloader
composer dump-autoload -o
```

---

## ⚠️ **Important Notes**

### **View Cache (CRITICAL)**
Since we modified several Blade views:
- **Profile view** - Changed layout from `clinic.layouts.app` to `web.layouts.dashboard_master`
- **Sidebar** - Rearranged menu items
- **Form views** - Added error handling and validation

**You MUST clear view cache:**
```bash
php artisan view:clear
```

### **Route Cache**
If routes are cached, new routes might not be available:
```bash
php artisan route:clear
```

### **Config Cache**
If config is cached, new configuration might not load:
```bash
php artisan config:clear
```

---

## 🧪 **After Deployment - Verification**

### **1. Check Migrations**
```bash
php artisan migrate:status
```
All migrations should show as "Ran".

### **2. Test Forms**
- ✅ Profile form (`/clinic/profile`) - Should load without errors
- ✅ Appointments form (`/clinic/appointments/create`) - Should submit correctly
- ✅ Programs form (`/clinic/programs/create`) - Should submit correctly
- ✅ Course forms (`/instructor/courses/create`) - Should work with Next buttons

### **3. Test Sidebar**
- ✅ Sidebar should show items in logical order
- ✅ Profile & Settings should be visible
- ✅ All menu items should work

### **4. Test Specialty Selection**
- ✅ Dashboard should show specialty selection modal if not selected
- ✅ Specialty selection should save correctly

---

## 🚨 **If Issues Occur**

### **Migration Errors:**
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback all migrations (DANGEROUS - only if needed)
php artisan migrate:reset
```

### **Cache Issues:**
```bash
# Clear everything
php artisan optimize:clear

# Or individually
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### **Permission Issues:**
```bash
# Ensure storage is writable
chmod -R 775 storage bootstrap/cache
```

---

## ✅ **Quick Deployment Script**

For convenience, you can run all cache clearing commands at once:

```bash
php artisan cache:clear && \
php artisan config:clear && \
php artisan route:clear && \
php artisan view:clear && \
php artisan optimize:clear
```

---

## 📝 **Summary**

**Required Actions:**
1. ✅ Run migrations: `php artisan migrate`
2. ✅ Clear view cache: `php artisan view:clear` (CRITICAL)
3. ✅ Clear route cache: `php artisan route:clear`
4. ✅ Clear config cache: `php artisan config:clear`
5. ✅ Clear application cache: `php artisan cache:clear`

**Optional (Production):**
- Cache config: `php artisan config:cache`
- Cache routes: `php artisan route:cache`

**No Database Seeding Required** - All data is created dynamically.

---

**Status:** Ready for deployment after running migrations and clearing caches.

