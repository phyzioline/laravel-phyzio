# 📍 Sidebar Navigation - Where Everything Is Located

**Date:** December 29, 2025  
**For:** Clinic Dashboard Sidebar

---

## 🎯 **Sidebar Menu Order (Top to Bottom)**

### **Current Sidebar Structure:**

```
📋 Clinic Dashboard Sidebar
├── 🏠 Dashboard
├── ⚠️ Select Specialty (if not selected - Orange highlight)
├── 💼 Job System
├── 📝 Clinical Episodes
├── 🔧 Services
├── 👨‍⚕️ Doctors
├── 📋 Treatment Programs ← **NEW!** (Between Doctors & Appointments)
├── 📅 Appointments
├── 👥 Patients
├── 👨‍👩‍👧 Staff
├── 📊 Analytics
├── 💳 Billing
├── 🔔 Notifications
├── ← Back to Website
└── 🚪 Logout
```

---

## 🆕 **New Features Location**

### **1. Treatment Programs** ✅
- **Location in Sidebar:** After "Doctors", Before "Appointments"
- **Icon:** 📋 (clipboard-list)
- **Route:** `/clinic/programs`
- **What it does:**
  - List all weekly treatment programs
  - Create new programs
  - View program details
  - Track program progress

### **2. Enhanced Appointments** ✅
- **Location in Sidebar:** Already exists (after Treatment Programs)
- **Icon:** 📅 (calendar-check)
- **Route:** `/clinic/appointments`
- **What's new:**
  - Specialty-specific fields
  - Dynamic form generation
  - Real-time price calculation
  - Enhanced appointment creation

### **3. Specialty Selection** ⚠️
- **Location in Sidebar:** After Dashboard (if not selected)
- **Icon:** 🩺 (stethoscope)
- **Route:** `/clinic/specialty-selection`
- **When it appears:**
  - Only shows if clinic hasn't selected specialty
  - Orange highlight with "Required" badge
  - Automatically redirects on first login

---

## 📊 **Visual Sidebar Map**

```
┌─────────────────────────────┐
│   Phyzioline Logo           │
├─────────────────────────────┤
│ 🏠 Dashboard                 │ ← Always visible
├─────────────────────────────┤
│ ⚠️ Select Specialty          │ ← Only if not selected
│    (Orange - Required)       │
├─────────────────────────────┤
│ 💼 Job System                │ ← Always visible
├─────────────────────────────┤
│ 📝 Clinical Episodes         │ ← Always visible
├─────────────────────────────┤
│ 🔧 Services                  │ ← Always visible
├─────────────────────────────┤
│ 👨‍⚕️ Doctors                  │ ← Always visible
├─────────────────────────────┤
│ 📋 Treatment Programs        │ ← **NEW!** Always visible
│    (Weekly Programs)         │
├─────────────────────────────┤
│ 📅 Appointments              │ ← Always visible (Enhanced)
├─────────────────────────────┤
│ 👥 Patients                  │ ← Always visible
├─────────────────────────────┤
│ 👨‍👩‍👧 Staff                   │ ← Always visible
├─────────────────────────────┤
│ 📊 Analytics                 │ ← Always visible
├─────────────────────────────┤
│ 💳 Billing                   │ ← Always visible
├─────────────────────────────┤
│ 🔔 Notifications             │ ← Always visible
├─────────────────────────────┤
│ ← Back to Website           │ ← Always visible
├─────────────────────────────┤
│ 🚪 Logout                    │ ← Always visible
└─────────────────────────────┘
```

---

## 🔍 **How to Find New Features**

### **Treatment Programs:**
1. Look in the sidebar (right side of screen)
2. Find "Doctors" menu item
3. **Right below "Doctors"** → You'll see **"Treatment Programs"** 📋
4. Click it to access:
   - Program list
   - Create new program
   - View program details

### **Enhanced Appointments:**
1. Find "Appointments" in sidebar (after Treatment Programs)
2. Click "Appointments" 📅
3. Click "Create New" or go to `/clinic/appointments/create`
4. You'll see:
   - Specialty selection dropdown
   - Dynamic fields based on specialty
   - Real-time price preview

### **Specialty Selection:**
1. If you see orange "Select Specialty" button → Click it
2. Or go directly to: `/clinic/specialty-selection`
3. Select your clinic's specialty
4. System will activate appropriate features

---

## 🚀 **Quick Access URLs**

### **From Sidebar:**
- **Treatment Programs:** Click "Treatment Programs" in sidebar
- **Create Program:** Click "Treatment Programs" → "Create New Program" button
- **Appointments:** Click "Appointments" in sidebar
- **Create Appointment:** Click "Appointments" → "New Appointment" button

### **Direct URLs:**
- Programs List: `https://phyzioline.com/clinic/programs`
- Create Program: `https://phyzioline.com/clinic/programs/create`
- Appointments: `https://phyzioline.com/clinic/appointments`
- Create Appointment: `https://phyzioline.com/clinic/appointments/create`
- Specialty Selection: `https://phyzioline.com/clinic/specialty-selection`

---

## 📝 **Sidebar Code Location**

**File:** `resources/views/web/layouts/dashboard_master.blade.php`

**Lines:** 428-434 (Treatment Programs)
```php
<!-- Weekly Treatment Programs -->
<li>
    <a href="{{ route('clinic.programs.index') }}" class="{{ request()->routeIs('clinic.programs.*') ? 'active' : '' }}">
        <span class="las la-clipboard-list"></span>
        <span>{{ __('Treatment Programs') }}</span>
    </a>
</li>
```

---

## ✅ **If You Don't See It**

### **On Server:**
1. Pull latest code:
   ```bash
   git pull origin main
   ```

2. Clear view cache:
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

3. Refresh browser (Ctrl+F5 or Cmd+Shift+R)

### **Check:**
- Is the sidebar file updated? ✅ Yes (line 428-434)
- Are routes registered? ✅ Yes
- Is view cached? Clear cache and refresh

---

## 🎯 **Summary**

**Treatment Programs** is located:
- ✅ In the sidebar
- ✅ Between "Doctors" and "Appointments"
- ✅ With clipboard icon 📋
- ✅ Label: "Treatment Programs"

**If you don't see it:**
1. Pull latest code on server
2. Clear view cache
3. Hard refresh browser

---

**Document Version:** 1.0  
**Last Updated:** December 29, 2025

