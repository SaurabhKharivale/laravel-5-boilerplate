<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\RegisterPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_register_with_valid_details()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterPage)
                    ->type('first_name', 'John')
                    ->type('last_name', 'Doe')
                    ->type('email', 'johndoe@gmail.com')
                    ->type('password', '123456')
                    ->type('password_confirmation', '123456')
                    ->press('@register')
                    ->assertRedirectedTo('home');
        });

        $this->assertCount(1, User::all());
        $user = User::first();
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('johndoe@gmail.com', $user->email);
    }

    /** @test */
    public function validation_error_is_shown_for_every_invalid_field()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterPage)
                    ->type('first_name', 'a')
                    ->type('last_name', 'a')
                    ->type('email', 'xyz@abc')
                    ->type('password', '123')
                    ->type('password_confirmation', '123')
                    ->press('@register')
                    ->waitForText('first name must be at least 3 characters', 1)
                    ->waitForText('last name must be at least 3 characters', 1)
                    ->waitForText('email must be a valid', 1)
                    ->waitForText('password must be at least 6 characters', 1);
        });
    }

    /** @test */
    public function error_is_shown_when_password_confirmation_does_not_match()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new RegisterPage)
                    ->fillFormFields()
                    ->type('password', '123456')
                    ->type('password_confirmation', 'abcdef')
                    ->press('@register')
                    ->waitForText('password confirmation does not match');
        });
    }
}