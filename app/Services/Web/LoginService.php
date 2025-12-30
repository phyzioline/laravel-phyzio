<?php
namespace App\Services\Web;

use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginService
{
    use HasImage;

    public function __construct(public User $user)
    {}

    public function login_view()
    {
        return view('web.auth.login');
    }

     public function login($data)
{
    if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            Auth::logout();
            Session::flash('message', ['type' => 'error', 'text' => __('Admins cannot log in here!')]);
            return redirect()->back();
        }

        if ($user->email_verified_at === null) {
            Auth::logout();
            Session::flash('message', ['type' => 'error', 'text' => __('Account not activated!')]);
            return redirect()->back();
        }

        // Check status for vendor, company, and therapist
        if (in_array($user->type, ['vendor', 'company', 'therapist']) && $user->status == 'inactive') {
            Auth::logout();
            Session::flash('message', ['type' => 'error', 'text' => __('Your account is inactive! Please wait for approval.')]);
            return redirect()->back();
        }

        // Handle different user types
        if ($user->type == 'vendor' || $user->type == 'buyer') {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome Home!')]);
            return redirect()->route('home.' . app()->getLocale());
        }

        // Company users - redirect to company dashboard
        if ($user->type == 'company') {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome to your dashboard!')]);
            return redirect()->route('company.dashboard');
        }

        // Therapist users - redirect to therapist dashboard
        if ($user->type == 'therapist') {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome to your dashboard!')]);
            return redirect()->route('therapist.dashboard');
        }

        // Instructor users
        if ($user->hasRole('instructor')) {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome to your dashboard!')]);
            return redirect()->route('instructor.' . app()->getLocale() . '.dashboard.' . app()->getLocale());
        }

        // Clinic users
        if ($user->hasRole('clinic')) {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome to your dashboard!')]);
            return redirect()->route('clinic.dashboard');
        }

        // Default fallback
        Session::flash('message', ['type' => 'success', 'text' => __('Welcome Home!')]);
        return redirect()->route('home.' . app()->getLocale());
    }

    Session::flash('message', ['type' => 'error', 'text' => __('The data is incorrect!')]);
    return redirect()->back();
}

    public function logout()
    {
        Auth::logout();
        return redirect()->back();
    }
    
    public function complecet_info_view()
    {
        $user = auth()->user();
        return view('web.auth.complecet_info', compact('user'));
    }

    public function complecet_info($data)
    {
        $user = auth()->user();
         if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'user');
        } else {
            $data['image'] = asset('default/default.png');
        }
        if (isset($data['account_statement'])) {
            $data['account_statement'] = $this->saveImage($data['account_statement'], 'user');
        }
        if (isset($data['commercial_register'])) {
            $data['commercial_register'] = $this->saveImage($data['commercial_register'], 'user');
        }
        if (isset($data['tax_card'])) {
            $data['tax_card'] = $this->saveImage($data['tax_card'], 'user');
        }
        if (isset($data['card_image'])) {
            $data['card_image'] = $this->saveImage($data['card_image'], 'user');
        }
        if (isset($data['license_document'])) {
            $data['license_document'] = $this->saveImage($data['license_document'], 'therapist');
        }
        if (isset($data['id_document'])) {
            $data['id_document'] = $this->saveImage($data['id_document'], 'therapist');
        }

        if ($data['type'] === 'vendor') {
            $user->assignRole('vendor');
        }
        if ($data['type'] === 'buyer') {
            $user->status = 'active';
        }
        
        if ($data['type'] === 'therapist') {
            $user->assignRole('therapist');
            // Create Therapist Profile
            \App\Models\TherapistProfile::create([
                'user_id' => $user->id,
                'license_document' => $data['license_document'] ?? null,
                'id_document' => $data['id_document'] ?? null,
                'status' => 'pending', // Default status for approval
            ]);
        }

        $user->update($data);

        return redirect()->route('home.' . app()->getLocale());
    }


}
