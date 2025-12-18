<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Setting\SettingRequest;
use App\Services\Dashboard\SettingService;
use Illuminate\Support\Facades\Session;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:setting-update'),
        ];
    }

    public function __construct(public SettingService $settingService)
    {
    }

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
