<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('dashboard.auth.login');
    }

    public function loginAction(LoginRequest $loginRequest)
    {
        if (Auth::attempt($loginRequest->validated())) {
            $user = Auth::user();
            if ($user->email_verified_at === null) {
                Auth::logout();
                Session::flash('message', ['type' => 'error', 'text' => __('Account not verified!')]);
                return redirect()->back();
            }

             if ($user->status === 'inactive') {
                Auth::logout();
                Session::flash('message', ['type' => 'error', 'text' => __('Account not activated!')]);
                return redirect()->back();
            }

            Session::flash('message', ['type' => 'success', 'text' => __('Welcome Home!')]);
            return redirect()->route('dashboard.home');
        }
        Session::flash('message', ['type' => 'error', 'text' => __('Invalid credentials!')]);
        return redirect()->back();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard.login');
    }
}
