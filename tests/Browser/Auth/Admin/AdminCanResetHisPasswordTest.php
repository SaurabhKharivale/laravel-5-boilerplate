<?php

namespace Tests\Browser\Auth\Admin;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Feature\Auth\PasswordResetTrait;

class AdminCanResetHisPasswordTest extends DuskTestCase
{
    use PasswordResetTrait;

    /** @test */
    public function admin_can_reset_password_with_valid_reset_token()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('123456')
        ]);
        $token = $this->generatePasswordResetToken($admin);
        $this->assertAdminPasswordIs('123456', $admin);

        $this->browse(function (Browser $browser) use ($token) {
            $browser->visit("/admin/password/reset/$token")
                    ->type('email', 'admin@example.com')
                    ->type('password', 'new-password')
                    ->type('password_confirmation', 'new-password')
                    ->press('Reset')
                    ->assertPathIs('/admin/dashboard');
        });

        $this->assertAdminPasswordIs('new-password', $admin);
    }

    /** @test */
    public function admin_cannot_reset_password_using_invalid_password_reset_token()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('123456')
        ]);
        $token = $this->generatePasswordResetToken($admin);
        $this->assertAdminPasswordIs('123456', $admin);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/password/reset/invalid_token')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'new-password')
                    ->type('password_confirmation', 'new-password')
                    ->press('Reset')
                    ->waitForText('password reset token is invalid');
        });

        $this->assertAdminPasswordIs('123456', $admin);
    }

    /** @test */
    public function errors_are_shown_when_password_is_less_than_6_characters()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/password/reset/token')
                    ->type('email', 'admin@example.com')
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
            $browser->visit('/admin/password/reset/token')
                    ->type('email', 'admin@example.com')
                    ->type('password', '123456')
                    ->type('password_confirmation', 'abcdef')
                    ->press('Reset')
                    ->waitForText('password confirmation does not match');
        });
    }
}
