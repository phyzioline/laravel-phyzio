# Company Recruitment System - Situation Analysis
**Date:** December 25, 2025  
**System:** Phyzioline - Physical Therapy Platform

---

## 📋 Executive Summary

The company recruitment system is **partially implemented** but has significant gaps. Companies can register, but they lack a dedicated dashboard and proper job management interface. The system currently uses clinic job controllers and views, creating confusion and missing company-specific features.

---

## ✅ What Currently Exists

### 1. **Company Registration**
- ✅ Registration form at `/register/company`
- ✅ Google login support
- ✅ Business document uploads (Commercial Register, Tax Card)
- ✅ User type set to `'company'`
- ✅ Role assignment: `'company'` role
- ✅ Registration service handles company type

**Files:**
- `app/Http/Controllers/Web/RegisterCompanyController.php`
- `resources/views/web/auth/register-company.blade.php`
- `app/Services/Web/RegisterService.php` (handles company registration)

### 2. **Dashboard Redirect**
- ✅ Company users redirected to `/dashboard/jobs` when accessing dashboard
- ⚠️ **ISSUE:** This redirects to admin dashboard, not company-specific dashboard

**Location:** `routes/web.php:212-214`

### 3. **Job Management System (Shared with Clinics)**
- ✅ Job posting functionality exists
- ✅ Job model with `posted_by_type` field (can be 'clinic' or 'company')
- ✅ Job applications system
- ✅ Matching service for therapist-job matching
- ✅ Job requirements system

**Models:**
- `app/Models/Job.php` - Uses `clinic_jobs` table
- `app/Models/JobApplication.php`
- `app/Models/JobRequirement.php`

**Controllers:**
- `app/Http/Controllers/Clinic/JobController.php` - **Used by both clinics AND companies**
- `app/Http/Controllers/Web/JobController.php` - Public job listing
- `app/Http/Controllers/Dashboard/JobController.php` - Admin job management

**Views:**
- `resources/views/web/clinic/jobs/index.blade.php` - Job listing
- `resources/views/web/clinic/jobs/create.blade.php` - Create job
- `resources/views/web/clinic/jobs/applicants.blade.php` - View applicants

### 4. **Job Features**
- ✅ Job types: `job`, `training`
- ✅ Specialty selection (Orthopedic, Neurological, Pediatric, etc.)
- ✅ Techniques selection (Manual Therapy, Dry Needling, etc.)
- ✅ Equipment requirements
- ✅ Experience level requirements
- ✅ Salary/Stipend information
- ✅ Location-based jobs
- ✅ Urgency levels
- ✅ Multiple openings per job
- ✅ Featured jobs
- ✅ Application status tracking
- ✅ Match score calculation

### 5. **Database Structure**
- ✅ `clinic_jobs` table (used for both clinics and companies)
- ✅ `job_applications` table
- ✅ `job_requirements` table
- ✅ `job_skills` pivot table
- ✅ `posted_by_type` field distinguishes company vs clinic jobs

---

## ❌ What's Missing / Issues

### 1. **No Dedicated Company Dashboard**
- ❌ Company users redirected to admin dashboard (`/dashboard/jobs`)
- ❌ No company-specific dashboard controller
- ❌ No company dashboard view
- ❌ Companies see all jobs, not just their own

**Impact:** Companies cannot manage their jobs effectively

### 2. **Job Controller Issues**
- ❌ `Clinic\JobController` is used by companies (wrong namespace)
- ❌ Jobs filtered by `clinic_id` but companies should use `company_id` or `user_id`
- ❌ Route names use `clinic.jobs.*` for company users
- ❌ Views located in `web/clinic/jobs/` instead of `web/company/jobs/`

**Impact:** Confusing UX, potential data access issues

### 3. **Missing Company-Specific Features**
- ❌ No company profile management
- ❌ No company branding on job postings
- ❌ No company verification status
- ❌ No company-specific job analytics
- ❌ No bulk job posting
- ❌ No job templates
- ❌ No company subscription/plan management

### 4. **Route Issues**
- ❌ No dedicated company routes
- ❌ Companies use clinic routes: `clinic.jobs.*`
- ❌ Dashboard redirect goes to wrong place

**Current Routes:**
```php
// Companies use these (wrong):
Route::get('/jobs/{id}/applicants', [Clinic\JobController::class, 'applicants'])
Route::resource('jobs', Clinic\JobController::class)
```

