<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest; // Reuse or extend
use App\Services\Web\RegisterService;
use Illuminate\Http\Request;

class RegisterCompanyController extends Controller
{
    public function __construct(public RegisterService $registerService)
    {}

    /**
     * Show the company registration form.
     */
    public function create()
    {
        return view('web.auth.register-company');
    }

    /**
     * Handle company registration.
     */
    public function store(RegisterRequest $request)
    {
        // Enforce type = company
        $data = $request->validated();
        $data['type'] = 'company';

        $this->registerService->register($data);

        return redirect()->route('view_login.' . app()->getLocale())->with('success', __('Registration successful! Your account is pending approval.'));
    }
}
