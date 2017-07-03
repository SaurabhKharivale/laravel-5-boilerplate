<?php

namespace Tests\Browser\Backend\AdminAccounts;

use App\Admin;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\DashboardPage;
use Tests\Support\Helpers\AdminHelpers;

class CreateNewAdminTest extends DuskTestCase
{
    use AdminHelpers;

    /** @test */
    public function super_admin_can_create_account_for_other_admins()
    {
        $super_admin = $this->createSuperAdmin('admin@example.com');
        $this->assertCount(1, Admin::all());

        $this->browse(function (Browser $browser) use ($super_admin) {
            $browser->loginAs($super_admin, 'admin')
                    ->visit(new DashboardPage)
                    ->press('@create-new-admin')
                    ->waitForText('New admin details')
                    ->type('first_name', 'Jane')
                    ->type('last_name', 'Doe')
                    ->type('email', 'jane@example.com')
                    ->press('@save-admin')
                    ->waitForText('New admin user created.');
        });

        $this->assertCount(2, Admin::all());
        $this->assertDatabaseHas('admins', [
            'email' => 'jane@example.com',
        ]);
    }

    /** @test */
    public function admin_with_create_permission_can_create_account_for_other_admins()
    {
        $role = $this->createRoleWithPermission('manager', 'create-admin');
        $admin = factory(Admin::class)->create();
        $admin->assignRole($role);
        $this->assertCount(1, Admin::all());

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'admin')
                    ->visit(new DashboardPage)
                    ->press('@create-new-admin')
                    ->waitForText('New admin details')
                    ->type('first_name', 'Jane')
                    ->type('last_name', 'Doe')
                    ->type('email', 'jane@example.com')
                    ->press('@save-admin')
                    ->waitForText('New admin user created.');
        });

        $this->assertCount(2, Admin::all());
        $this->assertDatabaseHas('admins', [
            'email' => 'jane@example.com',
        ]);
    }

    /** @test */
    public function admin_without_create_permission_cannot_create_account_for_other_admins()
    {
        $admin = factory(Admin::class)->create();
        $this->assertCount(1, Admin::all());

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin, 'admin')
                    ->visit(new DashboardPage)
                    ->press('@create-new-admin')
                    ->waitForText('New admin details')
                    ->type('first_name', 'Jane')
                    ->type('last_name', 'Doe')
                    ->type('email', 'jane@example.com')
                    ->press('@save-admin')
                    ->waitForText('Admin creation failed.');
        });

        $this->assertCount(1, Admin::all());
        $this->assertDatabaseMissing('admins', [
            'email' => 'jane@example.com',
        ]);
    }
}
