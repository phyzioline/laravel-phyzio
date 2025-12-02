<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TearmsCondition\TearmsConditionRequest;
use App\Services\Dashboard\TearmsConditionService;
use Illuminate\Support\Facades\Session;

class TearmsConditionController extends Controller
{
    public function __construct(public TearmsConditionService $TearmsConditionService)
    {}

    public function show()
    {
        return $this->TearmsConditionService->show();
    }

    public function update(TearmsConditionRequest $request)
    {
        $this->TearmsConditionService->update($request->validated());
        Session::flash('message', ['type' => 'success', 'text' => __('TearmsConditions updated successfully!')]);
        return redirect()->back();
    }
}
