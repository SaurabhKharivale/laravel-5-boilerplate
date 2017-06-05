<?php

namespace Tests\Feature;

use Mockery;
use App\User;
use Socialite;
use Tests\TestCase;
use App\SocialAccount;
use Tests\Traits\UserAssertions;
use App\Exceptions\AccountCreationFailedException;

class UserCanLoginUsingSocialAccountTest extends TestCase
{
    use UserAssertions;

    /** @test */
    public function user_can_initiate_login_using_social_account()
    {
        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('redirect')->once();

        Socialite::shouldReceive('driver')->with('google')->once()->andReturn($provider);

        $response = $this->get('/auth/google');
    }

    /** @test */
    public function user_account_is_created_when_logging_in_for_first_time_through_google()
    {
        $this->setupSocilaliteExpectationsForUser([
            'provider_id' => '123456',
            'email' => 'test@gmail.com',
            'name' => 'Test User',
            'provider' => 'google'
        ]);
        $this->assertUserDoesNotExistsWithEmail('test@gmail.com');

        $response = $this->get('/auth/google/callback');

        $user = $this->assertUserExistsWithEmail('test@gmail.com');
        $this->assertEquals('Test User', $user->first_name);
        $this->assertNull($user->last_name);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider_id' => '123456',
            'provider' => 'google'
        ]);
        $response->assertRedirect('home');
    }

    /** @test */
    public function user_can_login_using_social_account()
    {
        $user = factory(User::class)->create([
            'email' => 'test@gmail.com'
        ]);
        factory(SocialAccount::class)->create(['user_id' => $user->id, 'provider' => 'google', 'provider_id' => '123456']);
        $this->setupSocilaliteExpectationsForUser([
            'provider_id' => '123456',
            'email' => 'test@gmail.com',
            'name' => 'Test User',
            'provider' => 'google'
        ]);
        $this->assertUserExistsWithEmail('test@gmail.com');
        $this->assertCount(1, User::all());

        $response = $this->get('/auth/google/callback');

        $this->assertCount(1, User::all());
        $this->assertEquals('test@gmail.com', auth()->user()->email);
        $this->assertEquals('123456', auth()->user()->socialAccounts->first()->provider_id);
    }

    /** @test */
    public function user_is_logged_in_after_successful_registration()
    {
        $this->setupSocilaliteExpectationsForUser([
            'email' => 'test@gmail.com',
            'name' => 'Test User',
            'provider_id' => '123456',
            'provider' => 'google'
        ]);
        $this->assertUserDoesNotExistsWithEmail('test@gmail.com');

        $response = $this->get('/auth/google/callback');

        $user = $this->assertUserExistsWithEmail('test@gmail.com');
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider_id' => '123456',
            'provider' => 'google'
        ]);
        $this->assertEquals('test@gmail.com', auth()->user()->email);
        $this->assertEquals($user->name, auth()->user()->name);
    }

    /** @test */
    public function user_account_is_not_created_if_error_occurs_while_linking_user_account_with_social_account()
    {
        $this->setupSocilaliteExpectationsForUser([
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'provider' => 'google',
            'provider_id' => null,
        ]);

        try {
            $response = $this->get('/auth/google/callback');
        } catch(AccountCreationFailedException $e) {
            $this->assertUserDoesNotExistsWithEmail('test@gmail.com');

            return;
        }

        $this->fail('User should not exist if social account linking failed');
    }

    /** @test */
    public function user_can_login_even_when_social_provider_deos_not_provide_an_email()
    {
        $this->setupSocilaliteExpectationsForUser([
            'name' => 'Test user',
            'email' => null,
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
        $this->assertCount(0, User::all());

        $response = $this->get('/auth/google/callback');

        $this->assertCount(1, User::all());
        $user = User::first();
        $this->assertEquals('Test user', $user->first_name);
        $this->assertNull($user->last_name);
        $this->assertNull($user->email);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider_id' => '123456',
            'provider' => 'google'
        ]);
        $response->assertRedirect('home');
    }

    public function setupSocilaliteExpectationsForUser($data)
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->once()->andReturn($data['provider_id'])
                    ->shouldReceive('getEmail')->once()->andReturn($data['email'])
                    ->shouldReceive('getName')->once()->andReturn($data['name']);

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->once()->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with($data['provider'])->once()->andReturn($provider);
    }
}
