# 📍 Where Everything Is Located - Complete Guide

**Date:** December 29, 2025  
**System:** Phyzioline - Modular Physical Therapy Clinic Management

---

## 🌐 **URL Routes (How to Access)**

### **1. Specialty Selection**
- **URL:** `/clinic/specialty-selection`
- **Route Name:** `clinic.specialty-selection.show`
- **When:** Automatically redirects on first login if specialty not selected
- **Access:** First-time clinic users

### **2. Dashboard**
- **URL:** `/clinic/dashboard`
- **Route Name:** `clinic.dashboard`
- **Features:** 
  - Checks for specialty selection
  - Redirects to specialty selection if needed
  - Shows clinic-specific dashboard

### **3. Weekly Programs**
- **List:** `/clinic/programs`
- **Create:** `/clinic/programs/create`
- **Show:** `/clinic/programs/{id}`
- **Activate:** `POST /clinic/programs/{id}/activate`
- **Calculate Price:** `POST /clinic/programs/calculate-price`
- **Route Names:** 
  - `clinic.programs.index`
  - `clinic.programs.create`
  - `clinic.programs.show`
  - `clinic.programs.activate`
  - `clinic.programs.calculatePrice`

### **4. Enhanced Appointments**
- **List:** `/clinic/appointments`
- **Create:** `/clinic/appointments/create`
- **Show:** `/clinic/appointments/{id}`
- **Specialty Fields:** `GET /clinic/appointments/specialty-fields?specialty={specialty}`
- **Calculate Price:** `POST /clinic/appointments/calculate-price`
- **Route Names:**
  - `clinic.appointments.index`
  - `clinic.appointments.create`
  - `clinic.appointments.show`
  - `clinic.appointments.specialtyFields`
  - `clinic.appointments.calculatePrice`

---

## 📁 **File Locations**

### **Controllers** (`app/Http/Controllers/Clinic/`)
```
✅ SpecialtySelectionController.php      - Specialty selection handling
✅ WeeklyProgramController.php            - Program management
✅ AppointmentController.php               - Enhanced appointments (updated)
✅ DashboardController.php                 - Dashboard with specialty check (updated)
```

### **Models** (`app/Models/`)
```
✅ ClinicSpecialty.php                     - Clinic-specialty relationships
✅ ReservationAdditionalData.php          - Specialty-specific appointment data
✅ PricingConfig.php                       - Pricing configuration
✅ WeeklyProgram.php                       - Treatment programs
✅ ProgramSession.php                      - Program sessions
✅ Clinic.php                              - Updated with specialty methods
✅ ClinicAppointment.php                   - Updated with specialty fields
```

### **Services** (`app/Services/Clinic/`)
```
✅ SpecialtySelectionService.php           - Specialty selection logic
✅ PaymentCalculatorService.php            - Price calculation
✅ WeeklyProgramService.php                - Program creation & auto-booking
✅ SpecialtyReservationFieldsService.php   - Specialty field definitions
✅ SpecialtyModuleActivationService.php    - Feature visibility control
✅ SpecialtyContextService.php             - Enhanced with all 9 specialties
```

### **Views** (`resources/views/web/clinic/`)
```
✅ specialty-selection.blade.php           - Specialty selection UI
✅ programs/
   ✅ index.blade.php                      - Program list
   ✅ create.blade.php                    - Program creation form
   ✅ show.blade.php                       - Program details
✅ appointments/
   ✅ index.blade.php                      - Appointment calendar
   ✅ create.blade.php                     - Enhanced appointment form (NEW)
```

### **Migrations** (`database/migrations/`)
```
✅ 2025_12_29_000001_add_specialty_fields_to_clinics_table.php
✅ 2025_12_29_000002_create_clinic_specialties_table.php
✅ 2025_12_29_000003_enhance_clinic_appointments_with_specialty_fields.php
✅ 2025_12_29_000004_create_reservation_additional_data_table.php
✅ 2025_12_29_000005_create_clinic_pricing_configs_table.php
✅ 2025_12_29_000006_create_weekly_programs_table.php
✅ 2025_12_29_000007_create_program_sessions_table.php
```

