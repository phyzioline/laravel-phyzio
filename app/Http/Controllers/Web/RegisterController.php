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
        return redirect()->route('view_otp')->with('success', __('Registration successful!'));
    }

    public function otp()
    {
        return view('web.auth.otp');
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

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => __('Email not registered')]);
        }

        if ($user->email_verified_at) {
            return back()->withErrors(['email' => __('The user account has already been verified')]);
        }

        if ($user->code !== $data['otp']) {
            return back()->withErrors(['otp' => __('Wrong OTP code')]);
        }

        if (Carbon::parse($user->expire_at)->lt(Carbon::now())) {
            return back()->withErrors(['otp' => __('The OTP code has expired')]);
        }

        // $token = $user->createToken("API TOKEN")->plainTextToken;
        $user->update([
            'email_verified_at' => Carbon::now(),
            'code'              => null,
            'expire_at'         => null,
        ]);

        Auth::login($user);

        // Redirect based on user type
        if ($user->type === 'buyer') {
            return redirect()->route('home.' . app()->getLocale())->with('success', __('Welcome! Your account has been verified.'));
        } else {
            // Vendor, Company, Therapist go to "Complete Your Account" page
            return redirect()->route('verification.complete-account')->with('success', __('Welcome! Please complete your account verification.'));
        }
    }

}
