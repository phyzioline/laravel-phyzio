<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PrivacyPolicy\PrivacyPolicyRequest;
use App\Services\Dashboard\PrivacyPolicyService;
use Illuminate\Support\Facades\Session;

class PrivacyPolicyController extends Controller
{
    public function __construct(public PrivacyPolicyService $privacyPolicyService)
    {}

    public function show()
    {
        return $this->privacyPolicyService->show();
    }

    public function update(PrivacyPolicyRequest $request)
    {
        $this->privacyPolicyService->update($request->validated());
        Session::flash('success', ['type' => 'success', 'text' => __('Privacy Policy updated successfully!')]);
        return redirect()->back();
    }
}
