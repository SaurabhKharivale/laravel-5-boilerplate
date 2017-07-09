<?php

namespace Tests\Feature\Auth;

use App\Admin;
use Tests\TestCase;
use Tests\Feature\Auth\CanChangePasswordTests;

class AdminCanChangePasswordTest extends TestCase
{
    use CanChangePasswordTests;

    private $password_reset_url = '/admin/password/reset';

    private $successful_redirect_url = '/admin/dashboard';

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
        return factory(Admin::class)->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    protected function verifyLoggedIn()
    {
        return auth()->guard('admin')->check();
    }
}
