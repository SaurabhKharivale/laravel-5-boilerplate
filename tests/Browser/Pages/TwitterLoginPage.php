<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class TwitterLoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/oauth/authenticate';
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
        return [];
    }
}
