<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class AdminPasswordResetEmailPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/admin/password/reset';
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
            '@send-reset-link' => '.send-reset-link',
        ];
    }
}
