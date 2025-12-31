# 👥 Staff Status Management Guide

## Overview
The staff status system in Phyzioline manages whether staff members are **Active** or **Inactive** in your clinic. This allows you to temporarily deactivate staff without permanently deleting them.

---

## 🎯 How Staff Status Works

### **Status Types:**
- ✅ **Active** - Staff member is currently working and can access the system
- ❌ **Inactive** - Staff member is temporarily deactivated (on leave, terminated, etc.)

### **Where Status is Stored:**
- Status is managed in the `clinic_staff` table via the `is_active` field
- This is separate from the `users` table to allow multi-clinic scenarios

---

## 📍 Where to Manage Staff Status

### **Location:** Staff Directory Page
**URL:** `/clinic/staff`  
**Navigation:** Sidebar → Staff

### **Features Available:**

1. **View All Staff**
   - See both active and inactive staff members
   - Filter by status (All / Active / Inactive)

2. **Toggle Status**
   - Click the status toggle button (🟡 Deactivate / 🟢 Activate)
   - Confirmation dialog appears before changing status

3. **Edit Staff**
   - Click the Edit (✏️) button to modify staff details
   - Edit form allows changing name, email, phone, and role

4. **Delete Staff**
   - Click the Delete (🗑️) button to permanently remove staff
   - This sets `is_active = false` and adds `terminated_date`

---

## 🔧 How to Activate/Deactivate Staff

### **Method 1: Using Toggle Button (Recommended)**

1. Go to **Staff Directory** (`/clinic/staff`)
2. Find the staff member you want to activate/deactivate
3. Click the status button:
   - **🟡 Yellow button** = Currently Active (click to deactivate)
   - **🟢 Green button** = Currently Inactive (click to activate)
4. Confirm the action in the dialog
5. Status updates immediately

### **Method 2: Using Edit Form**

1. Click **Edit** (✏️) button on the staff member
2. The edit form shows current status
3. Note: Status toggle is separate from edit form for security

---

## 📊 Status Display

### **In Staff Directory Table:**
- **Active** = Green badge (🟢)
- **Inactive** = Gray badge (⚪)

### **Status Filter:**
- **All** - Shows both active and inactive staff
- **Active** - Shows only active staff (default view)
- **Inactive** - Shows only inactive staff

---

## 🔄 What Happens When You Change Status

### **When Activating Staff:**
- ✅ `is_active` set to `true`
- ✅ `terminated_date` cleared (set to `null`)
- ✅ Staff member appears in active staff lists
- ✅ Staff can log in and access the system

### **When Deactivating Staff:**
- ❌ `is_active` set to `false`
- ❌ `terminated_date` set to current date
- ❌ Staff member removed from active staff lists
- ❌ Staff cannot log in (if authentication checks `is_active`)

---

## 🛡️ Security & Data Isolation

### **Clinic Isolation:**
- Each clinic only sees their own staff members
- Status changes only affect the specific clinic
- Staff can be active in one clinic, inactive in another

### **Role-Based Access:**
- Only clinic admins can manage staff status
- Staff members cannot change their own status
- All actions are logged for audit purposes

---

## 📝 Status vs Delete

### **Deactivate (Toggle Status):**
- ✅ Reversible - can reactivate later
- ✅ Preserves all data and history
- ✅ Staff record remains in system
- ✅ Can be reactivated with one click

### **Delete (Permanent):**
- ❌ Sets `is_active = false` AND `terminated_date`
- ❌ Cannot be undone easily
- ⚠️ Use only when staff member is permanently leaving

---

## 🎨 UI Elements

### **Status Toggle Button:**
- **Active Staff:** 🟡 Yellow button with ban icon (🚫)
  - Tooltip: "Deactivate"
  - Click to deactivate

- **Inactive Staff:** 🟢 Green button with check icon (✅)
  - Tooltip: "Activate"
  - Click to activate

### **Status Badge:**
- **Active:** Green badge with "Active" text
- **Inactive:** Gray badge with "Inactive" text

---

## 🔍 Technical Details

### **Database Structure:**
```sql
clinic_staff table:
- is_active (boolean) - true = active, false = inactive
- terminated_date (date) - set when deactivated, null when active
- hired_date (date) - original hire date
```

### **Controller Method:**
```php
StaffController::toggleStatus($id)
- Toggles is_active between true/false
- Updates terminated_date accordingly
- Returns success message
```

### **Route:**
```
POST /clinic/staff/{id}/toggle-status
Route name: clinic.staff.toggle-status
```

---

## 💡 Best Practices

1. **Use Deactivate for Temporary Leaves**
   - Staff on vacation, sick leave, or temporary leave
   - Can be reactivated quickly when they return

2. **Use Delete for Permanent Departures**
   - Staff who have permanently left the clinic
   - Ensures they cannot be accidentally reactivated

3. **Check Status Before Assigning Tasks**
   - Only assign appointments/tasks to active staff
   - System automatically filters inactive staff from dropdowns

4. **Regular Status Reviews**
   - Periodically review inactive staff
   - Delete permanently if not returning

---

## ❓ FAQ

**Q: Can inactive staff still log in?**  
A: No, inactive staff are filtered out from authentication and cannot access the system.

**Q: What happens to appointments assigned to inactive staff?**  
A: Appointments remain in the system but inactive staff won't appear in new appointment assignments.

**Q: Can I reactivate a deleted staff member?**  
A: Yes, use the toggle button to reactivate. The staff member will be restored.

**Q: Does deactivating affect historical data?**  
A: No, all historical records (appointments, notes, etc.) remain intact.

---

**Last Updated:** December 31, 2025  
**System Version:** Phyzioline Clinic Management v2.0

