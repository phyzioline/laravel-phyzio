<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Setting\SettingRequest;
use App\Services\Dashboard\SettingService;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function __construct(public SettingService $settingService)
    {}

    public function show()
    {
        return $this->settingService->show();
    }

    public function update(SettingRequest $request)
    {
        $this->settingService->update($request->validated());
        Session::flash('message', ['type' => 'success', 'text' => __('Settings updated successfully!')]);
        return redirect()->back();
    }
}
