<?php

namespace Tests\Browser\Auth;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdminLogoutTest extends DuskTestCase
{
    /** @test */
    public function admin_can_logout()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use($admin) {
            $browser->visit('/admin/login')
                    ->type('email', 'admin@example.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->clickLink('Logout')
                    ->assertPathIs('/');
        });
    }
}
