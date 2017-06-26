<?php

namespace Tests\Feature\Auth\User;

use App\User;
use Tests\TestCase;
use App\Events\UserRegistered;
use Tests\Traits\UserAssertions;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class UserCanRegisterTest extends TestCase
{
    use UserAssertions;

    public function setUp()
    {
        parent::setUp();

        // User sucessful registration triggers an event which sends mail and results in slow test.
        // Below tests are not concerned about whether an email is sent or not.
        // That concern is handled in anonther tests class.
        // Hence, to speedup the tests, need to stop the events from firing.
        $this->withoutEvents();
    }

    /** @test */
    public function user_can_register_with_valid_info()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];
        $this->assertCount(0, User::all());

        $response = $this->post('register', $data);

        $this->assertCount(1, User::all());
        $user = User::first();
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
        $this->assertEquals('johndoe@gmail.com', $user->email);
        $this->assertPasswordMatches('123456', $user->password);
    }

    /** @test */
    public function user_is_redirected_to_home_page_on_successful_registration_by_default()
    {
        $data = $this->generateUserDetails();

        $response = $this->post('register', $data);

        $response->assertRedirect('/home');
    }

    /** @test */
    public function a_successful_response_with_redirect_path_is_genereated_when_user_registers_using_ajax()
    {
        $data = $this->generateUserDetails();

        $response = $this->json('POST', 'register', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect_to' => '/home'
        ]);
    }

    /** @test */
    public function first_name_is_required()
    {
        $data = $this->generateUserDetails(['first_name' => '']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        } catch (\Exception $e) {}

        $this->fail('Empty first name should not pass validation.');
    }

    /** @test */
    public function first_name_with_less_than_3_characters_should_not_pass_validation()
    {
        $data = $this->generateUserDetails(['first_name' => 'ab']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('First name with less than 3 characters should not pass validation.');
    }

    /** @test */
    public function last_name_is_required()
    {
        $data = $this->generateUserDetails(['last_name' => '']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        } catch (\Exception $e) {}

        $this->fail('Empty last name should not pass validation.');
    }

    /** @test */
    public function last_name_with_less_than_3_characters_should_not_pass_validation()
    {
        $data = $this->generateUserDetails(['last_name' => 'ab']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('First name with less than 3 characters should not pass validation.');
    }

    /** @test */
    public function email_is_required()
    {
        $data = $this->generateUserDetails(['email' => '']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        } catch (\Exception $e) {}

        $this->fail('Empty email should not pass validation.');
    }

    /** @test */
    public function invalid_email_should_not_pass_validation()
    {
        $data = $this->generateUserDetails(['email' => 'invalid_email']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('Invalid email should not pass validation.');
    }

    /** @test */
    public function registering_with_already_registered_email_should_not_pass_validation()
    {
        factory(User::class)->create(['email' => 'johndoe@gmail.com']);
        $this->assertCount(1, User::all());
        $data = $this->generateUserDetails(['email' => 'johndoe@gmail.com']);

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(1, User::all());

            return;
        } catch (\Exception $e) {}

        $this->fail('User registered with duplicate email should not pass validation.');
    }

    /** @test */
    public function password_is_required()
    {
        $data = $this->generateUserDetails(['password' => '']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        } catch (\Exception $e) {}

        $this->fail('Empty password should not pass validation.');
    }

    /** @test */
    public function password_with_less_than_6_characters_should_not_pass_validation()
    {
        $data = $this->generateUserDetails(['password' => 'abc']);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('Password with less than 6 characters should not pass validation.');
    }

    /** @test */
    public function password_should_not_pass_validaton_if_it_does_not_match_password_confirmation()
    {
        $data = $this->generateUserDetails([
            'password' => '123456',
            'password_confirmation' => 'abc',
        ]);
        $this->assertCount(0, User::all());

        try {
            $response = $this->post('register', $data);
        } catch (ValidationException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('Incorrect password confirmation should not pass validation.');
    }

    /** @test */
    public function user_registered_event_is_fired_on_successful_registration()
    {
        Event::fake();
        $this->assertCount(0, User::all());
        $data = $this->generateUserDetails(['email' => 'john@doe.com']);

        $this->post('register', $data);

        $this->assertCount(1, User::all());
        $user = User::first();
        Event::assertDispatched(UserRegistered::class, function ($event) use ($user) {
            return $event->user->is($user);
        });
    }

    public function validUserDetails()
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];
    }

    public function generateUserDetails($data = [])
    {
        return array_merge($this->validUserDetails(), $data);
    }
}
