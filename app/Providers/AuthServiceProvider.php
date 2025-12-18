<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Payment;
use App\Models\VendorPayment;
use App\Policies\PaymentPolicy;
use App\Policies\VendorPaymentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Payment::class => PaymentPolicy::class,
        VendorPayment::class => VendorPaymentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates if needed
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
                return true;
            }
        });
    }
}
