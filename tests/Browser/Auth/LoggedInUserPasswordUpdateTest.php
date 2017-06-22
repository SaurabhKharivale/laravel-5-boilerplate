<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Traits\UserAssertions;
use Tests\Browser\Pages\ProfilePage;
use Tests\Feature\Auth\PasswordResetTrait;

class LoggedInUserPasswordUpdateTest extends DuskTestCase
{
    use UserAssertions;

    /** @test */
    public function user_can_update_password_with_valid_details()
    {
        $user = factory(User::class)->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit(new ProfilePage)
                    ->type('current_password', 'secret')
                    ->type('new_password', '123456')
                    ->type('new_password_confirmation', '123456')
                    ->press('@change')
                    ->assertPathIs('/profile')
                    ->waitForText('Your password has been updated');
        });

        $this->assertPasswordMatches('123456', $user->fresh()->password);
    }

    /** @test */
    public function user_cannot_update_password_with_incorrect_current_password()
    {
        $user = factory(User::class)->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit(new ProfilePage)
                    ->type('current_password', 'incorrect_current_password')
                    ->type('new_password', '123456')
                    ->type('new_password_confirmation', '123456')
                    ->press('@change')
                    ->assertPathIs('/profile')
                    ->waitForText('current password did not match');
        });

        $this->assertPasswordMatches('secret', $user->fresh()->password);
    }

    /** @test */
    public function cannnot_update_password_if_password_confirmation_does_not_match()
    {
        $user = factory(User::class)->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit(new ProfilePage)
                    ->type('current_password', 'secret')
                    ->type('new_password', '123456')
                    ->type('new_password_confirmation', 'abcdefg')
                    ->press('@change')
                    ->assertPathIs('/profile')
                    ->waitForText('password confirmation does not match');
        });

        $this->assertPasswordMatches('secret', $user->fresh()->password);
    }

    /** @test */
    public function new_password_must_have_at_least_6_characters()
    {
        $user = factory(User::class)->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit(new ProfilePage)
                    ->type('current_password', 'secret')
                    ->type('new_password', '123')
                    ->type('new_password_confirmation', '123')
                    ->press('@change')
                    ->assertPathIs('/profile')
                    ->waitForText('new password must be at least 6 characters');
        });

        $this->assertPasswordMatches('secret', $user->fresh()->password);
    }

    /** @test */
    public function user_is_notified_by_an_error_when_new_password_matches_current_password()
    {
        $user = factory(User::class)->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user->id)
                    ->visit(new ProfilePage)
                    ->type('current_password', 'secret')
                    ->type('new_password', 'secret')
                    ->type('new_password_confirmation', 'secret')
                    ->press('@change')
                    ->assertPathIs('/profile')
                    ->waitForText('new password and current password must be different');
        });

        $this->assertPasswordMatches('secret', $user->fresh()->password);
    }
}
