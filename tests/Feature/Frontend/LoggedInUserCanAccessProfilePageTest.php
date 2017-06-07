<?php

namespace Tests\Feature\Frontend;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;

class LoggedInUserCanAccessProfilePageTest extends TestCase
{
    /** @test */
    public function user_basic_info_is_available_on_profile_page()
    {
        $user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@gmail.com',
        ]);
        $this->be($user);

        $response = $this->get('/profile');

        $response->assertViewHas('user')
                ->assertSee('John Doe')
                ->assertSee('john@gmail.com');
    }

    /** @test */
    public function guest_user_cannot_access_profile_page()
    {
        $this->withExceptionHandling();

        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_access_password_reset_form()
    {
        $user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@gmail.com',
        ]);
        $this->be($user);

        $response = $this->get('/profile');

        $response->assertSee('Change password')
                ->assertSee('Current password')
                ->assertSee('New password')
                ->assertSee('Confirm password');
    }
}
