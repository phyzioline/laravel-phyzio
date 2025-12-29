<?php
namespace App\Services\Web;

use App\Exceptions\InsuranceNotFoundException;
use App\Mail\OTPEmail;
use App\Mail\CompanyWelcomeEmail;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\UserDocument;
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
            $uploadedFiles = $this->handleFileUploads($data);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Registration File Upload Error: ' . $e->getMessage());
            Session::flash('message', ['type' => 'error', 'text' => __('File upload error: ') . $e->getMessage()]);
            throw $e; // Re-throw to be handled by controller or global handler, or handled here if we want to return redirect
        }

        // Generate OTP code
        $code = rand(1000, 9999);
        $expire_at = Carbon::now()->addMinutes(5);

        // Store registration data in session (NOT in database yet)
        Session::put('registration_data', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']), // Hash password before storing in session
            'type' => $data['type'],
            'country' => $data['country'] ?? null,
            'image' => $data['image'] ?? asset('default/default.png'),
            'code' => $code,
            'expire_at' => $expire_at->toDateTimeString(),
            'uploaded_files' => $uploadedFiles ?? [], // Store uploaded document files
        ]);

        // Send OTP email
        Mail::to($data['email'])->send(new OTPEmail($code));

        // Store email in session for OTP page
        Session::put('email', $data['email']);

        return true; // Return true instead of user object
    }

    /**
     * Create user after successful OTP verification
     */
    public function createUserAfterVerification()
    {
        $registrationData = Session::get('registration_data');

        if (!$registrationData) {
            throw new \Exception('Registration data not found in session');
        }

        // Clean up any unverified users with the same email (from previous failed attempts)
        User::where('email', $registrationData['email'])
            ->whereNull('email_verified_at')
            ->delete();

        // Set verification status and profile visibility based on user type
        if ($registrationData['type'] === 'buyer') {
            // Buyers are auto-approved and visible
            $registrationData['verification_status'] = 'approved';
            $registrationData['profile_visibility'] = 'visible';
            $registrationData['status'] = 'active';
        } else {
            // Vendor, Company, Therapist need verification
            $registrationData['verification_status'] = 'pending';
            $registrationData['profile_visibility'] = 'hidden';
            $registrationData['status'] = 'inactive';
        }

        // Remove OTP-related data before creating user
        $code = $registrationData['code'];
        $expire_at = $registrationData['expire_at'];
        unset($registrationData['code']);
        unset($registrationData['expire_at']);

        // Get uploaded files from session before creating user
        $uploadedFiles = $registrationData['uploaded_files'] ?? [];
        unset($registrationData['uploaded_files']);

        // Create user in database
        $user = $this->model->create($registrationData);
        
        // Assign roles based on user type
        if ($registrationData['type'] === 'vendor') {
            $user->assignRole('vendor');
        }
        if ($registrationData['type'] === 'therapist') {
            $user->assignRole('therapist');
            \App\Models\TherapistProfile::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }
        if ($registrationData['type'] === 'company') {
            // Ensure company role exists before assigning
            if (!\Spatie\Permission\Models\Role::where('name', 'company')->where('guard_name', 'web')->exists()) {
                \Spatie\Permission\Models\Role::create(['name' => 'company', 'guard_name' => 'web']);
            }
            $user->assignRole('company');

            // Create company profile
            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $user->name,
                'status' => 'active',
            ]);

            // Save company registration documents as UserDocument records
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $documentType => $filePath) {
                    UserDocument::create([
                        'user_id' => $user->id,
                        'document_type' => $documentType,
                        'file_path' => $filePath,
                        'status' => 'uploaded', // Will be reviewed by admin
                    ]);
                }

                // Update verification status to 'under_review' when documents are uploaded
                if ($user->verification_status === 'pending') {
                    $user->update(['verification_status' => 'under_review']);
                }
            }

            // Send welcome email
            Mail::to($user->email)->send(new CompanyWelcomeEmail($user));
        }

        // Clear registration data from session
        Session::forget('registration_data');

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
        $uploadedFiles = [];
        
        // Handle profile image during registration
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        } else {
            $data['image'] = asset('default/default.png');
        }
        
        // Handle company documents during registration
        if (isset($data['type']) && $data['type'] === 'company') {
            if (isset($data['commercial_register'])) {
                $filePath = $this->saveImage($data['commercial_register'], 'documents');
                $uploadedFiles['commercial_register'] = $filePath;
            }
            if (isset($data['tax_card'])) {
                $filePath = $this->saveImage($data['tax_card'], 'documents');
                $uploadedFiles['tax_card'] = $filePath;
            }
        }
        
        // Remove document fields from data (they're stored separately)
        unset($data['account_statement']);
        unset($data['commercial_register']);
        unset($data['tax_card']);
        unset($data['card_image']);
        unset($data['license_document']);
        unset($data['id_document']);
        
        return $uploadedFiles;
    }
}
