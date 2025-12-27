<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest;
use App\Models\User;
use App\Services\Web\RegisterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(public RegisterService $registerService)
    {}
    public function index(Request $request)
    {
        return $this->registerService->regiser_view();
    }

    public function store(RegisterRequest $request)
    {
        $this->registerService->register($request->validated());
        return redirect()->route('view_otp.' . app()->getLocale())->with('success', __('Registration successful!'));
    }

    public function otp()
    {
        // Check if registration data exists in session
        if (!\Illuminate\Support\Facades\Session::has('registration_data')) {
            return redirect()->route('view_register.' . app()->getLocale())
                ->withErrors(['error' => __('Registration session expired. Please register again.')]);
        }
        
        return view('web.auth.otp');
    }

    public function resendOtp()
    {
        $registrationData = \Illuminate\Support\Facades\Session::get('registration_data');

        if (!$registrationData) {
            return redirect()->route('view_register.' . app()->getLocale())
                ->withErrors(['error' => __('Registration session expired. Please register again.')]);
        }

        // Generate new OTP
        $newCode = rand(1000, 9999);
        $expireAt = Carbon::now()->addMinutes(5);

        // Update OTP in session
        $registrationData['code'] = $newCode;
        $registrationData['expire_at'] = $expireAt->toDateTimeString();
        \Illuminate\Support\Facades\Session::put('registration_data', $registrationData);

        // Resend OTP email
        \Illuminate\Support\Facades\Mail::to($registrationData['email'])->send(new \App\Mail\OTPEmail($newCode));

        return back()->with('success', __('OTP code has been resent to your email.'));
    }
    // public function verify(CodeRequest $codeRequest)
    // {
    //      $this->registerService->verify($codeRequest->validated());
    //     Session::flash('message', ['type' => 'success', 'text' => __('Welcome Home!')]);
    //     return redirect()->route('view_login')->with('success', __('Registration successful!'));
    // }
    public function verify(Request $request)
    {
        $data = $request->all();
        $registrationData = \Illuminate\Support\Facades\Session::get('registration_data');

        // Check if registration data exists in session
        if (!$registrationData) {
            return back()->withErrors(['email' => __('Registration session expired. Please register again.')]);
        }

        // Verify email matches
        if ($registrationData['email'] !== $data['email']) {
            return back()->withErrors(['email' => __('Email mismatch. Please use the email you registered with.')]);
        }

        // Verify OTP code
        if ($registrationData['code'] != $data['otp']) {
            return back()->withErrors(['otp' => __('Wrong OTP code')]);
        }

        // Check if OTP has expired
        $expireAt = Carbon::parse($registrationData['expire_at']);
        if ($expireAt->lt(Carbon::now())) {
            // Clear expired session data
            \Illuminate\Support\Facades\Session::forget('registration_data');
            return back()->withErrors(['otp' => __('The OTP code has expired. Please register again.')]);
        }

        // Check if user already exists (in case of race condition)
        $existingUser = User::where('email', $registrationData['email'])->first();
        if ($existingUser) {
            // Clear session and login existing user
            \Illuminate\Support\Facades\Session::forget('registration_data');
            Auth::login($existingUser);
            return redirect()->route('home.' . app()->getLocale())->with('success', __('Welcome back!'));
        }

        // Create user in database after successful OTP verification
        try {
            $user = $this->registerService->createUserAfterVerification();
            
            // Mark email as verified
            $user->update([
                'email_verified_at' => Carbon::now(),
            ]);

            Auth::login($user);

            // Redirect based on user type
            if ($user->type === 'buyer') {
                return redirect()->route('home.' . app()->getLocale())->with('success', __('Welcome! Your account has been verified.'));
            } else {
                // Vendor, Company, Therapist go to "Complete Your Account" page
                return redirect()->route('verification.complete-account')->with('success', __('Welcome! Please complete your account verification.'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('User Creation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => __('Failed to create account. Please try again.')]);
        }
    }

}
