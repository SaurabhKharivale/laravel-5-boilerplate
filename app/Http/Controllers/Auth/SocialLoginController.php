<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider, UserRepository $userRepo)
    {
        $user = Socialite::driver($provider)->user();

        $authenticated_user = $userRepo->authenticate([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'provider' => $provider,
            'provider_id' => $user->getId(),
        ]);

        auth()->login($authenticated_user, true);

        return redirect()->home();
    }
}
