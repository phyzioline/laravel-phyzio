# Modular Physical Therapy Clinic Management System - Explanation Guide

**How to Explain the System to Stakeholders, Developers, and Clients**

---

## 🎯 One-Sentence Summary

**"Our system adapts itself to the clinic's physical therapy specialty, activating only the clinical workflows, assessments, and operational tools that are medically and operationally relevant to that field."**

---

## 📖 Core Concept Explained

### **What Makes This Different?**

**Traditional Approach:**
- One generic system for all clinics
- All features visible to everyone
- Confusing interface with unused options
- Manual configuration required

**Our Modular Approach:**
- Same core system for all clinics
- Each specialty activates its own modules
- Clean, focused interface
- Automatic configuration based on specialty selection

### **The Entry Experience**

**When a clinic owner first logs into the dashboard:**

1. **Mandatory Popup Appears:**
   ```
   "Select Your Physical Therapy Specialty"
   
   [ ] Orthopedic Physical Therapy
   [ ] Pediatric Physical Therapy
   [ ] Neurological Rehabilitation
   [ ] Sports Physical Therapy
   [ ] Geriatric Physical Therapy
   [ ] Women's Health / Pelvic Floor
   [ ] Cardiorespiratory Physical Therapy
   [ ] Home Care / Mobile Physical Therapy
   [ ] Multi-Specialty Clinic
   ```

2. **Once Selected:**
   - System activates correct clinical workflows
   - Loads specialized assessment forms
   - Adjusts KPIs, reports, and treatment templates
   - Controls staff permissions and scheduling logic
   - Hides irrelevant features

3. **Future Flexibility:**
   - Clinics can request additional modules later
   - Not visible by default (reduces confusion)
   - Easy activation process

---

## 🏗️ System Architecture

### **Shared Core System (Used by All Clinics)**

Every clinic, regardless of specialty, has access to:

#### A. Administration
- Clinic profile & licenses
- Branches & rooms management
- Staff management (therapists, assistants, reception)
- Roles & permissions

#### B. Patient Management
- Patient registration
- Medical history
- Attachments (reports, imaging, prescriptions)
- Consent & legal documents

#### C. Scheduling
- Appointment calendar
- Therapist availability
- Room allocation
- Cancellations & rescheduling

#### D. Billing & Finance
- Session pricing
- Packages & memberships
- Insurance / cash payments
- Invoices & receipts
- Financial reports

---

## 🎨 Specialty-Based Systems (The Real Differentiator)

### **1. Orthopedic Physical Therapy System**

**Clinical Focus:** Musculoskeletal conditions, post-operative rehab, pain management

**Activated Modules:**

**Assessment:**
- Pain scale (VAS 0-10)
- Range of Motion (ROM) measurements
- Muscle strength grading (MMT)
- Postural assessment
- Gait analysis
- Special tests (e.g., McMurray, Lachman)

**Treatment Planning:**
- Exercise prescription with progression
- Manual therapy tracking
- Modalities (TENS, ultrasound, shockwave)
- Session progress notes

**Outcome Tracking:**
- Pain improvement percentage
- ROM progression charts
- Functional outcome scores (ODI, LEFS)

**Equipment Management:**
- Device usage logs
- Maintenance reminders

**Reservation Additional Data:**
- Body region (knee, shoulder, spine, ankle, etc.)
- Diagnosis / post-op status
- Pain level before session
- Required equipment (shockwave, ultrasound, etc.)
- Session intensity level
- Session type (manual / exercise / modality)

**System Impact:**
- Auto-adjusts session duration based on treatment type
- Adds equipment usage cost to pricing
- Matches therapist specialization
- Builds orthopedic weekly program automatically

---

### **2. Pediatric Physical Therapy System**

**Clinical Focus:** Children with developmental, neurological, or congenital conditions

**Activated Modules:**

**Patient Profile (Unique):**
- Child age in months (not years)
- Parent/guardian accounts (separate login)
- Consent per guardian
- Growth & developmental milestones tracking

