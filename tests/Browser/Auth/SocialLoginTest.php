<?php

namespace Tests\Browser\Auth;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\GoogleLoginPage;
use Tests\Browser\Pages\TwitterLoginPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SocialLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_is_redirected_to_google_login_page_when_trying_to_login_using_google_account()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->clickLink('Sign in with Google')
                    ->pause(1000)
                    ->on(new GoogleLoginPage);
        });
    }

    /** @test */
    public function user_is_redirected_to_twitter_login_page_when_trying_to_login_using_twitter_account()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->clickLink('Sign in with Twitter')
                    ->pause(1000)
                    ->on(new TwitterLoginPage);
        });
    }
}
