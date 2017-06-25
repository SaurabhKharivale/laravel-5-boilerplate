<?php

namespace Tests\Feature\Auth\Admin;

use App\User;
use App\Admin;
use Tests\TestCase;

class AdminCanLoginTest extends TestCase
{
    /** @test */
    public function user_can_login_as_admin_using_correct_credentials()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->assertAdminIsNotLoggedIn();

        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertLoggedInAsAdmin($admin);
    }

    /** @test */
    public function user_cannot_login_as_admin_using_invalid_credentials()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->assertAdminIsNotLoggedIn();

        $response = $this->call('POST', '/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'INVALID_PASSWORD',
        ], [], [], ['HTTP_REFERER' => '/admin/login']);

        $response->assertRedirect('/admin/login');
        $this->assertAdminIsNotLoggedIn();
    }

    /** @test */
    public function cannot_use_standard_users_credentials_to_login_as_admin()
    {
        $standard_user = factory(User::class)->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret'),
        ]);
        $this->assertAdminIsNotLoggedIn();

        $response = $this->call('POST', '/admin/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
        ], [], [], ['HTTP_REFERER' => '/admin/login']);

        $response->assertRedirect('/admin/login');
        $this->assertAdminIsNotLoggedIn();
    }

    /** @test */
    public function admin_user_can_access_admin_dashboard()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);
        auth()->guard('admin')->login($admin);
        $this->assertLoggedInAsAdmin($admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('logged in as admin');
    }

    /** @test */
    public function logged_in_admin_trying_to_access_admin_login_page_is_redirected_back_to_admin_dashboard()
    {
        $admin = factory(Admin::class)->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
        ]);
        auth()->guard('admin')->login($admin);
        $this->assertLoggedInAsAdmin($admin);

        $response = $this->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
    }

    /** @test */
    public function guest_user_can_visit_admin_login_page()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function assertAdminIsNotLoggedIn()
    {
        $this->assertFalse(auth()->guard('admin')->check(), 'No admin must not be logged in.');
    }

    public function assertLoggedInAsAdmin(Admin $admin)
    {
        $this->assertTrue(auth()->guard('admin')->check(), 'User is not logged in as admin.');
        $user = auth()->guard('admin')->user();
        $this->assertTrue(
            $user->is($admin),
            "Different user is logged in as admin.\nExpected admin: '{$admin->email}'\nActual admin: '{$user->email}'"
        );
    }
}