### **Routes** (`routes/web.php`)
```php
// Lines 286-322 contain all clinic routes
Route::group(['prefix' => 'clinic', 'as' => 'clinic.', 'middleware' => ['auth']], function () {
    // Specialty Selection
    Route::get('/specialty-selection', ...);
    Route::post('/specialty-selection', ...);
    
    // Dashboard
    Route::get('/dashboard', ...);
    
    // Programs
    Route::get('/programs/calculate-price', ...);
    Route::post('/programs/{id}/activate', ...);
    Route::resource('programs', ...);
    
    // Appointments
    Route::get('/appointments/specialty-fields', ...);
    Route::post('/appointments/calculate-price', ...);
    Route::resource('appointments', ...);
});
```

---

## 🗄️ **Database Tables**

### **New Tables:**
```
✅ clinic_specialties                      - Clinic-specialty relationships
✅ reservation_additional_data             - Specialty-specific appointment data
✅ clinic_pricing_configs                  - Pricing configuration
✅ weekly_programs                         - Treatment programs
✅ program_sessions                        - Program session tracking
```

### **Updated Tables:**
```
✅ clinics                                 - Added: primary_specialty, specialty_selected, specialty_selected_at
✅ clinic_appointments                     - Added: visit_type, location, payment_method, specialty, session_type
```

---

## 🎯 **How to Access Features**

### **For Clinic Users:**

1. **First Login:**
   - Go to: `/clinic/dashboard`
   - Automatically redirected to: `/clinic/specialty-selection`
   - Select specialty → Continue to dashboard

2. **Create Weekly Program:**
   - Navigate to: `/clinic/programs/create`
   - Or click "Create New Program" from: `/clinic/programs`

3. **View Programs:**
   - Navigate to: `/clinic/programs`
   - Click on any program to view details: `/clinic/programs/{id}`

4. **Create Appointment:**
   - Navigate to: `/clinic/appointments/create`
   - Select specialty → Dynamic fields appear
   - Price preview updates in real-time

5. **View Appointments:**
   - Navigate to: `/clinic/appointments`
   - Calendar view with all appointments

---

## 🔗 **Quick Links (Add to Sidebar/Menu)**

You can add these links to your clinic dashboard sidebar:

```html
<!-- Specialty Selection (if not selected) -->
@if(!$clinic->hasSelectedSpecialty())
    <a href="{{ route('clinic.specialty-selection.show') }}">Select Specialty</a>
@endif

<!-- Weekly Programs -->
<a href="{{ route('clinic.programs.index') }}">Treatment Programs</a>
<a href="{{ route('clinic.programs.create') }}">Create Program</a>

<!-- Enhanced Appointments -->
<a href="{{ route('clinic.appointments.index') }}">Appointments</a>
<a href="{{ route('clinic.appointments.create') }}">New Appointment</a>
```

---

## 📊 **API Endpoints (AJAX)**

### **Specialty Fields:**
```
GET /clinic/appointments/specialty-fields?specialty={specialty}
Response: { success: true, fields: {...} }
```

### **Price Calculation:**
```
POST /clinic/appointments/calculate-price
Body: { specialty, visit_type, location, duration_minutes, equipment }
Response: { success: true, pricing: {...} }

POST /clinic/programs/calculate-price
Body: { specialty, sessions_per_week, total_weeks, duration_minutes, ... }
Response: { success: true, pricing: {...} }
```

---

## 🎨 **View Files Structure**

```
resources/views/web/clinic/
├── specialty-selection.blade.php          ← Specialty selection popup
├── dashboard.blade.php                     ← Main dashboard
├── programs/
│   ├── index.blade.php                    ← Program list with filters
│   ├── create.blade.php                   ← Program creation form
│   └── show.blade.php                     ← Program details & sessions
└── appointments/
    ├── index.blade.php                    ← Appointment calendar
    └── create.blade.php                   ← Enhanced appointment form
```

