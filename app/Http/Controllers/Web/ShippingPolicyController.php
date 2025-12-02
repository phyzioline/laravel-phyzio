<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\ShippingPolicy;
use App\Http\Controllers\Controller;

class ShippingPolicyController extends Controller
{
    public function index()
    {
        $shipping_policy = ShippingPolicy::first();
        return view('web.pages.shipping-policy' , compact('shipping_policy'));
    }


}
