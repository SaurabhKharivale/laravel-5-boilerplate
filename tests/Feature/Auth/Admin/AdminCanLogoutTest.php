<?php

namespace Tests\Feature\Auth\Admin;

use App\Admin;
use Tests\TestCase;

class AdminCanLogoutTest extends TestCase
{
    /** @test */
    public function logged_in_admin_user_can_logout()
    {
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        auth()->guard('admin')->login($admin);
        $this->assertTrue(auth()->guard('admin')->check());
        $this->assertTrue(auth()->guard('admin')->user()->is($admin));

        $response = $this->post('/admin/logout');

        $response->assertRedirect('/');
        $this->assertFalse(auth()->guard('admin')->check());
    }
}
