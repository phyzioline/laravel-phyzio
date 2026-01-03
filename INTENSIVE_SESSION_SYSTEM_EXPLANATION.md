# Intensive Session Booking System - Complete Explanation

## 📋 Overview

This is the **first system in Egypt** to support comprehensive intensive pediatric therapy sessions with multi-doctor slot assignments and automatic payroll calculation.

---

## 🎯 How It Works

### **1. Booking Type Selection**

When creating an appointment, you now have **2 options**:

#### **Regular Session** (Traditional)
- One doctor for the entire session
- Standard appointment booking
- Works like before

#### **Intensive Session** (New - Children's Intensive Treatment)
- **1-4 hours** total duration
- **Automatically divided into hourly slots** (1 hour = 1 slot)
- **Each slot can have a different doctor**
- Perfect for pediatric clinics

---

### **2. Intensive Session Flow**

```
Step 1: Select "Intensive Session" when booking
   ↓
Step 2: Choose duration (1, 2, 3, or 4 hours)
   ↓
Step 3: System automatically creates slots
   Example: 3 hours = 3 slots (10:00-11:00, 11:00-12:00, 12:00-13:00)
   ↓
Step 4: Assign doctors to each slot
   - Drag & drop interface
   - Automatic availability checking
   - Conflict detection
   ↓
Step 5: System automatically creates work logs for payroll
```

---

### **3. Slot Assignment Engine**

**Features:**
- ✅ **Visual Timeline** - See all slots at once
- ✅ **Doctor Selection** - Click slot to assign doctor
- ✅ **Availability Checking** - Only shows available doctors
- ✅ **Conflict Detection** - Prevents double-booking
- ✅ **Hour Limits** - Prevents exceeding daily/weekly limits
- ✅ **Specialty Matching** - Only shows doctors with matching specialty

**Example:**
```
Slot 1: 10:00 - 11:00 → Dr. Ahmed (Pediatric Specialist)
Slot 2: 11:00 - 12:00 → Dr. Mohamed (Pediatric Specialist)  
Slot 3: 12:00 - 13:00 → Dr. Sarah (Pediatric Specialist)
```

---

### **4. Doctor Hour Tracking (Payroll Ready)**

**Automatic Calculation:**
- Each slot assignment creates a **work log entry**
- Tracks: hours worked, hourly rate, total earnings
- Ready for accounting export

**Example:**
```
Dr. Mohamed:
- Worked 2 slots (2 hours)
- Hourly Rate: 150 EGP
- Total: 300 EGP
```

**Reports Available:**
- Daily hours report
- Weekly hours report  
- Monthly hours report
- Export to Excel/PDF

---

### **5. Doctor Dashboard (Hourly View)**

Doctors see their schedule **by hour**, not by session:

```
10:00 AM - 11:00 AM
Patient: Ahmed Ali
Session Type: Intensive (Slot 1 of 3)

11:00 AM - 12:00 PM
Patient: Sara Mohamed
Session Type: Regular
```

**Shows:**
- ✅ Child's name
- ✅ Slot time
- ✅ Session type (Regular/Intensive)
- ✅ Today's hours & earnings
- ✅ Weekly summary

---

## 📁 Where to Find Everything

### **Latest Updates (Git Commits)**

All updates are in the main branch:
```bash
git pull origin main
```

**Recent Commits:**
1. `feat: Implement intensive session booking system` - Core system
2. `feat: Add doctor hourly schedule dashboard` - Doctor view
3. `fix: Handle existing treatment_plans table` - Migration fixes

### **Key Files**

#### **Database Migrations:**
- `database/migrations/2026_01_16_000001_add_booking_type_to_clinic_appointments.php`
- `database/migrations/2026_01_16_000002_create_treatment_plans_table.php`
- `database/migrations/2026_01_16_000003_create_booking_slots_table.php`
- `database/migrations/2026_01_16_000004_create_slot_doctor_assignments_table.php`
- `database/migrations/2026_01_16_000005_create_doctor_hourly_rates_table.php`
- `database/migrations/2026_01_16_000006_create_doctor_work_logs_table.php`

#### **Models:**
- `app/Models/BookingSlot.php` - Time slots
- `app/Models/SlotDoctorAssignment.php` - Doctor-slot assignments
- `app/Models/DoctorHourlyRate.php` - Doctor rates
- `app/Models/DoctorWorkLog.php` - Payroll logs
- `app/Models/TreatmentPlan.php` - Treatment plans

#### **Controllers:**
- `app/Http/Controllers/Clinic/AppointmentController.php` - Booking & slot assignment
- `app/Http/Controllers/Clinic/DoctorScheduleController.php` - Doctor hourly view
- `app/Http/Controllers/Clinic/DoctorHoursReportController.php` - Reports

#### **Services:**
- `app/Services/Clinic/IntensiveSessionService.php` - Core logic

#### **Views:**
- `resources/views/web/clinic/appointments/create.blade.php` - Booking form
- `resources/views/web/clinic/appointments/assign-slots.blade.php` - Slot assignment
- `resources/views/web/clinic/doctor-schedule/index.blade.php` - Doctor dashboard
- `resources/views/web/clinic/reports/doctor-hours.blade.php` - Reports

---

## ⚠️ What's Missing / Needs Setup

### **1. Doctor Hourly Rates** ⚠️

**Status:** System ready, but needs data entry

**Action Required:**
- Go to clinic settings
- Set hourly rate for each doctor
- Set max hours per day/week

**Location:** Will be added to clinic settings (coming next)

### **2. Service Pricing (Ultrasound, Laser, etc.)** ⚠️

**Status:** Partially implemented, needs UI

**Current State:**
- PricingConfig model has `equipment_pricing` JSON field
- PaymentCalculatorService includes equipment in calculations
- **Missing:** UI for clinics to manage services

**Action Required:** (Will implement next)
- Create service management page
- Allow clinics to add/edit services (ultrasound, laser, etc.)
- Set prices per service
- Services appear in pricing preview

---

## 💰 Pricing System (Current)

### **How Pricing Works:**

```
Total Price = Base Price 
            + Specialty Adjustment 
            + Therapist Level 
            + Equipment/Services (ultrasound, laser, etc.)
            + Location Factor 
            + Duration Factor 
            - Discount
```

### **Equipment/Services Currently Supported:**

The system supports equipment pricing, but clinics need a UI to:
1. **Add services** (ultrasound, laser, shockwave, etc.)
2. **Set prices** for each service
3. **See in pricing preview** when booking

**This is what we'll add next!**

---

## 🚀 Next Steps

1. ✅ **Run Migrations:** `php artisan migrate`
2. ⏳ **Set Doctor Hourly Rates** (UI coming)
3. ⏳ **Add Service Pricing** (UI coming - this is what you asked for!)
4. ✅ **Test Booking** - Create intensive session
5. ✅ **Assign Doctors** - Use slot assignment page
6. ✅ **View Reports** - Check doctor hours

---

## 📞 Support

All code is in:
- **Git Repository:** `https://github.com/phyzioline/laravel-phyzio`
- **Branch:** `main`
- **Latest Commit:** Check with `git log`

---

## 🎉 Key Features

✅ First system in Egypt with comprehensive intensive sessions  
✅ Multi-doctor slot assignments  
✅ Automatic payroll calculation  
✅ Hour-based scheduling (not session-based)  
✅ Conflict detection & safety rules  
✅ Ready for clinic chains  

