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

        // Set verification status and profile visibility based on user type
        if ($data['type'] === 'buyer') {
            // Buyers are auto-approved and visible
            $data['verification_status'] = 'approved';
            $data['profile_visibility'] = 'visible';
            $data['status'] = 'active';
        } else {
            // Vendor, Company, Therapist need verification
            $data['verification_status'] = 'pending';
            $data['profile_visibility'] = 'hidden';
            $data['status'] = 'inactive';
        }

        Mail::to($data['email'])->send(new OTPEmail($data['code']));

        Session::put('email', $data['email']);

        $user = $this->model->create($data);
        
        if ($data['type'] === 'vendor') {
            $user->assignRole('vendor');
        }
        if ($data['type'] === 'therapist') {
            $user->assignRole('therapist');
            \App\Models\TherapistProfile::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }
        if ($data['type'] === 'company') {
            // Ensure company role exists before assigning
            if (!\Spatie\Permission\Models\Role::where('name', 'company')->where('guard_name', 'web')->exists()) {
                \Spatie\Permission\Models\Role::create(['name' => 'company', 'guard_name' => 'web']);
            }
            $user->assignRole('company');
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

    private function handleFileUploads(&$data)
    {
        // Only handle profile image during registration
        // Documents will be uploaded later in verification center
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        } else {
            $data['image'] = asset('default/default.png');
        }
        
        // Remove document fields from data (they're no longer in the form)
        unset($data['account_statement']);
        unset($data['commercial_register']);
        unset($data['tax_card']);
        unset($data['card_image']);
        unset($data['license_document']);
        unset($data['id_document']);
    }
}
