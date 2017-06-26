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

    // use PasswordResetTrait;
    //
    // /** @test */
    // public function user_can_change_his_password_using_valid_reset_token()
    // {
    //     $user = $this->createUser('john@gmail.com', '123456');
    //     $valid_token = $this->generatePasswordResetToken($user);
    //     $this->assertUserPasswordIs('123456', $user->password);
    //
    //     $response = $this->post('/admin/password/reset', [
    //         'token' => $valid_token,
    //         'email' => 'john@gmail.com',
    //         'password' => 'new-password',
    //         'password_confirmation' => 'new-password',
    //     ]);
    //
    //     $this->assertUserPasswordIs('new-password', $user->fresh()->password);
    //     $response->assertRedirect('/home');
    //     $this->assertTrue(auth()->guard('admin')->check());
    // }
    //
    // /** @test */
    // public function user_cannot_change_his_password_using_invalid_token()
    // {
    //     $user = $this->createUser('john@gmail.com', '123456');
    //     $this->assertUserPasswordIs('123456', $user->password);
    //
    //     $response = $this->postFrom('/admin/password/reset', [
    //         'token' => 'invalid_token',
    //         'email' => 'john@gmail.com',
    //         'password' => 'new-password',
    //         'password_confirmation' => 'new-password',
    //     ]);
    //
    //     $this->assertUserPasswordIs('123456', $user->fresh()->password);
    //     $response->assertRedirect('/admin/password/reset');
    //     $response->assertSessionHasErrors('email');
    // }
    //
    // /** @test */
    // public function user_with_valid_reset_token_cannot_change_another_users_password()
    // {
    //     $user_one = $this->createUser('user_one@gmail.com', '123456');
    //     $user_two = $this->createUser('user_two@gmail.com', 'abcxyz');
    //     $valid_token_of_user_one = $this->generatePasswordResetToken($user_one);
    //     $this->assertUserPasswordIs('123456', $user_one->password);
    //     $this->assertUserPasswordIs('abcxyz', $user_two->password);
    //
    //     $response = $this->postFrom('/admin/password/reset', [
    //         'token' => $valid_token_of_user_one,
    //         'email' => 'user_two@gmail.com',
    //         'password' => 'new-password',
    //         'password_confirmation' => 'new-password',
    //     ]);
    //
    //     $this->assertUserPasswordIs('123456', $user_one->fresh()->password);
    //     $this->assertUserPasswordIs('abcxyz', $user_two->fresh()->password);
    //     $response->assertSessionHasErrors('email');
    // }
    //
    // /** @test */
    // public function user_cannot_change_password_using_expired_token()
    // {
    //     $user = $this->createUser('john@gmail.com', '123456');
    //     $this->assertUserPasswordIs('123456', $user->password);
    //     $expired_token = $this->generateExpiredPasswordResetToken($user);
    //
    //     $response = $this->post('/admin/password/reset', [
    //         'token' => $expired_token,
    //         'email' => 'john@gmail.com',
    //         'password' => 'new-password',
    //         'password_confirmation' => 'new-password',
    //     ]);
    //
    //     $this->assertUserPasswordIs('123456', $user->fresh()->password);
    //     $response->assertSessionHasErrors('email');
    // }
    //
    // public function createUser($email, $password)
    // {
    //     return factory(Admin::class)->create([
    //         'email' => $email,
    //         'password' => bcrypt($password)
    //     ]);
    // }
}
