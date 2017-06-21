<?php

namespace Tests\Unit\Repositories;

use App\User;
use Tests\TestCase;
use Tests\Traits\UserAssertions;
use App\Repositories\ActivationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivationRepositoryTest extends TestCase
{
    use UserAssertions;

    protected $activationRepo;

    public function setUp()
    {
        parent::setUp();

        $this->activationRepo = new ActivationRepository;
    }

    /** @test */
    public function can_create_activation_token_for_a_user()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        $this->assertUserDoesNotHaveActivationToken($user);

        $created_token = $this->activationRepo->createToken($user);

        $this->assertUserHasActivationToken($user);
        $this->assertEquals(64, strlen($created_token));
        $token_in_db = $this->getActivationTokenFromDB($user);
        $this->assertEquals($created_token, $token_in_db);
    }

    /** @test */
    public function can_get_activation_token_for_a_user()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertUserHasActivationToken($user);

        $token = $this->activationRepo->getToken($user);

        $this->assertEquals('VALID_ACTIVATION_TOKEN', $token);
    }

    /** @test */
    public function can_activate_user_account_with_valid_token()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_ACTIVATION_TOKEN',
            'user_id' => $user->id,
        ]);
        $this->assertEmailIsNotVerified($user);

        $this->activationRepo->activateUserAccount('VALID_ACTIVATION_TOKEN');

        $this->assertEmailIsVerified($user);
    }

    /** @test */
    public function can_find_user_using_activation_token()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        \DB::table('user_activations')->insert([
            'token' => 'VALID_TOKEN',
            'user_id' => $user->id,
        ]);

        $found_user = $this->activationRepo->findUserByToken('VALID_TOKEN');

        $this->assertEquals('john@test.com', $found_user->email);
        $this->assertTrue($found_user->is($user));
    }

    /** @test */
    public function if_no_user_found_using_activation_token_then_exception_is_thrown()
    {
        try {
            $this->activationRepo->findUserByToken('INVALID_TOKEN');
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('User was found with an invalid token.');
    }

    /** @test */
    public function can_delete_activation_token_for_a_user()
    {
        $user = factory(User::class)->create(['email' => 'john@test.com']);
        $this->activationRepo->createToken($user);
        $this->assertUserHasActivationToken($user);

        $this->activationRepo->deleteToken($user);

        $this->assertUserDoesNotHaveActivationToken($user);
    }

    public function getActivationTokenFromDB($user)
    {
        return \DB::table('user_activations')
                    ->where('user_id', $user->id)
                    ->first()
                    ->token;
    }
}
