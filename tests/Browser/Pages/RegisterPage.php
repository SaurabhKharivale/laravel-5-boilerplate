<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class RegisterPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
            '@register' => '.register.button',
        ];
    }

    public function fillFormFields(Browser $browser, $data = [])
    {
        $browser->type('first_name', $data['first_name'] ?? 'John')
                ->type('last_name', $data['last_name'] ?? 'Doe')
                ->type('email', $data['email'] ?? 'johndoe@gmail.com')
                ->type('password', $data['password'] ?? '123456')
                ->type('password_confirmation', $data['password_confirmation'] ?? '123456');
    }
}
