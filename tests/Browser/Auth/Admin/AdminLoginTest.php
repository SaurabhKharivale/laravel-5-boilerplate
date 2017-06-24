<?php

namespace Tests\Browser\Auth\Admin;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdminLoginTest extends DuskTestCase
{
    /** @test */
    public function admin_can_login_with_valid_credentials()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('logged in as admin');
        });
    }

    /** @test */
    public function user_cannot_login_with_invaid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $admin = factory(Admin::class)->create([
                'email' => 'admin@example.com',
                'password' => bcrypt('secret'),
            ]);

            $this->browse(function (Browser $browser) {
                $browser->visit('/admin/login')
                        ->type('email', 'admin@example.com')
                        ->type('password', 'INVALID_PASSWORD')
                        ->press('Login')
                        ->assertPathIs('/admin/login')
                        ->waitForText('credentials do not match');
            });
        });
    }
}
