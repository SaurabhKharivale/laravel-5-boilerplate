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
}
