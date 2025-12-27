# CSS/UI Style Files Inventory - Phyzioline Project

## 📊 Total CSS Files: **46 files**

---

## 🎨 **Therapist Dashboard CSS Files** (6 files)

### Main CSS Files:
1. **`public/dashboard/css/teal-theme.css`**
   - Therapist dashboard theme (now green)
   - Sidebar styling, navigation colors
   - Main content area styling

2. **`public/css/phyzioline-typography.css`**
   - Global Inter font typography system
   - Icon font preservation rules
   - Applied to all dashboards including therapist

3. **`public/web/assets/css/style.css`**
   - Main website styles
   - Used by therapist layout

4. **`public/web/assets/css/bootstrap.min.css`**
   - Bootstrap 4 framework
   - Used by therapist layout

5. **`public/web/assets/css/line-awesome.min.css`**
   - Line Awesome icon library
   - Used for icons in therapist dashboard

6. **`resources/views/web/therapist/layout.blade.php`** (Inline styles)
   - Custom styles for therapist dashboard
   - Typography overrides

---

## 🏢 **Admin Dashboard CSS Files** (15+ files)

### Core Dashboard CSS:
1. **`public/dashboard/sass/main.css`**
   - Main dashboard layout
   - Header, sidebar, page wrapper styles
   - Navigation and menu styles

2. **`public/dashboard/css/dash.css`**
   - Dashboard-specific styles

3. **`public/dashboard/css/teal-theme.css`**
   - Teal/green theme overrides
   - Sidebar colors

4. **`public/dashboard/assets/css/bootstrap-extended.css`**
   - Extended Bootstrap styles

5. **`public/dashboard/assets/css/bootstrap.min.css`**
   - Bootstrap 5 framework

### Theme Files:
6. **`public/dashboard/sass/blue-theme.css`**
   - Blue theme variant

7. **`public/dashboard/sass/dark-theme.css`**
   - Dark theme variant

8. **`public/dashboard/sass/semi-dark.css`**
   - Semi-dark theme variant

9. **`public/dashboard/sass/bordered-theme.css`**
   - Bordered theme variant

10. **`public/dashboard/sass/responsive.css`**
    - Responsive breakpoints

### Plugin CSS:
11. **`public/dashboard/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css`**
    - Scrollbar styling

12. **`public/dashboard/assets/plugins/metismenu/mm-vertical.css`**
    - Vertical menu styles

13. **`public/dashboard/assets/plugins/metismenu/metisMenu.min.css`**
    - MetisMenu component

14. **`public/dashboard/assets/plugins/simplebar/css/simplebar.css`**
    - Simplebar scrollbar

15. **`public/dashboard/assets/css/extra-icons.css`**
    - Additional icon fonts

---

## 🌐 **Website (Frontend) CSS Files** (10+ files)

1. **`public/web/assets/css/style.css`**
   - Main website stylesheet

2. **`public/web/assets/css/style-ar.css`**
   - Arabic RTL styles

3. **`public/web/assets/css/bootstrap.min.css`**
   - Bootstrap 4

4. **`public/web/assets/css/line-awesome.min.css`**
   - Line Awesome icons

5. **`public/web/assets/css/owl.carousel.min.css`**
   - Carousel slider

6. **`public/web/assets/css/magnific-popup.css`**
   - Lightbox/popup

7. **`public/web/assets/css/jquery-ui.css`**
   - jQuery UI components

8. **`public/web/assets/css/login.css`**
   - Login page styles

---

## 🔧 **Global/Shared CSS Files** (5+ files)

1. **`public/css/phyzioline-typography.css`**
   - **CRITICAL:** Global Inter font system
   - Icon font preservation
   - Applied to ALL dashboards

2. **`public/css/rtl-overrides.css`**
   - RTL language overrides

3. **`public/dashboard/css/rtl.css`**
   - Dashboard RTL styles

4. **`public/layout/plugins/toastr/toastr.min.css`**
   - Toast notifications

5. **`public/layout/plugins/toastr/toastr.css`**
   - Toast notifications (unminified)

---

## 📦 **Plugin/Third-Party CSS Files** (10+ files)

1. **`public/dashboard/assets/plugins/datatable/css/dataTables.bootstrap5.min.css`**
   - DataTables styling

2. **`public/dashboard/assets/plugins/select2/css/select2.min.css`**
   - Select2 dropdown

3. **`public/dashboard/assets/plugins/select2/css/select2-bootstrap4.css`**
   - Select2 Bootstrap theme

4. **`public/dashboard/assets/plugins/fullcalendar/css/main.min.css`**
   - FullCalendar component

5. **`public/dashboard/assets/plugins/bs-stepper/css/bs-stepper.css`**
   - Bootstrap Stepper

6. **`public/dashboard/assets/plugins/bs-stepper/css/bs-stepper-custom.css`**
   - Custom stepper styles

7. **`public/dashboard/assets/plugins/fancy-file-uploader/fancy_fileupload.css`**
   - File uploader

8. **`public/dashboard/assets/plugins/input-tags/css/tagsinput.css`**
   - Tags input

9. **`public/dashboard/assets/plugins/notifications/css/lobibox.min.css`**
   - Lobibox notifications

10. **`public/dashboard/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css`**
    - Vector maps

---

## 📝 **Summary by Dashboard Type**

### Therapist Dashboard:
- **6 CSS files** (including shared typography)
- Main: `teal-theme.css`, `phyzioline-typography.css`, `style.css`

### Admin Dashboard:
- **15+ CSS files**
- Main: `main.css`, `dash.css`, `teal-theme.css`, theme variants

### Company Dashboard:
- Uses same as Admin Dashboard
- Plus: `dashboard_master.blade.php` inline styles

### Website Frontend:
- **10+ CSS files**
- Main: `style.css`, `style-ar.css`, Bootstrap, plugins

---

## ⚠️ **Important Notes**

1. **`phyzioline-typography.css`** is loaded in ALL dashboards
2. **`teal-theme.css`** is used by both therapist and admin dashboards
3. Theme files (blue, dark, semi-dark, bordered) are loaded conditionally
4. RTL CSS files are loaded only for Arabic locale
5. Plugin CSS files are loaded as needed per page

---

## 🔍 **CSS Loading Order (Critical)**

1. Bootstrap CSS (base framework)
2. Plugin CSS (perfect-scrollbar, metismenu, etc.)
3. Theme CSS (blue-theme, dark-theme, etc.)
4. Main CSS (main.css, dash.css)
5. **Icon Font CSS** (Font Awesome, Line Awesome, Material Icons)
6. **Typography CSS** (`phyzioline-typography.css`) - **MUST load last**

---

**Last Updated:** December 28, 2025

