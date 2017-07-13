<?php

namespace Tests\Feature\Auth\User;

use App\User;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class UserCanLoginTest extends TestCase
{
    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        factory(User::class)->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('secret')
        ]);
        $credentials = [
            'email' => 'johndoe@gmail.com',
            'password' => 'secret'
        ];
        $this->assertNull(\Auth::user());

        $response = $this->post('login', $credentials);

        $response->assertRedirect('/home');
        $this->assertEquals('johndoe@gmail.com', \Auth::user()->email);
    }

    /** @test */
    public function user_trying_to_login_with_invalid_credentials_is_redirected_back_to_login()
    {
        factory(User::class)->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('secret')
        ]);
        $credentials = [
            'email' => 'johndoe@gmail.com',
            'password' => 'wrong-password'
        ];
        $this->assertNull(\Auth::user());

        $response = $this->from('/login')->post('/login', $credentials);

        $response->assertRedirect('/login');
        $this->assertNull(\Auth::user());
    }

    /** @test */
    public function user_trying_to_login_with_email_which_does_not_exist_in_system_is_redirected_back_to_login()
    {
        $credentials = [
            'email' => 'johndoe@gmail.com',
            'password' => 'wrong-password'
        ];
        $this->assertNull(\Auth::user());

        $response = $this->from('/login')->post('/login', $credentials);

        $response->assertRedirect('/login');
        $this->assertNull(\Auth::user());
    }

    /** @test */
    public function logged_in_user_cannot_access_admin_dashboard()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('secret')
        ]);
        $this->be($user);

        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }
}
