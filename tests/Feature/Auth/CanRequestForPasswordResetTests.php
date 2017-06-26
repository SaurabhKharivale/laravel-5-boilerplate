<?php

namespace Tests\Feature\Auth;

use Illuminate\Notifications\Messages\MailMessage;

use App\Notifications\AdminResetPassword;
use Tests\Feature\Auth\PasswordResetTrait;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

trait CanRequestForPasswordResetTests
{
    use PasswordResetTrait;

    abstract protected function getResetLinkUrl();

    abstract protected function createUserOrAdmin();

    abstract protected function passwordResetInitiationFormURL();

    abstract protected function getNotificationClass();

    public function setUp()
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function can_access_form_to_initiate_password_reset()
    {
        $response = $this->get($this->passwordResetInitiationFormURL());

        $response->assertStatus(200);
        $response->assertSee($this->getResetLinkUrl());
    }

    /** @test */
    public function password_reset_token_is_generated_on_user_request()
    {
        $this->createUserOrAdmin('john@example.com');
        $this->assertPasswordResetTokenDoesNotExists('john@example.com');

        $response = $this->post($this->getResetLinkUrl(), ['email' => 'john@example.com']);

        $this->assertPasswordResetTokenExists('john@example.com');
    }

    /** @test */
    public function user_is_redirected_back_on_successful_token_generation()
    {
        $this->createUserOrAdmin('john@example.com');

        $response = $this->postFrom($this->getResetLinkUrl(), ['email' => 'john@example.com']);

        $response->assertRedirect($this->getResetLinkUrl());
        $this->assertPasswordResetTokenExists('john@example.com');
        $response->assertSessionHas('status', 'We have e-mailed your password reset link!');
    }

    /** @test */
    public function password_reset_link_is_sent_to_user_email_on_his_request()
    {
        $user = $this->createUserOrAdmin('john@example.com');

        $response = $this->post($this->getResetLinkUrl(), ['email' => 'john@example.com']);

        $token_stored_in_db = $this->getPasswordResetToken('john@example.com');
        Notification::assertSentTo($user, $this->getNotificationClass(), function($notification) use ($token_stored_in_db) {
            $mail = $notification->toMail(new MailMessage);
            $this->assertEquals('Reset Password', $mail->actionText);
            $this->assertEquals(
                config('app.url').$this->passwordResetInitiationFormURL().'/'.$notification->token,
                $mail->actionUrl
            );

            return app('hash')->check($notification->token, $token_stored_in_db);
        });
    }

    /** @test */
    public function user_is_redirected_back_with_error_when_attepting_password_reset_on_non_existent_email()
    {
        $response = $this->postFrom($this->getResetLinkUrl(), ['email' => 'user_non_existent@xyz.com']);

        $response->assertRedirect($this->getResetLinkUrl());
        $response->assertSessionHasErrors(['email']);
    }
}
