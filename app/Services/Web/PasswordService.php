<?php
namespace App\Services\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\OTPEmail;
use App\Traits\HasImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class PasswordService
{
    use HasImage;

    public function __construct(public User $model)
    {}

    public function forget_password_view()
    {
        return view('web.auth.forget_password');
    }

    public function forgetPassword($data)
    {
        $user = $this->model->where('email', $data['email'])->first();
        if ($user) {
            $user->update(['code' => mt_rand(1000, 9999), 'expire_at' => Carbon::now()->addMinutes(5), 'email_verified_at' => null]);

        Mail::to($data['email'])->send(new OTPEmail($user->code));
            return $user;
        }
        Session::flash('message', ['type' => 'error', 'text' => __('Account not found!')]);
    }

}
