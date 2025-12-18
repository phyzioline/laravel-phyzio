<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VendorPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPaymentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, VendorPayment $vendorPayment)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $vendorPayment->vendor_id === $user->id;
    }
}
