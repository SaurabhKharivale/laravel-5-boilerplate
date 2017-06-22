<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Feature\Auth\PasswordResetTrait;
use Tests\Browser\Pages\PasswordResetEmailPage;

class UserCanRequestForPasswordChangeTest extends DuskTestCase
{
    use PasswordResetTrait;

    /** @test */
    public function user_is_notified_about_password_link_sent_to_their_email()
    {
        factory(User::class)->create(['email' => 'john@gmail.com']);

        $this->browse(function (Browser $browser) {
            $browser->visit(new PasswordResetEmailPage)
                    ->type('email', 'john@gmail.com')
                    ->press('@send-reset-link')
                    ->waitForText('We have e-mailed your password reset link');
        });
    }

    /** @test */
    public function password_reset_token_is_generate_on_user_request()
    {
        factory(User::class)->create(['email' => 'john@gmail.com']);
        $this->assertPasswordResetTokenDoesNotExists('john@gmail.com');

        $this->browse(function (Browser $browser) {
            $browser->visit(new PasswordResetEmailPage)
                    ->type('email', 'john@gmail.com')
                    ->press('@send-reset-link');
        });

        $this->assertPasswordResetTokenExists('john@gmail.com');
    }

    /** @test */
    public function user_is_notified_if_email_does_not_exists()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new PasswordResetEmailPage)
                    ->type('email', 'john@gmail.com')
                    ->press('@send-reset-link')
                    ->waitForText('can\'t find a user');
        });
    }
}
