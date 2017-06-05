<?php

namespace App\Repositories;

use DB;
use App\User;
use App\SocialAccount;
use App\Exceptions\AccountCreationFailedException;
use App\Exceptions\SocialAccountNotLinkedException;

class UserRepository
{
    public function authenticate($social_account)
    {
        $found_user = $this->findByEmailOrSocialAccount($social_account);

        if(! $found_user) {
            return $this->createAccount($social_account);
        }

        return $found_user;
    }

    public function createUser($user)
    {
        return User::create([
            'first_name' => $user['name'],
            'email' => $user['email'],
            'password' => bcrypt(str_random(16))
        ]);
    }

    public function createAccount($social_account)
    {
        $user = DB::transaction(function() use ($social_account) {
            try {
                $user =  $this->createUser($social_account);

                return $this->linkSocialAccount($user, $social_account);
            } catch (SocialAccountNotLinkedException | \Exception $e) {
                throw new AccountCreationFailedException('Account creation failed');
            }
        });

        return $user;
    }

    public function linkSocialAccount($user, $social)
    {
        if(empty($social['provider']) || empty($social['provider_id'])) {
            throw new SocialAccountNotLinkedException('Unable to link social account with user.');
        }

        $user->socialAccounts()->create([
            'provider' => $social['provider'],
            'provider_id' => $social['provider_id'],
        ]);

        return $user->fresh();
    }

    public function findByEmailOrSocialAccount($user)
    {
        $email = $user['email'] ?? 'no-email-provided';
        $provider = $user['provider'] ?? null;
        $provider_id = $user['provider_id'] ?? null;

        $found_user = User::with('socialAccounts')->where('email', $email)->first();

        if(! $found_user) {
            $social_account = SocialAccount::where('provider', $provider)
                    ->where('provider_id', $provider_id)
                    ->first();

            return $social_account ? $social_account->user : null;
        }

        return $found_user;
    }
}
