<?php

namespace Tests\Feature\Auth\User;

use App\User;
use Tests\TestCase;

class UserCanLogoutTest extends TestCase
{
    /** @test */
    public function logged_in_user_can_logout()
    {
        $user = factory(User::class)->create(['email' => 'test@gmail.com']);
        $this->be($user);
        $this->assertTrue(auth()->check());
        $this->assertTrue(auth()->user()->is($user));

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertFalse(auth()->check());
    }
}
