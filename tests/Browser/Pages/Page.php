<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function assertRedirectedTo(Browser $browser, $uri)
    {
        $browser->waitUntil("location.pathname.substring(1) == '$uri'", 1)
            ->assertPathIs("/$uri");
    }
}
