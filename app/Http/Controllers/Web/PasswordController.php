<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
 use App\Http\Controllers\Controller;
use App\Services\Web\PasswordService;
use App\Http\Requests\Web\ForgetPasswordRequest;

class PasswordController extends Controller
{
    public function __construct(public PasswordService $passwordService){}
    public function index()
    {
        return $this->passwordService->forget_password_view();
    }

    public function store(ForgetPasswordRequest $request)
    {
       $this->passwordService->forgetPassword($request->validated());
        return redirect()->route('home.' . app()->getLocale())->with('success', __('Registration successful!'));
    }
}
