<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class GoogleLoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/signin/oauth/identifier';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@email' => 'input[name="identifier"]',
            '@password' => 'input[name="password"]',
            '@emailnext' => '#identifierNext',
            '@passwordnext' => '#passwordNext',
        ];
    }
}
