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
            Route::resource('products', ProductController::class);
            Route::resource('orders', OrderController::class);
            Route::get('order_cash', [OrderController::class, 'orderCash'])->name('order_cash');

            // Ecosystem Management
            Route::resource('therapist_profiles', \App\Http\Controllers\Dashboard\TherapistProfileController::class);
            Route::resource('appointments', \App\Http\Controllers\Dashboard\AppointmentController::class);
            Route::resource('clinic_profiles', \App\Http\Controllers\Dashboard\ClinicProfileController::class);
            Route::resource('courses', \App\Http\Controllers\Dashboard\CourseController::class);
            Route::resource('data_points', \App\Http\Controllers\Dashboard\DataPointController::class);

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

