<?php

namespace Tests\Feature\Forntend\Activation;

use Event;
use App\User;
use Tests\TestCase;
use Tests\Traits\UserAssertions;
use App\Events\ActivationEmailRequested;

class UserCanActivateHisAccountTest extends TestCase
{
    use UserAssertions;

    /** @test */
    public function logged_in_user_can_activate_his_account_using_valid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        $this->be($user);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertEmailIsNotVerified($user);

        $response = $this->get('/activate/VALID_ACTIVATION_TOKEN');

        $this->assertEmailIsVerified($user);
        $response->assertRedirect('/home');
        $response->assertSessionHas([
            'notification.message' => 'Thank you for verifing your email.',
            'notification.type' => 'success'
        ]);
    }

    /** @test */
    public function logged_in_user_cannot_activate_his_account_using_invalid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        $this->be($user);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertEmailIsNotVerified($user);

        $response = $this->get('/activate/INVALID_TOKEN');

        $this->assertEmailIsNotVerified($user);
        $response->assertRedirect('/home');
        $response->assertSessionHas([
            'notification.message' => 'Sorry! Your activation link is not valid. Please check your email and try again.',
            'notification.type' => 'error'
        ]);
    }

    /** @test */
    public function guest_user_can_activate_his_account_using_valid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertEmailIsNotVerified($user);

        $response = $this->get('/activate/VALID_ACTIVATION_TOKEN');

        $this->assertEmailIsVerified($user);
        $response->assertRedirect('/login');
        $response->assertSessionHas([
            'notification.message' => 'Thank you for verifing your email.',
            'notification.type' => 'success'
        ]);
    }

    /** @test */
    public function guest_user_cannot_activate_his_account_using_invalid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertEmailIsNotVerified($user);

        $response = $this->get('/activate/INVALID_TOKEN');

        $this->assertEmailIsNotVerified($user);
        $response->assertRedirect('/login');
        $response->assertSessionHas([
            'notification.message' => 'Sorry! Your activation link is not valid. Please check your email and try again.',
            'notification.type' => 'error'
        ]);
    }

    /** @test */
    public function logged_in_user_can_request_to_resend_activation_link()
    {
        Event::fake();
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        $this->be($user);

        $response = $this->get('/resend-activation-link');

        $response->assertRedirect('/home');
        Event::assertDispatched(ActivationEmailRequested::class, function($event) use ($user) {
            return $event->user->is($user);
        });
    }

    /** @test */
    public function after_user_email_is_verified_activation_token_is_deleted()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertCount(1, \DB::table('user_activations')->get());

        $response = $this->get('/activate/VALID_ACTIVATION_TOKEN');

        $this->assertEmailIsVerified($user);
        $this->assertCount(0, \DB::table('user_activations')->get(), 'User activation token not deleted after successful email verification.');
    }
}
