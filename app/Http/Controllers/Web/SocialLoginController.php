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

            $provider_user = User::where([
                'provider_name' => $provider,
                'provider_id'   => $user->getId(),
            ])->first();

            if (! $provider_user) {
                $provider_user = User::create([
                    'name'           => $user->getName(),
                    'email'          => $user->getEmail(),
                    'password'       => Crypt::encrypt(Str::random(8)),
                    'email_verified_at' => now(),
                    'provider_name'  => $provider,
                    'provider_id'    => $user->getId(),
                    'provider_token' => $user->token,
                    'phone'          => null, // Google doesn't provide phone
                    'country'        => null, // Will be collected later if needed
                    'type'           => 'buyer', // Default type for social login
                    'status'         => 'active', // Auto-activate Google users
                ]);

                Auth::login($provider_user);
                 return redirect()->route('complecet_info_view');
            } else {
                Auth::login($provider_user);
                return redirect()->route('home');
            }

        } catch (Throwable $e) {
            return redirect()->route('register')->withErrors([
                'email' => $e->getMessage(),
            ]);
        }
    }

}
