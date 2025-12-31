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
use App\Http\Controllers\Dashboard\AdController;
use App\Http\Controllers\Dashboard\CrmController;
use App\Http\Controllers\Dashboard\TreatmentPlanController;
use App\Http\Controllers\Dashboard\ExerciseController;

// Dashboard routes WITHOUT locale prefix (dashboards don't need URL-based localization)
// Language is changed via session/interface only
Route::get('/dashboard/login', [AuthController::class, 'login'])->name('dashboard.login');
Route::post('/dashboard/login', [AuthController::class, 'loginAction'])->name('loginAction');

// Locale switcher for dashboard (session-based, no URL change)
Route::get('/dashboard/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('dashboard.locale.switch');

Route::group(['middleware' => ['auth', 'notification', 'admin', \App\Http\Middleware\SetDashboardLocale::class], 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            // Notifications
            Route::get('/notifications', [App\Http\Controllers\Dashboard\NotificationController::class, 'index'])->name('notifications.index');
            Route::get('/notifications/{id}/read', [App\Http\Controllers\Dashboard\NotificationController::class, 'read'])->name('notifications.read');
            Route::post('/notifications/read-all', [App\Http\Controllers\Dashboard\NotificationController::class, 'readAll'])->name('notifications.readAll');

            Route::get('/home', [HomeController::class, 'index'])->name('home');
            Route::resource('roles', RoleController::class);
            Route::resource('users', UserController::class);
            
            // Verification Management
            Route::get('/verifications', [\App\Http\Controllers\Dashboard\VerificationController::class, 'index'])->name('verifications.index');
            Route::get('/verifications/{userId}', [\App\Http\Controllers\Dashboard\VerificationController::class, 'show'])->name('verifications.show');
            Route::post('/verifications/documents/{documentId}/review', [\App\Http\Controllers\Dashboard\VerificationController::class, 'reviewDocument'])->name('verifications.review-document');
            Route::post('/verifications/users/{userId}/review', [\App\Http\Controllers\Dashboard\VerificationController::class, 'reviewUser'])->name('verifications.review-user');
            Route::post('/verifications/users/{userId}/modules/{moduleType}/review', [\App\Http\Controllers\Dashboard\VerificationController::class, 'reviewModule'])->name('verifications.review-module');
            Route::post('/verifications/users/{userId}/company-modules/{moduleType}/review', [\App\Http\Controllers\Dashboard\VerificationController::class, 'reviewCompanyModule'])->name('verifications.review-company-module');
            Route::resource('categories', CategoryController::class);
            Route::resource('sub_categories', SubCategoryController::class);
            Route::resource('tags', TagController::class);
            Route::get('products/export/{format?}', [ProductController::class, 'export'])->name('products.export');
            Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
            Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
            Route::resource('products', ProductController::class);
            Route::resource('orders', OrderController::class);
            Route::post('orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
            Route::get('orders/{id}/print-label', [OrderController::class, 'printLabel'])->name('orders.print-label');
            Route::get('orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
            Route::get('order_cash', [OrderController::class, 'orderCash'])->name('order_cash');

            // Ecosystem Management
            Route::resource('therapist_profiles', \App\Http\Controllers\Dashboard\TherapistProfileController::class);
            Route::resource('home_visits', \App\Http\Controllers\Dashboard\HomeVisitController::class);
            Route::resource('clinic_profiles', \App\Http\Controllers\Dashboard\ClinicProfileController::class);
            Route::resource('courses', \App\Http\Controllers\Dashboard\CourseController::class);
            Route::resource('jobs', \App\Http\Controllers\Dashboard\JobController::class);
            Route::resource('data_points', \App\Http\Controllers\Dashboard\DataPointController::class);

            // External Integrated Modules (Admin Only - protected by controller middleware)
            Route::get('ads/settings', [AdController::class, 'settings'])->name('ads.settings');
            Route::post('ads/account/{id}/toggle', [AdController::class, 'toggleTracking'])->name('ads.toggle');
            Route::resource('ads', AdController::class);
            
            Route::get('crm/dashboard', [CrmController::class, 'dashboard'])->name('crm.dashboard');
            Route::get('crm/contacts', [CrmController::class, 'contacts'])->name('crm.contacts');
            Route::resource('crm', CrmController::class);
            
            Route::resource('plans', TreatmentPlanController::class);
            Route::resource('exercises', ExerciseController::class);

            // Financial Management
            Route::get('/payments', [\App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('payments.index');
            Route::get('/payments/{id}', [\App\Http\Controllers\Dashboard\PaymentController::class, 'showVendorPayment'])->name('payments.show');
            Route::post('/payments/{id}/status', [\App\Http\Controllers\Dashboard\PaymentController::class, 'updateStatus'])->name('payments.update-status');
            Route::get('/payments/{id}/detail', [\App\Http\Controllers\Dashboard\PaymentController::class, 'detail'])->name('payments.detail');
            
            // Earnings Management (All Sources)
            Route::get('/earnings', [\App\Http\Controllers\Dashboard\EarningsController::class, 'index'])->name('earnings.index');
            Route::get('/earnings/{earningsTransaction}', [\App\Http\Controllers\Dashboard\EarningsController::class, 'show'])->name('earnings.show');
            Route::post('/earnings/process-settlements', [\App\Http\Controllers\Dashboard\EarningsController::class, 'processSettlements'])->name('earnings.process-settlements');
            Route::post('/earnings/{earningsTransaction}/settle', [\App\Http\Controllers\Dashboard\EarningsController::class, 'manualSettle'])->name('earnings.manual-settle');
            Route::post('/earnings/{earningsTransaction}/hold', [\App\Http\Controllers\Dashboard\EarningsController::class, 'putOnHold'])->name('earnings.put-on-hold');
            Route::post('/earnings/{earningsTransaction}/release', [\App\Http\Controllers\Dashboard\EarningsController::class, 'releaseFromHold'])->name('earnings.release-from-hold');

            // Inventory Management
            Route::prefix('inventory')->as('inventory.')->group(function () {
                Route::get('/manage', [\App\Http\Controllers\Dashboard\InventoryController::class, 'manage'])->name('manage');
                Route::get('/stock-levels', [\App\Http\Controllers\Dashboard\InventoryController::class, 'stockLevels'])->name('stock-levels');
                Route::get('/reports', [\App\Http\Controllers\Dashboard\InventoryController::class, 'reports'])->name('reports');
                Route::post('/update-stock', [\App\Http\Controllers\Dashboard\InventoryController::class, 'updateStock'])->name('update-stock');
            });

            // Vendor Shipping Management
            Route::get('shipments', [\App\Http\Controllers\Dashboard\VendorShipmentController::class, 'index'])->name('shipments.index');
            Route::get('orders/{order}/ship', [\App\Http\Controllers\Dashboard\VendorShipmentController::class, 'create'])->name('orders.ship.create');
            Route::post('orders/{order}/ship', [\App\Http\Controllers\Dashboard\VendorShipmentController::class, 'store'])->name('orders.ship.store');
            Route::post('shipments/{shipment}/track', [\App\Http\Controllers\Dashboard\VendorShipmentController::class, 'updateTracking'])->name('shipments.track');

            // Vendor Wallet (Dedicated)
            Route::get('wallet', [\App\Http\Controllers\Vendor\VendorWalletController::class, 'index'])->name('vendor.wallet');
            Route::post('wallet/payout', [\App\Http\Controllers\Vendor\VendorWalletController::class, 'requestPayout'])->name('vendor.wallet.payout');

            // Pricing Management
            Route::prefix('pricing')->as('pricing.')->group(function () {
                Route::get('/manage', [\App\Http\Controllers\Dashboard\PricingController::class, 'manage'])->name('manage');
                Route::get('/rules', [\App\Http\Controllers\Dashboard\PricingController::class, 'rules'])->name('rules');
                Route::post('/update-price', [\App\Http\Controllers\Dashboard\PricingController::class, 'updatePrice'])->name('update-price');
            });

            // Multi-Vendor Shipping Management (Admin Oversight)
            Route::prefix('shipments')->as('shipments.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Dashboard\ShipmentController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Dashboard\ShipmentController::class, 'show'])->name('show');
                Route::post('/{id}/update-status', [\App\Http\Controllers\Dashboard\ShipmentController::class, 'updateStatus'])->name('update-status');
                Route::get('/overdue/list', [\App\Http\Controllers\Dashboard\ShipmentController::class, 'overdue'])->name('overdue');
            });

            // Shipping Management System (Bosta-like)
            Route::prefix('shipping-management')->as('shipping-management.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'show'])->name('show');
                Route::post('/create', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'create'])->name('create');
                Route::post('/bulk-create', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/{id}/create-with-provider', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'createWithProvider'])->name('create-with-provider');
                Route::post('/{id}/update-status', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'updateStatus'])->name('update-status');
                Route::get('/{id}/download-label', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'downloadLabel'])->name('download-label');
                Route::get('/{id}/print-label', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'printLabel'])->name('print-label');
                Route::get('/estimate-cost', [\App\Http\Controllers\Dashboard\ShippingManagementController::class, 'estimateCost'])->name('estimate-cost');
            });

            // Vendor Payout Management (Admin Approvals)
            Route::prefix('payouts')->as('payouts.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Dashboard\PayoutController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Dashboard\PayoutController::class, 'show'])->name('show');
                Route::post('/{id}/approve', [\App\Http\Controllers\Dashboard\PayoutController::class, 'approve'])->name('approve');
                Route::post('/{id}/mark-paid', [\App\Http\Controllers\Dashboard\PayoutController::class, 'markAsPaid'])->name('mark-paid');
                Route::post('/{id}/cancel', [\App\Http\Controllers\Dashboard\PayoutController::class, 'cancel'])->name('cancel');
                Route::post('/bulk-approve', [\App\Http\Controllers\Dashboard\PayoutController::class, 'bulkApprove'])->name('bulk-approve');
                
                // Payout Settings (Admin Only)
                Route::get('/settings', [\App\Http\Controllers\Dashboard\PayoutSettingController::class, 'index'])->name('settings');
                Route::post('/settings', [\App\Http\Controllers\Dashboard\PayoutSettingController::class, 'update'])->name('settings.update');
                Route::post('/settings/trigger', [\App\Http\Controllers\Dashboard\PayoutSettingController::class, 'triggerAutoPayout'])->name('settings.trigger');
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


        
    // Debugging Route
    Route::get('/debug', [DashboardController::class, 'debug'])->name('debug');
    
    // Admin Return Management Routes
    Route::prefix('returns')->name('returns.')->group(function() {
        Route::get('/', [App\Http\Controllers\Dashboard\ReturnManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Dashboard\ReturnManagementController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [App\Http\Controllers\Dashboard\ReturnManagementController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Dashboard\ReturnManagementController::class, 'reject'])->name('reject');
        Route::post('/{id}/refund', [App\Http\Controllers\Dashboard\ReturnManagementController::class, 'refund'])->name('refund');
    });
            // logout
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
