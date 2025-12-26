<?php
namespace App\Services\Web;

use App\Exceptions\InsuranceNotFoundException;
use App\Mail\OTPEmail;
use App\Models\User;
use App\Traits\HasImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisterService
{
    use HasImage;

    public function __construct(public User $model)
    {}

    public function regiser_view()
    {
        return view('web.auth.register');
    }

    public function register($data)
    {
        // Process all files FIRST before email (to prevent temp file cleanup)
        try {
            $this->handleFileUploads($data);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Registration File Upload Error: ' . $e->getMessage());
            Session::flash('message', ['type' => 'error', 'text' => __('File upload error: ') . $e->getMessage()]);
            throw $e; // Re-throw to be handled by controller or global handler, or handled here if we want to return redirect
        }

        $data['password']  = Hash::make($data['password']);
        $data['code']      = rand(1000, 9999);
        $data['expire_at'] = Carbon::now()->addMinutes(5);

        Mail::to($data['email'])->send(new OTPEmail($data['code']));

        Session::put('email', $data['email']);

        $user = $this->model->create($data);
        
        if ($data['type'] === 'vendor') {
            $user->assignRole('vendor');
        }
        if ($data['type'] === 'buyer') {
            $user->update([
                'status' => 'active'
            ]);
        }
        if ($data['type'] === 'therapist') {
            $user->assignRole('therapist');
            \App\Models\TherapistProfile::create([
                'user_id' => $user->id,
                'license_document' => $data['license_document'] ?? null,
                'id_document' => $data['id_document'] ?? null,
                'status' => 'pending',
            ]);
        }
        if ($data['type'] === 'company') {
            $user->assignRole('company'); // Assuming role 'company' exists or will be created
            $user->update(['status' => 'inactive']); // Require approval
        }

        return $user;
    }

    // public function verify($data)
    // {

    //     $user = User::where('email', $data['email'])->first();

    //     if (! $user) {
    //         throw new InsuranceNotFoundException(__('Email not registered', [], request()->header('Accept-language')), 400);

    //     }

    //     if ($user->email_verified_at) {
    //         throw new InsuranceNotFoundException(__('The user account has already been verified', [], request()->header('Accept-language')), 400);
    //     }

    //     if ($user->code !== $data['otp']) {
    //         return redirect()->back();
    //     }

    //     if (Carbon::parse($user->expire_at)->lt(Carbon::now())) {
    //         throw new InsuranceNotFoundException(__('The OTP code has expired', [], request()->header('Accept-language')), 400);
    //     }

    //     // $token = $user->createToken("API TOKEN")->plainTextToken;
    //     $user->update([
    //         'email_verified_at' => Carbon::now(),
    //         'code'              => null,
    //         'expire_at'         => null,
    //     ]);

    //     Auth::login($user);

    //     return $user;
    // }

//        public function verify(Request $request)
// {
//     $data = $request->all();

//     $user = User::where('email', $data['email'])->first();

//     if (! $user) {
//         return back()->withErrors(['email' => __('Email not registered')]);
//     }

//     if ($user->email_verified_at) {
//         return back()->withErrors(['email' => __('The user account has already been verified')]);
//     }

//     if ($user->code !== $data['otp']) {
//         return back()->withErrors(['otp' => __('Wrong OTP code')]);
//     }

//     if (Carbon::parse($user->expire_at)->lt(Carbon::now())) {
//         return back()->withErrors(['otp' => __('The OTP code has expired')]);
//     }

//     // $token = $user->createToken("API TOKEN")->plainTextToken;
//     $user->update([
//         'email_verified_at' => Carbon::now(),
//         'code'              => null,
//         'expire_at'         => null,
//     ]);

//     Auth::login($user);

//     return redirect()->route('view_login');
// }

}
