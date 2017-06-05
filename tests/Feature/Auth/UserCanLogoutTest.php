<?php

namespace Tests\Feature;

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

        $response = $this->call('POST', '/logout', [], [], [], ['HTTP_REFERER' => '/home']);

        $response->assertRedirect('/');
        $this->assertFalse(auth()->check());
    }
}
