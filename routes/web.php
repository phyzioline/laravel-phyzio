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
    Route::post('/verify', [RegisterController::class, 'verify'])->name("verify.{$locale}");

        // Payment gateway webhooks (generic)
        Route::post('/webhooks/payments/{provider}', [\App\Http\Controllers\Web\PaymentWebhookController::class, 'handle'])->name('webhooks.payments');
        
        // Shipping provider webhooks (Bosta, Aramex, DHL, etc.)
        Route::post('/webhooks/shipping/{provider}', [\App\Http\Controllers\Web\ShippingWebhookController::class, 'handle'])->name('webhooks.shipping');
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

     // Currency Switcher
     Route::post('/currency/switch', function (\Illuminate\Http\Request $request) {
         $currency = $request->input('currency');
         if (array_key_exists($currency, config('currency.currencies'))) {
             session(['currency' => $currency]);
         }
         return redirect()->back();
     })->name('currency.switch');

     Route::get('tearms_condition',[TearmsConditionController::class, 'index'])->name("tearms_condition.index.{$locale}");
        
        Route::get('/shop/search', [ShowController::class, 'search'])->name('web.shop.search');
     
          Route::get('/shop/subcategory/{id}', [ShowController::class, 'ProductBySubCategory'])->name('web.shop.category');
        
        Route::get('/jobs', [App\Http\Controllers\Web\JobController::class, 'index'])->name('web.jobs.index');
        Route::get('/jobs/{id}', [App\Http\Controllers\Web\JobController::class, 'show'])->name('web.jobs.show');
        Route::post('/jobs/{id}/apply', [App\Http\Controllers\Web\JobController::class, 'apply'])->name('web.jobs.apply');

        // Data Hub
        Route::get('/data-hub', [App\Http\Controllers\Web\DataHubController::class, 'index'])->name('web.datahub.index');
        Route::get('/data-hub/dashboard', [App\Http\Controllers\Web\DataHubController::class, 'dashboard'])->name('web.datahub.dashboard');
        Route::get('/data-hub/licensing', [App\Http\Controllers\Web\DataHubController::class, 'licensing'])->name('web.datahub.licensing');


        // Authenticated routes
    Route::group(['middleware' => ['auth']], function () {
        Route::resources([
            'carts' => CartController::class ,
        ]);

        Route::post('/products/{id}/reviews', [App\Http\Controllers\Web\ProductReviewController::class, 'store'])->name('web.products.reviews.store');

        // Generic Profile Routes (Vendor, Buyer, Patient)
        Route::get('/profile', [App\Http\Controllers\Web\ProfileController::class, 'index'])->name('web.profile.index');
        Route::put('/profile', [App\Http\Controllers\Web\ProfileController::class, 'update'])->name('web.profile.update');

        // Vendor Dashboard Routes (Amazon-style multi-vendor)
        Route::prefix('vendor')->name('vendor.')->middleware(App\Http\Middleware\VendorMiddleware::class)->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/orders', [App\Http\Controllers\Vendor\VendorOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{id}', [App\Http\Controllers\Vendor\VendorOrderController::class, 'show'])->name('orders.show');
            Route::get('/shipments', [App\Http\Controllers\Vendor\VendorShippingController::class, 'index'])->name('shipments.index');
            Route::get('/shipments/{id}', [App\Http\Controllers\Vendor\VendorShippingController::class, 'show'])->name('shipments.show');
            Route::post('/shipments/{id}', [App\Http\Controllers\Vendor\VendorShippingController::class, 'update'])->name('shipments.update');
            Route::get('/wallet', [App\Http\Controllers\Vendor\VendorWalletController::class, 'index'])->name('wallet');
            Route::post('/wallet/payout', [App\Http\Controllers\Vendor\VendorWalletController::class, 'requestPayout'])->name('wallet.payout');
        });

        // Instructor Routes
        Route::prefix('instructor')->name('instructor.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name('dashboard');
            
            // Course Wizard Routes (Specific routes first)
            Route::get('/courses/create', [App\Http\Controllers\Instructor\CourseController::class, 'create'])->name('courses.create');
            Route::post('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'store'])->name('courses.store');

            // Course Management (Wildcard routes last)
            Route::get('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'index'])->name('courses.index');
            Route::get('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'show'])->name('courses.show');
            Route::get('/courses/{course}/edit', [App\Http\Controllers\Instructor\CourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'update'])->name('courses.update');
            Route::delete('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'destroy'])->name('courses.destroy');
            
            // Modules & Units
            Route::post('/courses/{course}/modules', [App\Http\Controllers\Instructor\CourseController::class, 'storeModule'])->name('courses.modules.store');
            Route::post('/courses/{course}/modules/{module}/units', [App\Http\Controllers\Instructor\CourseController::class, 'storeUnit'])->name('courses.modules.units.store');
        });

        // --- Home Visit System Routes ---
        
        // Patient Flow
        Route::get('/home_visits/request', [App\Http\Controllers\Web\PatientVisitController::class, 'create'])->name('patient.home_visits.create');
        Route::post('/home_visits/request', [App\Http\Controllers\Web\PatientVisitController::class, 'store'])->name('patient.home_visits.store');
        Route::get('/home_visits/status/{id}', [App\Http\Controllers\Web\PatientVisitController::class, 'show'])->name('patient.home_visits.show');

        // Therapist Flow (Legacy - Consolidated into therapist.php)
        // Route::get('/therapist/visits', [App\Http\Controllers\Therapist\VisitManagementController::class, 'index'])->name('therapist.visits.index');
        // Route::post('/therapist/visits/{visit}/accept', [App\Http\Controllers\Therapist\VisitManagementController::class, 'accept'])->name('therapist.visits.accept');
        // Route::post('/therapist/visits/{visit}/status', [App\Http\Controllers\Therapist\VisitManagementController::class, 'updateStatus'])->name('therapist.visits.status');
        // Route::post('/therapist/visits/{visit}/complete', [App\Http\Controllers\Therapist\VisitManagementController::class, 'complete'])->name('therapist.visits.complete');

        // Feed Routes (Public)
        Route::get('/feed', [App\Http\Controllers\Web\FeedController::class, 'index'])->name('feed.index');
        Route::post('/feed', [App\Http\Controllers\Web\FeedController::class, 'store'])->name('feed.store'); // New
        Route::post('/feed/{id}/interact', [App\Http\Controllers\Web\FeedController::class, 'logInteraction'])->name('feed.interact');
        Route::post('/feed/{id}/like', [App\Http\Controllers\Web\FeedController::class, 'toggleLike'])->name('feed.like');

        // Admin Feed Management
        Route::prefix('admin')->name('admin.')->group(function(){
            Route::resource('feed', App\Http\Controllers\Admin\FeedController::class);
        });

        // Clinic ERP Routes
        Route::prefix('clinic')->name('clinic.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Clinic\DashboardController::class, 'index'])->name('dashboard');
            
            // Episodes & Clinical Care (New ERP)
            Route::resource('episodes', App\Http\Controllers\Clinic\EpisodeController::class);
            Route::resource('episodes.assessments', App\Http\Controllers\Clinic\AssessmentController::class);
            
            Route::resource('patients', App\Http\Controllers\Clinic\PatientController::class);
            Route::resource('appointments', App\Http\Controllers\Clinic\AppointmentController::class);
            Route::resource('plans', App\Http\Controllers\Clinic\TreatmentPlanController::class);
            Route::resource('invoices', App\Http\Controllers\Clinic\InvoiceController::class);
        });
        
          Route::get('complecet_info_view',[LoginController::class, 'complecet_info_view'])->name('complecet_info_view');
        Route::post('complecet_info',[LoginController::class, 'complecet_info'])->name('complecet_info');

        Route::put('update_cart/{id}', [CartController::class, 'update_carts']);
        Route::get('total',[CartController::class, 'total'])->name('carts.total');
        Route::get('flush',[CartController::class, 'flush'])->name('carts.flush');
        Route::post('store',[OrderController::class, 'store'])->name('order.store');
        Route::get('favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('favorites', [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.delete');
        Route::get('compare', [App\Http\Controllers\Web\CompareController::class, 'index'])->name('compare.index');
        Route::post('compare', [App\Http\Controllers\Web\CompareController::class, 'store'])->name('compare.store');
        Route::delete('/compare/{id}', [App\Http\Controllers\Web\CompareController::class, 'destroy'])->name('compare.delete');
        Route::get('history_order',[HistoryOrderController::class, 'index'])->name('history_order.index');

            // logout
            Route::get('logout', [LoginController::class, 'logout'])->name('logout');
            
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
                } else {
                    return redirect()->route('history_order.index');
                }
            })->name('dashboard');
        });
        
    }); // End of locale route group
} // End of foreach loop

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
        Route::get('/dashboard', [\App\Http\Controllers\Clinic\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/doctors', [\App\Http\Controllers\Clinic\DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [\App\Http\Controllers\Clinic\DoctorController::class, 'create'])->name('doctors.create');
        Route::get('/doctors/{id}', [\App\Http\Controllers\Clinic\DoctorController::class, 'show'])->name('doctors.show');
        
        Route::get('/departments', [\App\Http\Controllers\Clinic\DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [\App\Http\Controllers\Clinic\DepartmentController::class, 'create'])->name('departments.create');
        
        Route::get('/staff', [\App\Http\Controllers\Clinic\StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [\App\Http\Controllers\Clinic\StaffController::class, 'create'])->name('staff.create');
        
        Route::get('/analytics', [\App\Http\Controllers\Clinic\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/billing', [\App\Http\Controllers\Clinic\BillingController::class, 'index'])->name('billing.index');
        Route::get('/notifications', [\App\Http\Controllers\Clinic\NotificationController::class, 'index'])->name('notifications.index');
        
        Route::get('/profile', [\App\Http\Controllers\Clinic\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [\App\Http\Controllers\Clinic\ProfileController::class, 'update'])->name('profile.update');

        // Existing Resources (keep if needed, or replace)
        Route::resource('patients', \App\Http\Controllers\Clinic\PatientController::class);
        Route::resource('appointments', \App\Http\Controllers\Clinic\AppointmentController::class);
        Route::resource('plans', \App\Http\Controllers\Clinic\TreatmentPlanController::class);
        Route::resource('invoices', \App\Http\Controllers\Clinic\InvoiceController::class);
        Route::get('/jobs/{id}/applicants', [\App\Http\Controllers\Clinic\JobController::class, 'applicants'])->name('jobs.applicants');
        Route::resource('jobs', \App\Http\Controllers\Clinic\JobController::class);
    });

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/therapist.php';