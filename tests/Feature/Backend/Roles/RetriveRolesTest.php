<?php

namespace Tests\Feature\Backend;

use App\Role;
use App\Admin;
use Tests\TestCase;

class RetriveRolesTest extends TestCase
{
    /** @test */
    public function can_fetch_all_roles()
    {
        $admin = factory(Admin::class)->create();
        factory(Role::class)->create(['name' => 'owner']);
        factory(Role::class)->create(['name' => 'executive']);
        factory(Role::class)->create(['name' => 'manager']);

        $response = $this->actingAs($admin, 'admin-api')->json('GET', '/api/role');

        $response->assertStatus(200);
        $response->assertJson([
            'roles' => [
                ['name' => 'owner'],
                ['name' => 'executive'],
                ['name' => 'manager'],
            ]
        ]);
    }

    /** @test */
    public function guest_user_cannot_view_roles()
    {
        $this->withExceptionHandling();

        $response = $this->json('GET', '/api/role');

        $response->assertStatus(401);
        $response->assertSeeText('Unauthenticated');
    }
}
