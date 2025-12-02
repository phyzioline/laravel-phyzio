<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ShippingPolicy\ShippingPolicyRequest;
use App\Services\Dashboard\ShippingPolicyService;
use Illuminate\Support\Facades\Session;

class ShippingPolicyController extends Controller
{
    public function __construct(public ShippingPolicyService $shippingPolicyService)
    {}

    public function show()
    {
        return $this->shippingPolicyService->show();
    }

    public function update(ShippingPolicyRequest $request)
    {
        $this->shippingPolicyService->update($request->validated());
               Session::flash('success', ['type' => 'success', 'text' => __('ShippingPolicys updated successfully!')]);

        return redirect()->back();
    }
}
