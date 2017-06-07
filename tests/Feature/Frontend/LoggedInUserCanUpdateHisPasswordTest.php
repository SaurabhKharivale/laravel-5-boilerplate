<?php

namespace Tests\Feature\Frontend;

use Hash;
use App\User;
use Tests\TestCase;
use Tests\Traits\UserAssertions;
use Tests\Traits\SessionAssertions;
use App\Exceptions\PasswordMismatchException;
use Illuminate\Validation\ValidationException;

class LoggedInUserCanUpdateHisPasswordTest extends TestCase
{
    use UserAssertions, SessionAssertions;

    /** @test */
    public function logged_in_user_can_update_his_password()
    {
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => '123456',
            'new_password_confirmation' => '123456',
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('123456', $user->fresh()->password);
    }

    /** @test */
    public function user_is_redirected_back_to_profile_page_after_password_update_is_successful()
    {
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $response = $this->call('POST', '/password/change', [
            'current_password' => 'secret',
            'new_password' => '123456',
            'new_password_confirmation' => '123456',
        ], [], [], ['HTTP_REFERER' => '/profile']);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('123456', $user->fresh()->password);
        $response->assertRedirect('/profile');
        $response->assertSessionHas('notification.message', 'Your password has been updated.');
        $response->assertSessionHas('notification.type', 'success');
        $response->assertSessionHas('notification.persist', true);
    }

    /** @test */
    public function user_cannot_change_his_password_if_current_password_did_not_match_with_our_records()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $response = $this->post('/password/change', [
            'current_password' => 'incorrect_current_password',
            'new_password' => '123456',
            'new_password_confirmation' => '123456',
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'current_password' => 'The current password did not match our records.',
        ]);
    }

    /** @test */
    public function current_password_field_is_required()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $response = $this->post('/password/change', [
            'current_password' => null,
        ]);
        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'current_password' => 'The current password field is required.',
        ]);
    }

    /** @test */
    public function new_password_field_is_required()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => null,
        ]);
        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'new_password' => 'The new password field is required.',
        ]);
    }

    /** @test */
    public function password_cannot_be_changed_if_password_confirmation_field_is_left_empty()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => '123456',
            'new_password_confirmation' => null,
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'new_password' => 'The new password confirmation does not match.',
        ]);
    }

    /** @test */
    public function password_cannot_be_changed_if_password_confirmation_does_not_match()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => '123456',
            'new_password_confirmation' => 'abcdefg',
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'new_password' => 'The new password confirmation does not match.',
        ]);
    }

    /** @test */
    public function new_password_must_have_at_least_6_characters()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => '123',
            'new_password_confirmation' => '123',
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'new_password' => 'The new password must be at least 6 characters.',
        ]);
    }

    /** @test */
    public function cannot_update_new_password_if_it_matches_current_password()
    {
        $this->withExceptionHandling();
        $user = $this->createLoggedInUser([
            'email' => 'john@gmail.com',
            'password' => 'secret',
        ]);
        $this->assertPasswordMatches('secret', $user->fresh()->password);

        $this->post('/password/change', [
            'current_password' => 'secret',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ]);

        $this->assertEquals('john@gmail.com', $user->email);
        $this->assertPasswordMatches('secret', $user->fresh()->password);
        $this->assertSessionContainErrors([
            'new_password' => 'The new password and current password must be different.',
        ]);
    }

    public function createLoggedInUser($user)
    {
        $user = factory(User::class)->create([
            'email' => $user['email'],
            'password' => bcrypt($user['password']),
        ]);
        $this->be($user);

        return $user;
    }
}
