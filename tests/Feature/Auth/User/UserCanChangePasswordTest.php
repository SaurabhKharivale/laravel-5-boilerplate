<?php

namespace Tests\Feature\Auth\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Auth\CanChangePasswordTests;
use Illuminate\Auth\Notifications\ResetPassword;

class UserCanChangePasswordTest extends TestCase
{
    use CanChangePasswordTests;

    private $password_reset_url = '/password/reset';

    private $successful_redirect_url = '/home';

    protected function getPasswordResetURL()
    {
        return $this->password_reset_url;
    }

    protected function getSuccessfulRedirectURL()
    {
        return $this->successful_redirect_url;
    }

    protected function createUserOrAdmin($email, $password)
    {
        return factory(User::class)->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    protected function verifyLoggedIn()
    {
        return auth()->guard('web')->check();
    }
}
