<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function logged_in_user_can_logout()
    {
        $user = factory(User::class)->create(['email' => 'test@gmail.com']);

        $this->browse(function (Browser $browser) use($user) {
            $browser->loginAs($user)
                    ->visit('/home')
                    ->clickLink('Logout')
                    ->assertPathIs('/');
        });
    }
}