### 5. **Database Schema Issues**
- ⚠️ Jobs table is `clinic_jobs` but used by companies too
- ⚠️ Jobs use `clinic_id` field for both clinics and companies
- ⚠️ No `company_id` field (uses `clinic_id` for companies)
- ⚠️ `posted_by_type` field exists but not consistently used

**Impact:** Data model confusion, potential bugs

### 6. **Missing Views**
- ❌ No `resources/views/web/company/` directory
- ❌ No company dashboard view
- ❌ No company profile view
- ❌ Companies use clinic views

### 7. **Missing Services**
- ❌ No `CompanyJobService`
- ❌ No company-specific matching logic
- ❌ No company analytics service

### 8. **Permission/Role Issues**
- ⚠️ Company role exists but permissions not fully defined
- ⚠️ No middleware to check if user is company
- ⚠️ Companies can access clinic routes

---

## 🔍 Detailed Analysis

### **Registration Flow**
1. ✅ User selects "Company (Clinic / Recruitment)" or goes to `/register/company`
2. ✅ Fills form with company details
3. ✅ Uploads business documents (optional)
4. ✅ Account created with `type='company'`
5. ✅ Role `'company'` assigned
6. ❌ **MISSING:** Account approval workflow
7. ❌ **MISSING:** Email verification
8. ❌ **MISSING:** Welcome email with dashboard link

### **Dashboard Access Flow**
1. ✅ Company user logs in
2. ✅ Redirected to `/dashboard` route
3. ❌ **ISSUE:** Redirects to `/dashboard/jobs` (admin dashboard)
4. ❌ **MISSING:** Should redirect to `/company/dashboard` or similar
5. ❌ **MISSING:** Company-specific dashboard with:
   - Posted jobs count
   - Active applications count
   - Recent applicants
   - Job posting statistics
   - Quick actions

### **Job Posting Flow**
1. ✅ Company can access job creation (via clinic routes)
2. ✅ Can create jobs with all features
3. ✅ Job saved with `posted_by_type='company'`
4. ⚠️ **ISSUE:** Job saved with `clinic_id` = company user ID (confusing)
5. ✅ Job appears in public job listings
6. ✅ Therapists can apply
7. ❌ **MISSING:** Company-specific job management interface

### **Application Management Flow**
1. ✅ Companies can view applicants for their jobs
2. ✅ Can see match scores
3. ✅ Can see therapist profiles
4. ❌ **MISSING:** Application status management (accept/reject)
5. ❌ **MISSING:** Interview scheduling
6. ❌ **MISSING:** Communication tools
7. ❌ **MISSING:** Bulk actions on applications

---

## 🎯 Recommendations

### **Priority 1: Critical Fixes (Immediate)**

1. **Create Company Dashboard**
   - Create `app/Http/Controllers/Company/DashboardController.php`
   - Create `resources/views/web/company/dashboard.blade.php`
   - Update dashboard redirect in `routes/web.php`
   - Show company-specific metrics and quick actions

2. **Create Company Job Controller**
   - Create `app/Http/Controllers/Company/JobController.php`
   - Separate from clinic controller
   - Filter jobs by company user ID
   - Company-specific routes

3. **Fix Routes**
   - Add company routes group
   - Create `routes/company.php` or add to `routes/web.php`
   - Routes: `company.dashboard`, `company.jobs.*`, `company.applicants.*`

4. **Fix Database Queries**
   - Update Job model to support both `clinic_id` and company filtering
   - Use `posted_by_type` + user ID for filtering
   - Or add `company_id` field

### **Priority 2: Important Features (Short-term)**

5. **Company Profile Management**
   - Company profile model/table
   - Company branding (logo, description)
   - Company verification status
   - Public company profile page

6. **Enhanced Job Management**
   - Company-specific job listing page
   - Job analytics (views, applications, match scores)
   - Job editing functionality
   - Job status management (active/inactive/draft)

7. **Application Management**
   - Accept/reject applications
   - Application status workflow
   - Filter and search applicants
   - Export applicant data

### **Priority 3: Nice-to-Have (Long-term)**

8. **Advanced Features**
   - Job templates
   - Bulk job posting
   - Automated matching notifications
   - Interview scheduling
   - Communication system
   - Company subscription plans
   - Job posting analytics dashboard

