<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class UserCanRequestForPasswordResetTest extends TestCase
{
    use PasswordResetTrait;

    public function setUp()
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function password_reset_token_is_generated_on_user_request()
    {
        $user = factory(User::class)->create(['email' => 'john@gmail.com']);
        $this->assertPasswordResetTokenDoesNotExists('john@gmail.com');

        $response = $this->post('/password/email', ['email' => 'john@gmail.com']);

        $this->assertPasswordResetTokenExists('john@gmail.com');
    }

    /** @test */
    public function user_is_redirected_back_on_successful_token_generation()
    {
        $user = factory(User::class)->create(['email' => 'john@gmail.com']);

        $response = $this->postFrom('/password/email', ['email' => 'john@gmail.com']);

        $response->assertRedirect('/password/email');
        $response->assertSessionHas('status', 'We have e-mailed your password reset link!');
    }

    /** @test */
    public function password_reset_link_is_sent_to_user_email_on_his_request()
    {
        $user = factory(User::class)->create(['email' => 'john@gmail.com']);

        $response = $this->post('/password/email', ['email' => 'john@gmail.com']);

        $token_stored_in_db = $this->getPasswordResetToken('john@gmail.com');
        Notification::assertSentTo($user, ResetPassword::class, function($mail) use ($token_stored_in_db) {
            return app('hash')->check($mail->token, $token_stored_in_db);
        });
    }

    /** @test */
    public function user_is_redirected_back_with_error_when_attepting_password_reset_on_non_existent_email()
    {
        $response = $this->postFrom('/password/email', ['email' => 'user_non_existent@xyz.com']);

        $response->assertRedirect('/password/email');
        $response->assertSessionHasErrors(['email']);
    }
}
