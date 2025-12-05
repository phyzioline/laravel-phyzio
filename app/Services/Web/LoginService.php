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

        if ($user->type == 'vendor' && $user->status == 'inactive') {
            Auth::logout();
            Session::flash('message', ['type' => 'error', 'text' => __('Your account is inactive!')]);
            return redirect()->back();
        }

        if ($user->type == 'vendor' || $user->type == 'buyer') {
            Session::flash('message', ['type' => 'success', 'text' => __('Welcome Home!')]);
            return redirect()->route('home');
        }

        Auth::logout();
        Session::flash('message', ['type' => 'error', 'text' => __('There are no invalid credentials!')]);
        return redirect()->back();
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

        return redirect()->route('home');
    }


}
