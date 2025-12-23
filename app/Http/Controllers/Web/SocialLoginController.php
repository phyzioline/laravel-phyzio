<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            // 1. Check if user already linked this Google account
            $provider_user = User::where([
                'provider_name' => $provider,
                'provider_id'   => $user->getId(),
            ])->first();

            if ($provider_user) {
                Auth::login($provider_user);
                return redirect()->route('home.' . app()->getLocale());
            }

            // 2. Check if email already exists (Manual Registration)
            $existing_user = User::where('email', $user->getEmail())->first();

            if ($existing_user) {
                // Link Google to existing account
                $existing_user->update([
                    'provider_name'  => $provider,
                    'provider_id'    => $user->getId(),
                    'provider_token' => $user->token,
                    // Don't overwrite verification if verify date is set
                    'email_verified_at' => $existing_user->email_verified_at ?? now(),
                ]);

                Auth::login($existing_user);
                return redirect()->route('home.' . app()->getLocale());
            }

            // 3. Create NEW User (if email doesn't exist)
            $new_user = User::create([
                'name'           => $user->getName(),
                'email'          => $user->getEmail(),
                'password'       => Crypt::encrypt(Str::random(8)),
                'email_verified_at' => now(),
                'provider_name'  => $provider,
                'provider_id'    => $user->getId(),
                'provider_token' => $user->token,
                'phone'          => null, 
                'country'        => null,
                'type'           => 'buyer', 
                'status'         => 'active',
            ]);

            Auth::login($new_user);
            return redirect()->route('complecet_info_view');

        } catch (Throwable $e) {
            return redirect()->route('register')->withErrors([
                'email' => 'Login Failed: ' . $e->getMessage(),
            ]);
        }
    }
}
