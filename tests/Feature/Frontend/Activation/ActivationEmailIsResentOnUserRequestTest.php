<?php

namespace Tests\Feature\Frontend\Activation;

use Mail;
use App\User;
use Tests\TestCase;
use App\Mail\ActivationEmail;
use App\Events\ActivationEmailRequested;

class ActivationEmailIsResentOnUserRequestTest extends TestCase
{
    use ConformsToEmailContractTests;

    public function fireEventToSendActivationEmail($user)
    {
        \DB::table('user_activations')->insert([
            'user_id' => $user->id,
            'token' => 'VALID_TOKEN_CREATED_AFTER_REGISTERING',
        ]);
        $this->assertUserHasActivationToken($user);

        event(new ActivationEmailRequested($user));
    }

    /** @test */
    public function guest_users_cannot_request_to_resend_activation_link()
    {
        $response = $this->get('resend-activation-link');

        $response->assertRedirect('/login');

        $response->assertSessionHas([
            'notification.message' => 'Please login first inorder to receive your activation email.',
            'notification.type' => 'info',
        ]);
    }

    /** @test */
    public function logged_in_users_can_request_to_resend_activation_link()
    {
        Mail::fake();
        $user = factory(User::class)->create();
        $this->be($user);
        \DB::table('user_activations')->insert([
            'user_id' => $user->id,
            'token' => 'VALID_TOKEN_CREATED_AFTER_REGISTERING',
        ]);
        $response = $this->get('resend-activation-link');

        $response->assertRedirect('/home');

        Mail::assertSent(ActivationEmail::class, function($mail) {
            return $mail->token == 'VALID_TOKEN_CREATED_AFTER_REGISTERING';
        });
        $response->assertSessionHas([
            'notification.message' => 'We have resent activation link to your email address.',
            'notification.type' => 'success'
        ]);
    }

    /** @test */
    public function already_verified_user_cannot_request_to_resend_activation_link()
    {
        $user = factory(User::class)->create(['verified' => true]);
        $this->be($user);
        $this->assertEmailIsVerified($user);

        $response = $this->get('resend-activation-link');

        $response->assertRedirect('/home');
        $this->assertEmailIsVerified($user);
        $response->assertSessionHas([
            'notification.message' => 'You have already verified your email.',
            'notification.type' => 'info'
        ]);
    }
}