---

## 🔧 **Service Methods**

### **SpecialtySelectionService:**
```php
selectSpecialty($clinic, $specialties, $primarySpecialty)
addSpecialty($clinic, $specialty)
removeSpecialty($clinic, $specialty)
needsSpecialtySelection($clinic)
```

### **PaymentCalculatorService:**
```php
calculateSessionPrice($clinic, $params)
calculateProgramPrice($clinic, $params)
calculateAppointmentPrice($appointment)
```

### **WeeklyProgramService:**
```php
createProgram($data)
generateProgramSessions($program, $sessionPrice)
autoBookSessions($program)
activateProgram($program)
```

### **SpecialtyReservationFieldsService:**
```php
getFieldsSchema($specialty)                 // Returns field definitions
validateSpecialtyData($specialty, $data)   // Validates data
```

### **SpecialtyModuleActivationService:**
```php
isFeatureVisible($clinic, $feature)
getWorkflow($specialty)
getHiddenFeatures($specialty)
```

---

## 📝 **Model Relationships**

### **Clinic Model:**
```php
$clinic->specialties()                      // Has many
$clinic->primarySpecialty()                 // Has one (primary)
$clinic->activeSpecialties()                // Has many (active only)
$clinic->hasSelectedSpecialty()             // Boolean check
$clinic->hasSpecialty($specialty)          // Boolean check
$clinic->isMultiSpecialty()                // Boolean check
```

### **WeeklyProgram Model:**
```php
$program->sessions()                        // Has many
$program->patient()                         // Belongs to
$program->therapist()                       // Belongs to
$program->clinic()                          // Belongs to
$program->getCompletedSessionsCount()      // Method
$program->getRemainingSessionsCount()      // Method
$program->getCompletionPercentage()        // Method
```

### **ClinicAppointment Model:**
```php
$appointment->additionalData()              // Has one
$appointment->hasAdditionalData()           // Boolean check
$appointment->getAdditionalDataField($key)  // Method
```

---

## 🚀 **Quick Start Guide**

### **1. Run Migrations:**
```bash
php artisan migrate
```

### **2. Access Dashboard:**
```
http://your-domain.com/clinic/dashboard
```

### **3. Select Specialty (First Time):**
```
http://your-domain.com/clinic/specialty-selection
```

### **4. Create Program:**
```
http://your-domain.com/clinic/programs/create
```

### **5. Create Appointment:**
```
http://your-domain.com/clinic/appointments/create
```

---

## 📋 **Checklist: Where to Find Everything**

- ✅ **Specialty Selection:** `/clinic/specialty-selection`
- ✅ **Program List:** `/clinic/programs`
- ✅ **Create Program:** `/clinic/programs/create`
- ✅ **View Program:** `/clinic/programs/{id}`
- ✅ **Appointment Calendar:** `/clinic/appointments`
- ✅ **Create Appointment:** `/clinic/appointments/create`
- ✅ **Controllers:** `app/Http/Controllers/Clinic/`
- ✅ **Models:** `app/Models/`
- ✅ **Services:** `app/Services/Clinic/`
- ✅ **Views:** `resources/views/web/clinic/`
- ✅ **Migrations:** `database/migrations/`
- ✅ **Routes:** `routes/web.php` (lines 286-322)

---

## 🎯 **Summary**

**Everything is located in:**
- **Backend:** `app/` directory
- **Frontend:** `resources/views/web/clinic/` directory
- **Database:** `database/migrations/` directory
- **Routes:** `routes/web.php` file
- **URLs:** All under `/clinic/*` prefix

**All features are accessible through:**
- Direct URLs (listed above)
- Route names (use `route('clinic.xxx')`)
- Sidebar navigation (add links as needed)

---

**Document Version:** 1.0  
**Last Updated:** December 29, 2025

