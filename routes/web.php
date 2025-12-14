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


// Route::get('/', function () {
//     return view('welcome');
// });

// Root redirect to localized URL
Route::get('/', function () {
    return redirect(LaravelLocalization::getLocalizedURL());
});

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function(){
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

    Route::get('/register', [RegisterController::class, 'index'])
    ->name('view_register');

     Route::post('/register', [RegisterController::class, 'store'])
    ->name('register');

     Route::get('/otp', [RegisterController::class, 'otp'])->name('view_otp');
    Route::post('/verify', [RegisterController::class, 'verify'])->name('verify');

    Route::get('/login', [LoginController::class, 'index'])
    ->name('view_login');
     Route::post('/login', [LoginController::class, 'store'])
    ->name('login');

    Route::get('/forget_password', [PasswordController::class, 'index'])->name('view_forget_password');
    Route::post('/forget_password', [PasswordController::class, 'store'])->name('forget_password');
    Route::get('shop',[ShowController::class, 'show'])->name('show');
    
    Route::get('products/{id}', [ShowController::class, 'product'])->name('product.show');
        Route::get('shipping_policy',[ShippingPolicyController::class, 'index'])->name('shipping_policy.index');
     Route::get('privacy_policy',[PrivacyPolicyController::class, 'index'])->name('privacy_policy.index');

           Route::get('tearms_condition',[TearmsConditionController::class, 'index'])->name('tearms_condition.index');
        
        Route::get('/shop/search', [ShowController::class, 'search'])->name('web.shop.search');
     
          Route::get('/shop/subcategory/{id}', [ShowController::class, 'ProductBySubCategory'])->name('web.shop.category');

        // Ecosystem Routes
        Route::get('/appointments', [App\Http\Controllers\Web\AppointmentController::class, 'index'])->name('web.appointments.index');
        Route::get('/appointments/therapist/{id}', [App\Http\Controllers\Web\AppointmentController::class, 'show'])->name('web.appointments.show');
        Route::get('/appointments/book/{id}', [App\Http\Controllers\Web\AppointmentController::class, 'book'])->name('web.appointments.book');
        Route::post('/appointments/book', [App\Http\Controllers\Web\AppointmentController::class, 'store'])->name('web.appointments.store');
        Route::get('/appointments/payment/{id}', [App\Http\Controllers\Web\AppointmentController::class, 'payment'])->name('web.appointments.payment');
        Route::post('/appointments/payment/{id}', [App\Http\Controllers\Web\AppointmentController::class, 'processPayment'])->name('web.appointments.process_payment');
        Route::get('/appointments/success/{id}', [App\Http\Controllers\Web\AppointmentController::class, 'success'])->name('web.appointments.success');
        Route::get('/erp', [App\Http\Controllers\Web\ErpController::class, 'index'])->name('web.erp.index');
        Route::get('/courses', [App\Http\Controllers\Web\CourseController::class, 'index'])->name('web.courses.index');
        Route::get('/courses/{id}', [App\Http\Controllers\Web\CourseController::class, 'show'])->name('web.courses.show');
        Route::get('/jobs', [App\Http\Controllers\Web\JobController::class, 'index'])->name('web.jobs.index');
        Route::get('/jobs/{id}', [App\Http\Controllers\Web\JobController::class, 'show'])->name('web.jobs.show');
        Route::get('/jobs', [App\Http\Controllers\Web\JobController::class, 'index'])->name('web.jobs.index');
        Route::get('/jobs/{id}', [App\Http\Controllers\Web\JobController::class, 'show'])->name('web.jobs.show');
        Route::get('/data-hub', [App\Http\Controllers\Web\DataHubController::class, 'index'])->name('web.datahub.index');
        Route::get('/data-hub/dashboard', [App\Http\Controllers\Web\DataHubController::class, 'dashboard'])->name('web.datahub.dashboard');
        Route::get('/data-hub/licensing', [App\Http\Controllers\Web\DataHubController::class, 'licensing'])->name('web.datahub.licensing');




        // Authenticated routes
    Route::group(['middleware' => ['auth']], function () {
          Route::resources([
            'carts' => CartController::class ,
        ]);

        // Instructor Routes
        // Instructor Routes
        Route::prefix('instructor')->name('instructor.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name('dashboard');
            
            // Course Wizard Routes
            Route::get('/courses/create', [App\Http\Controllers\Instructor\CourseController::class, 'create'])->name('courses.create');
            Route::post('/courses', [App\Http\Controllers\Instructor\CourseController::class, 'store'])->name('courses.store');
            Route::get('/courses/{course}/edit', [App\Http\Controllers\Instructor\CourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'update'])->name('courses.update');
            Route::delete('/courses/{course}', [App\Http\Controllers\Instructor\CourseController::class, 'destroy'])->name('courses.destroy');
        });

        // Clinic ERP Routes
        Route::prefix('clinic')->name('clinic.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Clinic\DashboardController::class, 'index'])->name('dashboard');
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
        });

});



use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Web\FeedController;

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
    Route::get('{provider}/redirect','redirect')->name('redirect');
    Route::get('{provider}/callback','callback')->name('callback');
});

// Generic Dashboard Redirect
Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth' ]
], function(){
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->route('dashboard.home');
        } elseif ($user->type === 'therapist') { // Using type column based on previous context
            return redirect()->route('therapist.dashboard');
        } elseif ($user->hasRole('instructor')) { // Assuming role or type, checking previous code
            return redirect()->route('instructor.dashboard');
        } elseif ($user->hasRole('clinic')) {
            return redirect()->route('clinic.dashboard');
        } else {
            return redirect()->route('history_order.index'); // User dashboard
        }
    })->name('dashboard');
});

    // Clinic Dashboard Routes
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
        Route::get('/profile', [\App\Http\Controllers\Clinic\ProfileController::class, 'index'])->name('profile.index'); // Check if ProfileController exists or create

        // Existing Resources (keep if needed, or replace)
        Route::resource('patients', \App\Http\Controllers\Clinic\PatientController::class);
        Route::resource('appointments', \App\Http\Controllers\Clinic\AppointmentController::class);
        Route::resource('plans', \App\Http\Controllers\Clinic\TreatmentPlanController::class);
        Route::resource('invoices', \App\Http\Controllers\Clinic\InvoiceController::class);
        Route::resource('jobs', \App\Http\Controllers\Clinic\JobController::class);
        Route::resource('jobs', \App\Http\Controllers\Clinic\JobController::class);
    });

    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/therapist.php';


 