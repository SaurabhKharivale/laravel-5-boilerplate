<?php

namespace Tests\Browser\Auth\Admin;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Feature\Auth\PasswordResetTrait;
use Tests\Browser\Pages\AdminPasswordResetEmailPage;

class AdminCanRequestForPasswordChangeTest extends DuskTestCase
{
    use PasswordResetTrait;

    /** @test */
    public function admin_is_notified_about_password_link_sent_to_their_email()
    {
        factory(Admin::class)->create(['email' => 'admin@example.com']);

        $this->browse(function (Browser $browser) {
            $browser->visit(new AdminPasswordResetEmailPage)
                    ->type('email', 'admin@example.com')
                    ->press('@send-reset-link')
                    ->waitForText('We have e-mailed your password reset link');
        });
    }

    /** @test */
    public function password_reset_token_is_generate_on_user_request()
    {
        factory(Admin::class)->create(['email' => 'admin@example.com']);
        $this->assertPasswordResetTokenDoesNotExists('admin@example.com');

        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->clickLink('Forgot password')
                    ->on(new AdminPasswordResetEmailPage)
                    ->type('email', 'admin@example.com')
                    ->press('@send-reset-link')
                    ->waitForText('We have e-mailed your password reset link');
        });

        $this->assertPasswordResetTokenExists('admin@example.com');
    }

    /** @test */
    public function user_is_notified_if_email_does_not_exists()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AdminPasswordResetEmailPage)
                    ->type('email', 'admin@example.com')
                    ->press('@send-reset-link')
                    ->waitForText('can\'t find a user');
        });
    }
}