---

## 📊 Current Architecture Issues

### **Data Model Confusion**
```
clinic_jobs table
├── clinic_id (used for BOTH clinics AND companies)
├── posted_by_type ('clinic' or 'company')
└── ... other fields
```

**Problem:** Using `clinic_id` for companies is confusing and error-prone.

**Solution Options:**
1. Rename `clinic_id` to `posted_by_id` (generic)
2. Add `company_id` field and use appropriate one
3. Use `posted_by_type` + `clinic_id` combination

### **Controller/View Organization**
```
Current:
app/Http/Controllers/Clinic/JobController.php  ← Used by companies too
resources/views/web/clinic/jobs/              ← Used by companies too
```

**Problem:** No separation between clinic and company functionality.

**Solution:**
```
Recommended:
app/Http/Controllers/Company/JobController.php
app/Http/Controllers/Clinic/JobController.php
resources/views/web/company/jobs/
resources/views/web/clinic/jobs/
```

---

## 🔧 Implementation Plan

### **Phase 1: Fix Critical Issues (Week 1)**
1. Create company dashboard controller and view
2. Fix dashboard redirect
3. Create company job controller
4. Add company routes
5. Update job filtering logic

### **Phase 2: Enhance Features (Week 2)**
1. Company profile management
2. Enhanced job management UI
3. Application status management
4. Company-specific analytics

### **Phase 3: Advanced Features (Week 3-4)**
1. Job templates
2. Bulk operations
3. Communication tools
4. Advanced analytics

---

## 📝 Files That Need Creation/Modification

### **New Files Needed:**
1. `app/Http/Controllers/Company/DashboardController.php`
2. `app/Http/Controllers/Company/JobController.php`
3. `resources/views/web/company/dashboard.blade.php`
4. `resources/views/web/company/jobs/index.blade.php`
5. `resources/views/web/company/jobs/create.blade.php`
6. `resources/views/web/company/jobs/applicants.blade.php`
7. `app/Models/CompanyProfile.php` (optional)
8. `database/migrations/xxxx_create_company_profiles_table.php` (optional)

### **Files to Modify:**
1. `routes/web.php` - Add company routes, fix dashboard redirect
2. `app/Models/Job.php` - Add company filtering scopes
3. `app/Http/Controllers/Clinic/JobController.php` - Ensure it only handles clinics
4. `app/Services/MatchingService.php` - Add company-specific logic if needed

---

## 🚨 Critical Bugs/Issues

1. **Dashboard Redirect Bug**
   - Companies redirected to admin dashboard instead of company dashboard
   - **Fix:** Create company dashboard and update redirect

2. **Route Confusion**
   - Companies use `clinic.jobs.*` routes
   - **Fix:** Create `company.jobs.*` routes

3. **Data Model Confusion**
   - `clinic_id` used for companies
   - **Fix:** Use `posted_by_type` + user ID, or add `company_id`

4. **Missing Company Views**
   - Companies see clinic-branded views
   - **Fix:** Create company-specific views

---

## 📈 Success Metrics

After implementation, companies should be able to:
- ✅ Access dedicated company dashboard
- ✅ Post jobs with company branding
- ✅ Manage their own jobs separately from clinics
- ✅ View and manage applications
- ✅ See company-specific analytics
- ✅ Have clear separation from clinic functionality

---

## 🎓 Best Practices Comparison

### **Similar Platforms (LinkedIn, Indeed, etc.)**
- ✅ Dedicated employer dashboard
- ✅ Company profile pages
- ✅ Job analytics
- ✅ Application management tools
- ✅ Communication features
- ✅ Bulk operations

### **What We're Missing:**
- Company branding on job postings
- Company profile pages
- Advanced analytics
- Communication tools
- Bulk job management

---

## ✅ Conclusion

The company recruitment system has a **solid foundation** but needs **significant improvements** to be production-ready. The main issues are:

1. **No dedicated company dashboard** - Critical
2. **Shared controllers/views with clinics** - Causes confusion
3. **Data model confusion** - `clinic_id` used for companies
4. **Missing company-specific features** - Analytics, branding, etc.

**Recommended Action:** Implement Phase 1 fixes immediately to provide basic functionality, then proceed with enhancements.

---

**Document Version:** 1.0  
**Last Updated:** December 25, 2025

