<?php

use Spatie\Permission\Commands\Show;
use App\Services\Web\FavoriteService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ShowController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\FavoriteController;
use App\Http\Controllers\Web\PasswordController;
use App\Http\Controllers\Web\PrivacyPolicyController;
use App\Http\Controllers\Web\HistoryOrderController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\TearmsConditionController;
use App\Http\Controllers\Web\ShippingPolicyController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Web\SocialLoginController;
use App\Http\Controllers\Web\FeedbackController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Web\FeedController;

// Root route - redirect based on locale
Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'en'));
    if (!in_array($locale, ['en', 'ar'])) {
        $locale = 'en';
    }
    return redirect('/' . $locale);
})->middleware(['localeSessionRedirect']);

// Register routes explicitly for both locales (en and ar)
// This ensures /en and /ar both work correctly
$supportedLocales = ['en', 'ar'];
foreach ($supportedLocales as $locale) {
    Route::group([
        'prefix' => $locale,
        'middleware' => ['localeViewPath']
    ], function() use ($locale) {
        // Set locale for this route group
        app()->setLocale($locale);
        session(['locale' => $locale]);
        
        // Home route - Laravel will match based on URL prefix
        // Use locale-specific route name to avoid conflicts
        Route::get('/', [HomeController::class, 'index'])->name("home.{$locale}");

    Route::get('/register', [RegisterController::class, 'index'])->name("view_register.{$locale}");
    Route::post('/register', [RegisterController::class, 'store'])->name("register.{$locale}");

    Route::get('/otp', [RegisterController::class, 'otp'])->name("view_otp.{$locale}");
    Route::get('/otp/resend', [RegisterController::class, 'resendOtp'])->name("resend_otp.{$locale}");
    Route::post('/verify', [RegisterController::class, 'verify'])->name("verify.{$locale}");

    // Company Registration
    Route::get('/register/company', [App\Http\Controllers\Web\RegisterCompanyController::class, 'create'])->name("company.register.{$locale}");
    Route::post('/register/company', [App\Http\Controllers\Web\RegisterCompanyController::class, 'store'])->name("company.register.store.{$locale}");

    Route::get('/login', [LoginController::class, 'index'])->name("view_login.{$locale}");
    Route::post('/login', [LoginController::class, 'store'])->name("login.{$locale}");

    Route::get('/forget_password', [PasswordController::class, 'index'])->name("view_forget_password.{$locale}");
    Route::post('/forget_password', [PasswordController::class, 'store'])->name("forget_password.{$locale}");
    
    Route::get('/products/{id}', [ShowController::class, 'product'])->name("product.show.{$locale}");
        Route::get('shipping_policy',[ShippingPolicyController::class, 'index'])->name("shipping_policy.index.{$locale}");
     Route::get('privacy_policy',[PrivacyPolicyController::class, 'index'])->name("privacy_policy.index.{$locale}");
     
     Route::get('/contact-us', [FeedbackController::class, 'index'])->name("feedback.index.{$locale}");
     Route::post('/contact-us', [FeedbackController::class, 'store'])->name("feedback.store.{$locale}");
     
     Route::get('/about-us', function() {
         return view('web.pages.about');
     })->name("about.index.{$locale}");

     Route::get('tearms_condition',[TearmsConditionController::class, 'index'])->name("tearms_condition.index.{$locale}");
        
        Route::get('/shop/search', [ShowController::class, 'search'])->name("web.shop.search.{$locale}");
     
          Route::get('/shop/subcategory/{id}', [ShowController::class, 'ProductBySubCategory'])->name("web.shop.category.{$locale}");
          
          // Product tracking for metrics
          Route::post('/products/{id}/track-click', [\App\Http\Controllers\Web\ProductTrackingController::class, 'trackClick'])->name("web.products.track-click.{$locale}");
          Route::post('/products/{id}/track-add-to-cart', [\App\Http\Controllers\Web\ProductTrackingController::class, 'trackAddToCart'])->name("web.products.track-add-to-cart.{$locale}");
        
        Route::get('/jobs', [App\Http\Controllers\Web\JobController::class, 'index'])->name("web.jobs.index.{$locale}");
        Route::get('/jobs/{id}', [App\Http\Controllers\Web\JobController::class, 'show'])->name("web.jobs.show.{$locale}");
        Route::post('/jobs/{id}/apply', [App\Http\Controllers\Web\JobController::class, 'apply'])->name("web.jobs.apply.{$locale}");

        // Data Hub
        Route::get('/data-hub', [App\Http\Controllers\Web\DataHubController::class, 'index'])->name("web.datahub.index.{$locale}");
        Route::get('/data-hub/dashboard', [App\Http\Controllers\Web\DataHubController::class, 'dashboard'])->name("web.datahub.dashboard.{$locale}");
        Route::get('/data-hub/licensing', [App\Http\Controllers\Web\DataHubController::class, 'licensing'])->name("web.datahub.licensing.{$locale}");


        // Cart routes - available to guests and authenticated users
        Route::resource('carts', CartController::class)->names([
            'index' => "carts.index.{$locale}",
            'create' => "carts.create.{$locale}",
            'store' => "carts.store.{$locale}",
            'show' => "carts.show.{$locale}",
            'edit' => "carts.edit.{$locale}",
            'update' => "carts.update.{$locale}",
            'destroy' => "carts.destroy.{$locale}",
        ]);
        
        // Order route - available to guests and authenticated users (guest checkout supported)
        Route::post('store',[OrderController::class, 'store'])->name('order.store.' . $locale);
        
        // Authenticated routes
    Route::group(['middleware' => ['auth']], function () use ($locale) {

        // Verification Routes
        Route::get('/verification/complete-account', [App\Http\Controllers\Web\VerificationController::class, 'completeAccount'])->name("verification.complete-account.{$locale}");
        Route::get('/verification/center', [App\Http\Controllers\Web\VerificationController::class, 'verificationCenter'])->name("verification.verification-center.{$locale}");
        Route::post('/verification/upload-document', [App\Http\Controllers\Web\VerificationController::class, 'uploadDocument'])->name("verification.upload-document.{$locale}");
        Route::delete('/verification/documents/{documentId}', [App\Http\Controllers\Web\VerificationController::class, 'deleteDocument'])->name("verification.delete-document.{$locale}");

        Route::post('/products/{id}/reviews', [App\Http\Controllers\Web\ProductReviewController::class, 'store'])->name("web.products.reviews.store.{$locale}");

        // Generic Profile Routes (Vendor, Buyer, Patient)
        Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'index'])->name("web.profile.index.{$locale}");
        Route::put('/profile', [App\Http\Controllers\Web\ProfileController::class, 'update'])->name("web.profile.update.{$locale}");

        // Vendor Dashboard Routes (Amazon-style multi-vendor)
        Route::prefix('vendor')->name("vendor.{$locale}.")->middleware(App\Http\Middleware\VendorMiddleware::class)->group(function () use ($locale) {
            Route::get('/dashboard', [App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name("dashboard.{$locale}");
            Route::get('/orders', [App\Http\Controllers\Vendor\VendorOrderController::class, 'index'])->name("orders.index.{$locale}");
            Route::get('/orders/{id}', [App\Http\Controllers\Vendor\VendorOrderController::class, 'show'])->name("orders.show.{$locale}");
            Route::get('/shipments', [App\Http\Controllers\Vendor\VendorShippingController::class, 'index'])->name("shipments.index.{$locale}");
            Route::get('/shipments/{id}', [App\Http\Controllers\Vendor\VendorShippingController::class, 'show'])->name("shipments.show.{$locale}");
            Route::post('/shipments/{id}', [App\Http\Controllers\Vendor\VendorShippingController::class, 'update'])->name("shipments.update.{$locale}");
            Route::get('/wallet', [App\Http\Controllers\Vendor\VendorWalletController::class, 'index'])->name("wallet.{$locale}");
            Route::post('/wallet/payout', [App\Http\Controllers\Vendor\VendorWalletController::class, 'requestPayout'])->name("wallet.payout.{$locale}");
        });

        // Instructor Routes
        Route::prefix('instructor')->name("instructor.{$locale}.")->group(function () use ($locale) {
            Route::get('/dashboard', [App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name("dashboard.{$locale}");
            
            // Course Wizard Routes (Specific routes first)
            Route::get('/courses/create', [App\Http\Controllers\Instructor\CourseController::class, 'create'])->name("courses.create.{$locale}");
            Route::post('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'store'])->name("courses.store.{$locale}");

            // Course Management (Wildcard routes last)
            Route::get('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'index'])->name("courses.index.{$locale}");
            Route::get('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'show'])->name("courses.show.{$locale}");
            Route::get('/courses/{course}/edit', [App\Http\Controllers\Instructor\CourseController::class, 'edit'])->name("courses.edit.{$locale}");
            Route::put('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'update'])->name("courses.update.{$locale}");
            Route::delete('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'destroy'])->name("courses.destroy.{$locale}");
            
            // Modules & Units
            Route::post('/courses/{course}/modules', [App\Http\Controllers\Instructor\CourseController::class, 'storeModule'])->name("courses.modules.store.{$locale}");
            Route::post('/courses/{course}/modules/{module}/units', [App\Http\Controllers\Instructor\CourseController::class, 'storeUnit'])->name("courses.modules.units.store.{$locale}");
            
            // Students
            Route::get('/students', [App\Http\Controllers\Instructor\StudentController::class, 'index'])->name("students.index.{$locale}");
            Route::get('/students/{student}', [App\Http\Controllers\Instructor\StudentController::class, 'show'])->name("students.show.{$locale}");
        });

        // --- Home Visit System Routes ---
        
        // Patient Flow
        Route::get('/home_visits/request', [App\Http\Controllers\Web\PatientVisitController::class, 'create'])->name("patient.home_visits.create.{$locale}");
        Route::post('/home_visits/request', [App\Http\Controllers\Web\PatientVisitController::class, 'store'])->name("patient.home_visits.store.{$locale}");
        Route::get('/home_visits/status/{id}', [App\Http\Controllers\Web\PatientVisitController::class, 'show'])->name("patient.home_visits.show.{$locale}");
        
        // Patient Self-Scheduling
        Route::get('/self-schedule', [App\Http\Controllers\Patient\SelfSchedulingController::class, 'index'])->name("patient.self-schedule.index.{$locale}");
        Route::get('/self-schedule/available-slots', [App\Http\Controllers\Patient\SelfSchedulingController::class, 'getAvailableSlots'])->name("patient.self-schedule.slots.{$locale}");
        Route::post('/self-schedule', [App\Http\Controllers\Patient\SelfSchedulingController::class, 'store'])->name("patient.self-schedule.store.{$locale}");
        Route::post('/self-schedule/intake-form', [App\Http\Controllers\Patient\SelfSchedulingController::class, 'submitIntakeForm'])->name("patient.self-schedule.submitIntake.{$locale}");

        // Therapist Flow (Legacy - Consolidated into therapist.php)
        // Route::get('/therapist/visits', [App\Http\Controllers\Therapist\VisitManagementController::class, 'index'])->name('therapist.visits.index');
        // Route::post('/therapist/visits/{visit}/accept', [App\Http\Controllers\Therapist\VisitManagementController::class, 'accept'])->name('therapist.visits.accept');
        // Route::post('/therapist/visits/{visit}/status', [App\Http\Controllers\Therapist\VisitManagementController::class, 'updateStatus'])->name('therapist.visits.status');
        // Route::post('/therapist/visits/{visit}/complete', [App\Http\Controllers\Therapist\VisitManagementController::class, 'complete'])->name('therapist.visits.complete');

        // Help Center Routes
        Route::controller(App\Http\Controllers\Web\HelpCenterController::class)->prefix('help')->name("help.{$locale}.")->group(function () {
            Route::get('/', 'index')->name("index");
            Route::get('/search', 'search')->name("search");
            Route::get('/{category}', 'category')->name("category");
            Route::get('/{category}/{article}', 'article')->name("article");
        });

        // Feed Routes (Public)
        Route::get('/feed', [App\Http\Controllers\Web\FeedController::class, 'index'])->name("feed.index.{$locale}");
        Route::post('/feed', [App\Http\Controllers\Web\FeedController::class, 'store'])->name("feed.store.{$locale}"); // New
        Route::post('/feed/{id}/interact', [App\Http\Controllers\Web\FeedController::class, 'logInteraction'])->name("feed.interact.{$locale}");
        Route::post('/feed/{id}/like', [App\Http\Controllers\Web\FeedController::class, 'toggleLike'])->name("feed.like.{$locale}");

        // Admin Feed Management
        Route::prefix('admin')->name("admin.{$locale}.")->group(function() use ($locale){
            Route::resource('feed', App\Http\Controllers\Admin\FeedController::class)->names([
                'index' => "admin.feed.index.{$locale}",
                'create' => "admin.feed.create.{$locale}",
                'store' => "admin.feed.store.{$locale}",
                'show' => "admin.feed.show.{$locale}",
                'edit' => "admin.feed.edit.{$locale}",
                'update' => "admin.feed.update.{$locale}",
                'destroy' => "admin.feed.destroy.{$locale}",
            ]);
        });

        // Clinic ERP Routes - MOVED TO OUTSIDE LOCALE GROUP (see line 286)
        // These routes are now defined outside the locale group to avoid conflicts
        
          Route::get('complecet_info_view',[LoginController::class, 'complecet_info_view'])->name("complecet_info_view.{$locale}");
        Route::post('complecet_info',[LoginController::class, 'complecet_info'])->name("complecet_info.{$locale}");

        Route::put('update_cart/{id}', [CartController::class, 'update_carts'])->name("carts.update_cart.{$locale}");
        Route::get('total',[CartController::class, 'total'])->name("carts.total.{$locale}");
        Route::get('flush',[CartController::class, 'flush'])->name("carts.flush.{$locale}");
        Route::get('favorites', [FavoriteController::class, 'index'])->name("favorites.index.{$locale}");
        Route::post('favorites', [FavoriteController::class, 'store'])->name("favorites.store.{$locale}");
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name("favorites.delete.{$locale}");
        Route::get('compare', [App\Http\Controllers\Web\CompareController::class, 'index'])->name("compare.index.{$locale}");
        Route::post('compare', [App\Http\Controllers\Web\CompareController::class, 'store'])->name("compare.store.{$locale}");
        Route::delete('/compare/{id}', [App\Http\Controllers\Web\CompareController::class, 'destroy'])->name("compare.delete.{$locale}");
        Route::get('history_order',[HistoryOrderController::class, 'index'])->name("history_order.index.{$locale}");

            // logout
            Route::get('logout', [LoginController::class, 'logout'])->name("logout.{$locale}");
            
             // Generic Dashboard Redirect - Redirects to non-localized dashboard routes
            Route::get('/dashboard', function () {
                $user = Auth::user();
                // Remove locale prefix and redirect to actual dashboard
                $locale = app()->getLocale();
                $dashboardUrl = str_replace('/' . $locale . '/dashboard', '/dashboard', request()->url());
                
                if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
                    return redirect('/dashboard/home');
                } elseif ($user->type === 'therapist') {
                    return redirect()->route('therapist.dashboard');
                } elseif ($user->hasRole('instructor')) {
                    return redirect()->route('instructor.dashboard');
                } elseif ($user->hasRole('clinic')) {
                    return redirect()->route('clinic.dashboard');
                } elseif ($user->type === 'company') {
                    // Recruitment company dashboard
                    return redirect()->route('company.dashboard');
                } else {
                    return redirect()->route('history_order.index');
                }
            })->name('dashboard');
        });
        
    }); // End of locale route group
} // End of foreach loop

// Webhook routes (no locale prefix needed - called by external services)
Route::post('/webhooks/payments/{provider}', [\App\Http\Controllers\Web\PaymentWebhookController::class, 'handle'])->name('webhooks.payments');
Route::post('/webhooks/shipping/{provider}', [\App\Http\Controllers\Web\ShippingWebhookController::class, 'handle'])->name('webhooks.shipping');

// Currency Switcher (no locale prefix needed - works globally)
Route::post('/currency/switch', function (\Illuminate\Http\Request $request) {
    $currency = $request->input('currency');
    if (array_key_exists($currency, config('currency.currencies'))) {
        session(['currency' => $currency]);
    }
    return redirect()->back();
})->name('currency.switch');

// Routes that work WITHOUT locale prefix (use session/default locale)
// These routes were working before and should continue to work
Route::get('/shop', [ShowController::class, 'show'])->name('show');
Route::get('/home_visits', [App\Http\Controllers\Web\HomeVisitController::class, 'index'])->name('web.home_visits.index');
Route::get('/home_visits/therapist/{id}', [App\Http\Controllers\Web\HomeVisitController::class, 'show'])->name('web.home_visits.show');
Route::get('/home_visits/book/{id}', [App\Http\Controllers\Web\HomeVisitController::class, 'book'])->name('web.home_visits.book');
Route::post('/home_visits/book', [App\Http\Controllers\Web\HomeVisitController::class, 'store'])->name('web.home_visits.store');
Route::get('/home_visits/payment/{id}', [App\Http\Controllers\Web\HomeVisitController::class, 'payment'])->name('web.home_visits.payment');
Route::post('/home_visits/payment/{id}', [App\Http\Controllers\Web\HomeVisitController::class, 'processPayment'])->name('web.home_visits.process_payment');
Route::get('/home_visits/success/{id}', [App\Http\Controllers\Web\HomeVisitController::class, 'success'])->name('web.home_visits.success');
Route::get('/erp', [App\Http\Controllers\Web\ErpController::class, 'index'])->name('web.erp.index');
Route::get('/courses', [App\Http\Controllers\Web\CourseController::class, 'index'])->name('web.courses.index');
Route::get('/courses/{id}', [App\Http\Controllers\Web\CourseController::class, 'show'])->name('web.courses.show');
Route::post('/courses/{id}/purchase', [App\Http\Controllers\Web\CourseController::class, 'purchase'])->name('web.courses.purchase');

// Google Merchant feeds (separate feeds per language)
Route::get('/google-merchant-{lang}.xml', [FeedController::class, 'google'])
    ->where('lang', 'en|ar')
    ->name('feeds.google');


Route::get('/run-privacy-policy-seeder', function () {
    Artisan::call('db:seed', [
        '--class' => 'DatabaseSeeder',
        '--force' => true,
    ]);

    return 'Privacy Policies seeder has been run!';
});

Route::get('/callback', [OrderController::class, 'callback']);
Route::controller(SocialLoginController::class)->prefix('auth')->as('auth.social.')->group(function(){
    Route::get('{provider}/redirect','redirect')->name('redirect');
    Route::get('{provider}/callback','callback')->name('callback');

});

// Clinic Dashboard Routes (Outside Access)
    Route::group(['prefix' => 'clinic', 'as' => 'clinic.', 'middleware' => ['auth']], function () {
        // Specialty Selection (must be before dashboard to catch first-time users)
        Route::get('/specialty-selection', [\App\Http\Controllers\Clinic\SpecialtySelectionController::class, 'show'])->name('specialty-selection.show');
        Route::post('/specialty-selection', [\App\Http\Controllers\Clinic\SpecialtySelectionController::class, 'store'])->name('specialty-selection.store');
        
        Route::get('/dashboard', [\App\Http\Controllers\Clinic\DashboardController::class, 'index'])->name('dashboard');
        
        // Episodes & Clinical Care (New ERP)
        Route::resource('episodes', \App\Http\Controllers\Clinic\EpisodeController::class);
        Route::resource('episodes.assessments', \App\Http\Controllers\Clinic\AssessmentController::class);
        
        Route::get('/doctors', [\App\Http\Controllers\Clinic\DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [\App\Http\Controllers\Clinic\DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [\App\Http\Controllers\Clinic\DoctorController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{id}', [\App\Http\Controllers\Clinic\DoctorController::class, 'show'])->name('doctors.show');
        
        Route::get('/departments', [\App\Http\Controllers\Clinic\DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [\App\Http\Controllers\Clinic\DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [\App\Http\Controllers\Clinic\DepartmentController::class, 'store'])->name('departments.store');
        
        Route::get('/staff', [\App\Http\Controllers\Clinic\StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [\App\Http\Controllers\Clinic\StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [\App\Http\Controllers\Clinic\StaffController::class, 'store'])->name('staff.store');
        
        Route::get('/analytics', [\App\Http\Controllers\Clinic\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/billing', [\App\Http\Controllers\Clinic\BillingController::class, 'index'])->name('billing.index');
        
        // Insurance Claims (RCM)
        Route::post('/insurance-claims/{id}/submit', [\App\Http\Controllers\Clinic\InsuranceClaimController::class, 'submit'])->name('insurance-claims.submit');
        Route::post('/insurance-claims/batch-submit', [\App\Http\Controllers\Clinic\InsuranceClaimController::class, 'batchSubmit'])->name('insurance-claims.batchSubmit');
        Route::post('/appointments/{id}/create-claim', [\App\Http\Controllers\Clinic\InsuranceClaimController::class, 'createFromAppointment'])->name('insurance-claims.createFromAppointment');
        Route::resource('insurance-claims', \App\Http\Controllers\Clinic\InsuranceClaimController::class);
        
        Route::get('/notifications', [\App\Http\Controllers\Clinic\NotificationController::class, 'index'])->name('notifications.index');
        
        Route::get('/profile', [\App\Http\Controllers\Clinic\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Clinic\ProfileController::class, 'update'])->name('profile.update');

        // Existing Resources (keep if needed, or replace)
        Route::resource('patients', \App\Http\Controllers\Clinic\PatientController::class);
        
        // Appointments with specialty fields
        Route::get('/appointments/specialty-fields', [\App\Http\Controllers\Clinic\AppointmentController::class, 'getSpecialtyFields'])->name('appointments.specialtyFields');
        Route::post('/appointments/calculate-price', [\App\Http\Controllers\Clinic\AppointmentController::class, 'calculatePrice'])->name('appointments.calculatePrice');
        Route::get('/appointments/available-slots', [\App\Http\Controllers\Clinic\AppointmentController::class, 'getAvailableSlots'])->name('appointments.availableSlots');
        Route::resource('appointments', \App\Http\Controllers\Clinic\AppointmentController::class);
        Route::resource('plans', \App\Http\Controllers\Clinic\TreatmentPlanController::class);
        Route::resource('invoices', \App\Http\Controllers\Clinic\InvoiceController::class);
        
        // Weekly Programs
        Route::get('/programs/get-template', [\App\Http\Controllers\Clinic\WeeklyProgramController::class, 'getTemplate'])->name('programs.getTemplate');
        Route::post('/programs/calculate-price', [\App\Http\Controllers\Clinic\WeeklyProgramController::class, 'calculatePrice'])->name('programs.calculatePrice');
        Route::post('/programs/{id}/activate', [\App\Http\Controllers\Clinic\WeeklyProgramController::class, 'activate'])->name('programs.activate');
        Route::resource('programs', \App\Http\Controllers\Clinic\WeeklyProgramController::class);
        
        // Clinical Notes (EMR)
        Route::get('/clinical-notes/templates', [\App\Http\Controllers\Clinic\ClinicalNoteController::class, 'getTemplates'])->name('clinical-notes.templates');
        Route::post('/clinical-notes/validate-coding', [\App\Http\Controllers\Clinic\ClinicalNoteController::class, 'validateCoding'])->name('clinical-notes.validateCoding');
        Route::post('/clinical-notes/{id}/sign', [\App\Http\Controllers\Clinic\ClinicalNoteController::class, 'sign'])->name('clinical-notes.sign');
        Route::resource('clinical-notes', \App\Http\Controllers\Clinic\ClinicalNoteController::class);
        
        // Waitlist Management
        Route::get('/waitlist/position', [\App\Http\Controllers\Clinic\WaitlistController::class, 'getPosition'])->name('waitlist.position');
        Route::resource('waitlist', \App\Http\Controllers\Clinic\WaitlistController::class);
        
        // Intake Forms
        Route::post('/intake-forms/{id}/toggle', [\App\Http\Controllers\Clinic\IntakeFormController::class, 'toggleActive'])->name('intake-forms.toggle');
        Route::resource('intake-forms', \App\Http\Controllers\Clinic\IntakeFormController::class);
        Route::get('/jobs/{id}/applicants', [\App\Http\Controllers\Clinic\JobController::class, 'applicants'])->name('jobs.applicants');
        Route::resource('jobs', \App\Http\Controllers\Clinic\JobController::class);
    });

    // Company Dashboard Routes
    Route::group(['prefix' => 'company', 'as' => 'company.', 'middleware' => ['auth']], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Company\DashboardController::class, 'index'])->name('dashboard');
        
        // Job routes
        Route::get('/jobs/{id}/applicants', [\App\Http\Controllers\Company\JobController::class, 'applicants'])->name('jobs.applicants');
        Route::post('/jobs/{jobId}/applications/{applicationId}/status', [\App\Http\Controllers\Company\JobController::class, 'updateApplicationStatus'])->name('jobs.updateApplicationStatus');
        Route::post('/jobs/{jobId}/applications/bulk-update', [\App\Http\Controllers\Company\JobController::class, 'bulkUpdateApplications'])->name('jobs.bulkUpdateApplications');
        Route::post('/jobs/{jobId}/applications/{applicationId}/schedule-interview', [\App\Http\Controllers\Company\JobController::class, 'scheduleInterview'])->name('jobs.scheduleInterview');
        
        // Job templates
        Route::get('/jobs/templates', [\App\Http\Controllers\Company\JobController::class, 'templates'])->name('jobs.templates');
        Route::post('/jobs/templates', [\App\Http\Controllers\Company\JobController::class, 'createTemplate'])->name('jobs.createTemplate');
        Route::get('/jobs/templates/{templateId}/create-job', [\App\Http\Controllers\Company\JobController::class, 'createFromTemplate'])->name('jobs.createFromTemplate');
        
        // Analytics
        Route::get('/jobs/analytics', [\App\Http\Controllers\Company\JobController::class, 'analytics'])->name('jobs.analytics');
        
        Route::resource('jobs', \App\Http\Controllers\Company\JobController::class);
    });

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/therapist.php';