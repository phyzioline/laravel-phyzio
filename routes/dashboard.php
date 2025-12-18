<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\TearmsConditionController;
use App\Http\Controllers\Dashboard\SubCategoryController;
use App\Http\Controllers\Dashboard\ShippingPolicyController;
use App\Http\Controllers\Dashboard\PrivacyPolicyController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::get('/dashboard/login', [AuthController::class, 'login'])->name('dashboard.login');
        Route::post('/dashboard/login', [AuthController::class, 'loginAction'])->name('loginAction');

        Route::group(['middleware' => ['auth', 'notification', 'admin'], 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/home', [HomeController::class, 'index'])->name('home');
            Route::resource('roles', RoleController::class);
            Route::resource('users', UserController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('sub_categories', SubCategoryController::class);
            Route::resource('tags', TagController::class);
            Route::get('products/export/{format?}', [ProductController::class, 'export'])->name('products.export');
            Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
            Route::resource('products', ProductController::class);
            Route::resource('orders', OrderController::class);
            Route::get('orders/{id}/print-label', [OrderController::class, 'printLabel'])->name('orders.print-label');
            Route::get('order_cash', [OrderController::class, 'orderCash'])->name('order_cash');

            // Ecosystem Management
            Route::resource('therapist_profiles', \App\Http\Controllers\Dashboard\TherapistProfileController::class);
            Route::resource('appointments', \App\Http\Controllers\Dashboard\AppointmentController::class);
            Route::resource('clinic_profiles', \App\Http\Controllers\Dashboard\ClinicProfileController::class);
            Route::resource('courses', \App\Http\Controllers\Dashboard\CourseController::class);
            Route::resource('jobs', \App\Http\Controllers\Dashboard\JobController::class);
            Route::resource('data_points', \App\Http\Controllers\Dashboard\DataPointController::class);

            // Financial Management
            Route::get('/payments', [\App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/{id}', [\App\Http\Controllers\Dashboard\PaymentController::class, 'showVendorPayment'])->name('payments.show');
            Route::post('/payments/{id}/status', [\App\Http\Controllers\Dashboard\PaymentController::class, 'updateStatus'])->name('payments.update-status');
            Route::get('/payments/{id}/detail', [\App\Http\Controllers\Dashboard\PaymentController::class, 'detail'])->name('payments.detail');

            // Inventory Management
            Route::prefix('inventory')->as('inventory.')->group(function () {
                Route::get('/manage', [\App\Http\Controllers\Dashboard\InventoryController::class, 'manage'])->name('manage');
                Route::get('/stock-levels', [\App\Http\Controllers\Dashboard\InventoryController::class, 'stockLevels'])->name('stock-levels');
                Route::get('/reports', [\App\Http\Controllers\Dashboard\InventoryController::class, 'reports'])->name('reports');
                Route::post('/update-stock', [\App\Http\Controllers\Dashboard\InventoryController::class, 'updateStock'])->name('update-stock');
            });

            // Pricing Management
            Route::prefix('pricing')->as('pricing.')->group(function () {
                Route::get('/manage', [\App\Http\Controllers\Dashboard\PricingController::class, 'manage'])->name('manage');
                Route::get('/rules', [\App\Http\Controllers\Dashboard\PricingController::class, 'rules'])->name('rules');
                Route::post('/update-price', [\App\Http\Controllers\Dashboard\PricingController::class, 'updatePrice'])->name('update-price');
            });

            // Business Reports & Analytics
            Route::prefix('reports')->as('reports.')->group(function () {
                Route::get('/sales-dashboard', [\App\Http\Controllers\Dashboard\ReportsController::class, 'salesDashboard'])->name('sales-dashboard');
                Route::get('/sales', [\App\Http\Controllers\Dashboard\ReportsController::class, 'sales'])->name('sales');
                Route::get('/traffic', [\App\Http\Controllers\Dashboard\ReportsController::class, 'traffic'])->name('traffic');
                Route::get('/product-performance', [\App\Http\Controllers\Dashboard\ReportsController::class, 'productPerformance'])->name('product-performance');
                Route::get('/customers', [\App\Http\Controllers\Dashboard\ReportsController::class, 'customers'])->name('customers');
                Route::get('/customer-insights', [\App\Http\Controllers\Dashboard\ReportsController::class, 'customerInsights'])->name('customer-insights');
                Route::get('/orders', [\App\Http\Controllers\Dashboard\ReportsController::class, 'orders'])->name('orders');
            });


            // Settings
            Route::get('settings', [SettingController::class, 'show'])->name('settings.show');
            Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

            // Shipping Policy
            Route::get('shipping_policy', [ShippingPolicyController::class, 'show'])->name('shipping_policy.show');
            Route::post('shipping_policy', [ShippingPolicyController::class, 'update'])->name('shipping_policy.update');
          
               // Tearms Condition
            Route::get('tearms_conditions', [TearmsConditionController::class, 'show'])->name('tearms_conditions.show');
            Route::post('tearms_conditions', [TearmsConditionController::class, 'update'])->name('tearms_conditions.update');
                // Privacy Policies
            Route::get('privacy_policies', [PrivacyPolicyController::class, 'show'])->name('privacy_policies.show');
            Route::post('privacy_policies', [PrivacyPolicyController::class, 'update'])->name('privacy_policies.update');


            // logout
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    }
);