**Pediatric Assessment:**
- Gross motor function measures (GMFM)
- Developmental delay scales
- Sensory integration tracking
- Balance & coordination tests
- Primitive reflexes assessment

**Session Management:**
- Shorter session times (30-45 min default)
- Play-based therapy logging
- Behavioral notes
- Tolerance level tracking

**Communication:**
- Parent reports (auto-generated)
- Home exercise programs (visual & simple)
- Progress summaries for schools/doctors

**Reservation Additional Data:**
- Child age (months)
- Guardian attending (yes/no, name)
- Behavioral considerations
- Session tolerance level
- School or developmental report attached

**System Impact:**
- Shorter session blocks (30-45 min)
- Therapist with pediatric certification only
- Lower pricing brackets if applicable
- Weekly program focuses on milestones, not repetitions

**⚠️ Important:** Adult-only features are automatically hidden

---

### **3. Neurological Rehabilitation System**

**Clinical Focus:** Stroke, spinal cord injury, MS, Parkinson's

**Activated Modules:**

**Neurological Assessment:**
- Muscle tone & spasticity (Modified Ashworth Scale)
- Reflexes
- Sensation testing
- Coordination tests
- Balance scales (Berg Balance Scale)
- Functional Independence Measure (FIM)
- Timed Up and Go (TUG)

**Long-Term Plans:**
- Multi-phase rehab plans (acute → subacute → chronic)
- Goal-based milestones
- Caregiver involvement tracking

**Progress Analytics:**
- Recovery curves
- Functional scores over time
- Therapist effectiveness reports

