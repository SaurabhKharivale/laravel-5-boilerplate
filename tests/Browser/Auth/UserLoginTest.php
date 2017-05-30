<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Tests\Browser\Pages\LoginPage;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_login_using_valid_credentials()
    {
        factory(User::class)->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('secret')
        ]);
        $this->assertCount(1, User::all());

        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->type('email', 'johndoe@gmail.com')
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertRedirectedTo('home');
        });
    }

    /** @test */
    public function error_is_shown_when_credentials_does_not_match()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'ghost@user.com')
                    ->type('password', 'xyz')
                    ->press('@login')
                    ->waitForText('credentials do not match', 1);
        });
    }
}
