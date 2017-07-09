<?php

namespace Tests\Feature\Auth;

use Tests\Feature\Auth\PasswordResetTrait;

trait CanChangePasswordTests
{
    use PasswordResetTrait;

    abstract protected function createUserOrAdmin();

    abstract protected function getPasswordResetURL();

    abstract protected function getSuccessfulRedirectURL();

    abstract protected function verifyLoggedIn();

    /** @test */
    public function can_access_password_reset_form()
    {
        $response = $this->get($this->getPasswordResetURL() . '/SOME_TOKEN');

        $response->assertStatus(200);
        $response->assertSee($this->getPasswordResetURL());
    }

    /** @test */
    public function user_can_change_his_password_using_valid_reset_token()
    {
        $user = $this->createUserOrAdmin('john@example.com', '123456');
        $valid_token = $this->generatePasswordResetToken($user);
        $this->assertUserPasswordIs('123456', $user);

        $response = $this->post($this->getPasswordResetURL(), [
            'token' => $valid_token,
            'email' => 'john@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertUserPasswordIs('new-password', $user);
        $response->assertRedirect($this->getSuccessfulRedirectURL());
        $this->assertTrue($this->verifyLoggedIn());
    }

    /** @test */
    public function user_cannot_change_his_password_using_invalid_token()
    {
        $user = $this->createUserOrAdmin('john@example.com', '123456');
        $this->assertUserPasswordIs('123456', $user);

        $response = $this->postFrom($this->getPasswordResetURL(), [
            'token' => 'invalid_token',
            'email' => 'john@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertUserPasswordIs('123456', $user);
        $response->assertRedirect($this->getPasswordResetURL());
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_with_valid_reset_token_cannot_change_another_users_password()
    {
        $user_one = $this->createUserOrAdmin('user_one@gmail.com', '123456');
        $user_two = $this->createUserOrAdmin('user_two@gmail.com', 'abcxyz');
        $valid_token_of_user_one = $this->generatePasswordResetToken($user_one);
        $this->assertUserPasswordIs('123456', $user_one);
        $this->assertUserPasswordIs('abcxyz', $user_two);

        $response = $this->postFrom($this->getPasswordResetURL(), [
            'token' => $valid_token_of_user_one,
            'email' => 'user_two@gmail.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertUserPasswordIs('123456', $user_one);
        $this->assertUserPasswordIs('abcxyz', $user_two);
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function user_cannot_change_password_using_expired_token()
    {
        $user = $this->createUserOrAdmin('john@example.com', '123456');
        $this->assertUserPasswordIs('123456', $user);
        $expired_token = $this->generateExpiredPasswordResetToken($user);

        $response = $this->post($this->getPasswordResetURL(), [
            'token' => $expired_token,
            'email' => 'john@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertUserPasswordIs('123456', $user);
        $response->assertSessionHasErrors('email');
    }
}
