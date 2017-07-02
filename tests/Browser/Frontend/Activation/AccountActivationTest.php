<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Support\Assertions\UserAssertions;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountActivationTest extends DuskTestCase
{
    use DatabaseMigrations, UserAssertions;

    /** @test */
    public function logged_in_user_can_activate_account_using_valid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit('/activate/VALID_TOKEN')
                    ->assertPathIs('/home')
                    ->waitForText('Thank you for verifing your email.');
        });

        $this->assertEmailIsVerified($user);
    }

    /** @test */
    public function logged_in_user_cannot_activate_account_using_invalid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit('/activate/SOME_INVALID_TOKEN')
                    ->assertPathIs('/home')
                    ->waitForText('Sorry! Your activation link is not valid. Please check your email and try again.');
        });

        $this->assertEmailIsNotVerified($user);
    }

    /** @test */
    public function guest_user_can_activate_account_using_valid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->browse(function (Browser $browser) {
            $browser->visit('/activate/VALID_TOKEN')
                    ->assertPathIs('/login')
                    ->waitForText('Thank you for verifing your email.');
        });

        $this->assertEmailIsVerified($user);
    }

    /** @test */
    public function guest_user_cannot_activate_account_using_invalid_activation_link()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->browse(function (Browser $browser) {
            $browser->visit('/activate/SOME_INVALID_TOKEN')
                    ->assertPathIs('/login')
                    ->waitForText('Sorry! Your activation link is not valid. Please check your email and try again.');
        });

        $this->assertEmailIsNotVerified($user);
    }

    /** @test */
    public function user_can_request_to_resend_activation_email()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)->visit('/home')
                    ->assertSee('check your email for activation email')
                    ->clickLink('Resend')
                    ->waitForText('We have resent activation link to your email');
        });

        $this->assertEmailIsNotVerified($user);
    }
}