**Reservation Additional Data:**
- Diagnosis (stroke, SCI, MS, Parkinson's, etc.)
- Affected side (left, right, bilateral)
- Mobility level (bedbound, wheelchair, ambulatory)
- Cognitive status
- Caregiver present (yes/no)

**System Impact:**
- Longer sessions (60-90 min)
- Multi-therapist option
- Long-term booking packages
- Weekly programs are phase-based (not session-based)

---

### **4. Sports Physical Therapy System**

**Clinical Focus:** Athletes, performance rehab, injury prevention

**Activated Modules:**

**Athlete Profile:**
- Sport type (football, basketball, running, etc.)
- Position (if applicable)
- Training schedule
- Competition calendar

**Advanced Assessment:**
- Strength symmetry analysis
- Agility tests
- Functional movement screens (FMS)
- Power output measurements
- Load tolerance testing

**Return-to-Play Logic:**
- Phase-based clearance (Phase 1, 2, 3, 4)
- Injury risk indicators
- Performance benchmarks
- Clearance documentation

**Reporting:**
- Coach reports
- Athlete dashboards
- Performance metrics

**Reservation Additional Data:**
- Sport type
- Injury phase (acute, subacute, return-to-play)
- Competition date
- Training load (percentage)
- Clearance level (not cleared, partial, full)

**System Impact:**
- Variable session duration (45-90 min)
- Premium pricing
- Performance metrics added
- Weekly programs sync with training calendar

---

### **5. Geriatric Physical Therapy System**

**Clinical Focus:** Elderly patients, fall prevention, chronic conditions

**Activated Modules:**

**Risk Assessment:**
- Fall risk tools (Morse Fall Scale)
- Mobility scales
- Cognitive screening (MMSE, MoCA)

**Treatment Adjustments:**
- Lower intensity plans
- Assistive device tracking
- Home safety notes
- Medication considerations

**Family Access:**
- Family caregiver accounts
- Progress summaries
- Safety alerts

**Reservation Additional Data:**
- Fall risk level (low, moderate, high)
- Assistive device (cane, walker, wheelchair, none)
- Chronic conditions (comorbidities)
- Family contact (for reporting)

**System Impact:**
- Lower intensity session limits
- Longer rest intervals
- Family reporting enabled
- Weekly programs emphasize safety and mobility

---

### **6. Women's Health / Pelvic Floor System**

**Clinical Focus:** Pregnancy, postpartum, pelvic dysfunction

**Activated Modules:**

**Sensitive Data Controls:**
- Restricted access permissions
- Privacy-focused documentation
- HIPAA-compliant storage

**Specialized Assessment:**
- Pelvic floor strength
- Pain & function questionnaires
- Posture assessment (pregnancy-specific)
- Diastasis recti assessment

**Treatment Tracking:**
- Biofeedback sessions
- Exercise progression
- Stage-based protocols

**Reservation Additional Data:**
- Pregnancy / postpartum status
- Trimester or recovery stage (weeks postpartum)
- Pain sensitivity level
- Privacy level

**System Impact:**
- Restricted therapist assignment
- Private room enforcement
- Specialized pricing
- Weekly programs are stage-based

---

### **7. Cardiorespiratory Physical Therapy System**

**Clinical Focus:** Cardiac rehabilitation, pulmonary conditions

**Activated Modules:**

**Assessment:**
- Vital signs monitoring (HR, BP, O2 sat)
- Exercise tolerance tests
- Respiratory function tests
- Functional capacity assessment

**Treatment:**
- Cardiac rehab protocols
- Breathing exercises
- Energy conservation techniques

**Reservation Additional Data:**
- Cardiac/pulmonary diagnosis
- Vital signs baseline
- Exercise tolerance level
- Monitoring requirements

---

### **8. Home Care / Mobile Physical Therapy System**

**Clinical Focus:** Home-based rehabilitation, mobile services

**Activated Modules:**

**Location Management:**
- Patient address with GPS
- Travel time calculation
- Route optimization
- Travel cost calculation

**Home Environment:**
- Home environment notes
- Safety assessment
- Equipment availability

**Portable Equipment:**
- Equipment inventory
- Equipment tracking
- Maintenance logs

**Reservation Additional Data:**
- Patient address (full address with GPS)
- Travel time (estimated)
- Home environment notes
- Required portable equipment

**System Impact:**
- Travel cost added to pricing
- Route optimization
- Session buffer time for travel
- Weekly programs account for therapist movement

---

### **9. Multi-Specialty Clinic System**

**Who Uses This:** Large centers offering multiple PT services

**System Behavior:**
- Multiple specialties activated
- Specialty selection per patient/episode
- Therapist assigned by specialization
- Unified financial dashboard
- Specialty-wise performance analytics

---

## 💰 Reservation System – Additional Data by Clinic Type

### **Common Reservation Data (All Clinics)**

These fields always exist:
- ✅ Patient name
- ✅ Patient ID
- ✅ Therapist
- ✅ Session date & time
- ✅ Session duration
- ❌ Visit type (evaluation / follow-up / re-evaluation) - **NEEDED**
- ❌ Location (clinic / home) - **NEEDED**
- ❌ Payment method (cash, card, insurance) - **NEEDED**
- ✅ Notes

### **Specialty-Specific Additional Fields**

When a reservation is created, the system adds dynamic fields based on the selected PT specialty. This data affects:
- Duration
- Pricing
- Therapist assignment
- Weekly programs

**See detailed breakdown in the main analysis document for each specialty.**

---

## 💳 Payment Calculator System (Smart & Dynamic)

### **Payment Calculation Formula**

```
Total Session Price = 
    Base Session Price
    + Specialty Adjustment
    + Therapist Level Factor
    + Equipment Usage Fees
    + Location Factor
    + Duration Factor
    - Package / Program Discount
```

### **Payment Factors Explained**

#### 1. Base Price
- Defined per specialty
- Different for evaluation vs follow-up

#### 2. Specialty Adjustment
- Pediatric ≠ Sports ≠ Neuro
- Each has its own coefficient
- Example: Sports = 1.2x, Pediatric = 0.9x

#### 3. Therapist Level
- Junior: 1.0x
- Senior: 1.3x
- Consultant: 1.5x

#### 4. Equipment Usage
- Shockwave: +$50
- Biofeedback: +$30
- Advanced devices: Variable

#### 5. Location Factor
- Clinic: 1.0x
- Home care: 1.2x – 1.5x (based on distance)

#### 6. Duration Factor
- 30 minutes: 0.7x
- 45 minutes: 0.85x
- 60 minutes: 1.0x
- 90 minutes: 1.4x

#### 7. Discounts
- Weekly program: 10-15%
- Monthly package: 20-25%
- Insurance agreement: Variable

### **Payment Output**

- Session price breakdown
- Package price
- Remaining balance
- Invoice auto-generated
- Insurance claim (if enabled)

---

## 📅 Weekly Programs System (Core Value Feature)

Instead of booking random sessions, the system encourages structured weekly programs.

### **Program Creation Logic**

A weekly program includes:
- Number of sessions per week
- Session types (evaluation, follow-up, re-evaluation)
- Progression rules (week 1-2: X, week 3-4: Y)
- Re-assessment schedule (every 4 weeks, etc.)
- Payment plan (pay per week, monthly, upfront)

### **Weekly Programs by Specialty**

#### **Orthopedic**
- 2–3 sessions/week
- Strength + mobility progression
- Weekly ROM & pain evaluation

#### **Pediatric**
- 1–2 short sessions/week (30-45 min)
- Developmental goal tracking
- Parent home activities included

#### **Neurological**
- 3–5 sessions/week
- Phase-based goals (acute → subacute → chronic)
- Monthly functional reassessment

#### **Sports**
- 2–4 sessions/week
- Load management
- Return-to-play checkpoints

#### **Geriatric**
- 1–2 sessions/week
- Fall prevention focus
- Safety compliance checks

#### **Women's Health**
- Stage-based programs
- Pregnancy or recovery week mapping

#### **Home Care**
- Route-optimized weekly planning
- Fixed therapist assignment

### **Program Payment Models**

- **Pay per week** - Weekly billing
- **Monthly subscription** - Discounted monthly rate
- **Full program upfront** - Largest discount (20-25%)

### **Program Features**

The system:
- Auto-books sessions
- Locks pricing for program duration
- Tracks attendance
- Calculates remaining sessions
- Sends reminders
- Generates progress reports

---

## 🎯 Why This Makes Your System Powerful

### **1. Medical Accuracy**
- Right tools for the right specialty
- Evidence-based protocols
- Proper outcome measures

### **2. Financial Transparency**
- Clear pricing breakdown
- Automatic calculations
- No manual errors

### **3. Higher Patient Commitment**
- Structured programs increase adherence
- Clear treatment plans
- Progress tracking

### **4. Predictable Clinic Revenue**
- Program-based bookings
- Upfront payments option
- Reduced no-shows

### **5. Easy Expansion**
- Add new specialties easily
- Insurance integration ready
- AI features can be added per specialty

---

## 📊 How to Present This

### **To Business Stakeholders:**

**"We're building a modular system that adapts to each clinic's specialty, reducing training time, increasing clinical accuracy, and improving patient outcomes. This is a competitive differentiator that allows us to scale across different markets and specialties."**

### **To Developers:**

**"We need a specialty selection system that activates modules dynamically. The core system remains the same, but specialty-specific features, forms, and workflows are conditionally loaded. Think of it as a plugin architecture where each specialty is a plugin."**

### **To Clinics:**

**"When you first log in, you'll select your specialty. The system will then show you only the tools and forms relevant to your practice. No confusion, no clutter - just what you need to provide excellent patient care."**

---

## ✅ Key Benefits Summary

1. **Faster Onboarding** - Clinics see only relevant features
2. **Less Training Time** - Focused interface reduces learning curve
3. **Higher Clinical Accuracy** - Right tools for the right specialty
4. **Better Compliance** - Specialty-specific documentation
5. **Scalable** - Easy to add new specialties
6. **Easy to Upsell** - Clinics can add modules later

---

**Document Version:** 1.0  
**Last Updated:** December 28, 2025  
**Purpose:** Explanation and Communication Guide

