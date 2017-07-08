<?php

namespace Tests\Support\Assertions;

use App\Admin;

trait AdminAssertions
{
    public function assertAdminUserExists($email)
    {
        $admin = Admin::where('email', $email)->get();
        $this->assertCount(1, $admin);
    }

    public function assertAdminUserDoesNotExists($email)
    {
        $admin = Admin::where('email', $email)->get();
        $this->assertCount(0, $admin);
    }

    public function assertAdminHasRole($role, $admin)
    {
        $this->assertTrue($admin->fresh()->roles->contains('name', $role), "Admin does not have {$role} role");
    }

    public function assertAdminDoesNotHaveRole($role, $admin)
    {
        $this->assertFalse($admin->fresh()->roles->contains('name', $role), "Admin should not have '{$role}' role");
    }

    public function assertActionIsUnauthorized($response)
    {
        $response->assertStatus(403);
        $response->assertJson(['message' => 'This action is unauthorized.']);
    }
}
