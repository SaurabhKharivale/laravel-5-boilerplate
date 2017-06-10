<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Feature\Auth\PasswordResetTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCanResetHisPasswordTest extends DuskTestCase
{
    use DatabaseMigrations, PasswordResetTrait;

    /** @test */
    public function user_can_reset_password_with_valid_reset_token()
    {
        $user = factory(User::class)->create([
            'email' => 'john@doe.com',
            'password' => bcrypt('123456')
        ]);
        $token = $this->generatePasswordResetToken($user);
        $this->assertUserPasswordIs('123456', $user->password);

        $this->browse(function (Browser $browser) use ($token) {
            $browser->visit("/password/reset/$token")
                    ->type('email', 'john@doe.com')
                    ->type('password', 'new-password')
                    ->type('password_confirmation', 'new-password')
                    ->press('Reset');
        });

        $this->assertUserPasswordIs('new-password', $user->fresh()->password);
    }

    /** @test */
    public function user_cannot_reset_password_using_invalid_password_reset_token()
    {
        $user = factory(User::class)->create([
            'email' => 'john@doe.com',
            'password' => bcrypt('123456')
        ]);
        $token = $this->generatePasswordResetToken($user);
        $this->assertUserPasswordIs('123456', $user->password);

        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset/invalid_token')
                    ->type('email', 'john@doe.com')
                    ->type('password', 'new-password')
                    ->type('password_confirmation', 'new-password')
                    ->press('Reset');
        });

        $this->assertUserPasswordIs('123456', $user->password);
    }

    /** @test */
    public function errors_are_shown_when_password_is_less_than_6_characters()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset/token')
                    ->type('email', 'john@doe.com')
                    ->type('password', '123')
                    ->type('password_confirmation', '123')
                    ->press('Reset')
                    ->waitForText('password must be at least 6 characters');
        });
    }

    /** @test */
    public function error_is_shown_when_password_confirmation_does_not_match()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset/token')
                    ->type('email', 'john@doe.com')
                    ->type('password', '123456')
                    ->type('password_confirmation', 'abcdef')
                    ->press('Reset')
                    ->waitForText('password confirmation does not match');
        });
    }
}
