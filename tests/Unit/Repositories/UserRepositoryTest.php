<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\SocialAccount;
use Tests\Traits\UserAssertions;
use App\Repositories\UserRepository;
use App\Exceptions\AccountCreationFailedException;
use App\Exceptions\SocialAccountNotLinkedException;

class UserRepositoryTest extends TestCase
{
    use UserAssertions;

    protected $userRepo;

    public function setUp()
    {
        parent::setUp();

        $this->userRepo = new UserRepository;
    }

    /** @test */
    public function account_is_created_for_unregistered_user_after_successful_authentication()
    {
        $this->assertUserDoesNotExistsWithEmail('test@gmail.com');

        $user = $this->userRepo->authenticate([
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'provider' => 'google',
            'provider_id' => '123456',
        ]);

        $this->assertUserExistsWithEmail('test@gmail.com');
        $this->assertEquals('test@gmail.com', $user->email);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
    }

    /** @test */
    public function can_create_user_account()
    {
        $user = $this->userRepo->createUser([
            'name' => 'Test user',
            'email' => 'test@gmail.com',
        ]);

        $this->assertUserExistsWithEmail('test@gmail.com');
    }

    /** @test */
    public function can_create_user_account_linked_with_social_account()
    {
        $user = $this->userRepo->createAccount([
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'provider' => 'google',
            'provider_id' => '123456',
        ]);

        $this->assertEquals('Test user', $user->first_name);
        $this->assertEquals('test@gmail.com', $user->email);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
    }

    /** @test */
    public function can_link_social_account_to_user()
    {
        $user = factory(User::class)->create(['email' => 'test@gmail.com']);

        $user_with_social = $this->userRepo->linkSocialAccount($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);

        $this->assertTrue($user_with_social->is($user));
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
    }

    /** @test */
    public function user_can_relogin_to_his_existing_account_using_social_login()
    {
        $this->createUserWithSocialAccount([
            'email' => 'test@gmail.com',
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
        $this->assertUserExistsWithEmail('test@gmail.com');

        $user = $this->userRepo->authenticate([
            'name' => 'Test user',
            'email' => 'test@gmail.com',
            'provider_id' => '123456',
            'provider' => 'google'
        ]);

        $this->assertEquals('test@gmail.com', $user->email);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
    }

    /** @test */
    public function can_find_an_user_account_by_email()
    {
        factory(User::class)->create(['email' => 'test@gmail.com']);
        $this->assertUserExistsWithEmail('test@gmail.com');

        $user = $this->userRepo->findByEmailOrSocialAccount([
            'email' => 'test@gmail.com',
        ]);

        $this->assertEquals('test@gmail.com', $user->email);
    }

    /** @test */
    public function can_find_an_user_account_by_social_account()
    {
        $this->createUserWithSocialAccount([
            'email' => 'test@gmail.com',
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
        $this->assertUserExistsWithEmail('test@gmail.com');

        $user = $this->userRepo->findByEmailOrSocialAccount([
            'provider' => 'google',
            'provider_id' => '123456',
        ]);

        $this->assertEquals('test@gmail.com', $user->email);
        $this->assertSocialAccountIsLinkedToUser($user, [
            'provider' => 'google',
            'provider_id' => '123456',
        ]);
    }

    /** @test */
    public function null_is_returned_when_no_user_is_found_by_email_or_social_account()
    {
        $user = $this->userRepo->findByEmailOrSocialAccount([
            'email' => 'user_does_not_exists@gmail.com',
            'provider' => 'some_provider',
            'provider_id' => '123456',
        ]);

        $this->assertNull($user);
    }

    /** @test */
    public function an_exception_is_thrown_when_user_account_creation_fails()
    {
        $this->assertCount(0, User::all());

        try {
            $this->userRepo->createAccount([
                'name' => null,
                'email' => null,
                'provider' => 'google',
                'provider_id' => '123456',
            ]);
        } catch (AccountCreationFailedException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('User should not exist in system as invalid name and email was provided.');
    }

    /** @test */
    public function an_exception_is_thrown_when_invalid_social_details_are_provided()
    {
        $user = factory(User::class)->create(['email' => 'test@gmail.com']);
        $this->assertNoSocialAccountIsLinkedWithUser($user);

        try {
            $this->userRepo->linkSocialAccount($user, [
                'provider' => null,
                'provider_id' => null
            ]);
        } catch(SocialAccountNotLinkedException $e) {
            $this->assertNoSocialAccountIsLinkedWithUser($user);

            return;
        }

        $this->fail('Social account with invalid details should throw an exception (Social AccountNotLinkedException).');
    }

    /** @test */
    public function if_linking_social_account_fails_then_user_account_should_not_exist_in_system()
    {
        $this->assertCount(0, User::all());

        try {
            $this->userRepo->createAccount([
                'name' => 'Test user',
                'email' => 'test@gmail.com',
                'provider' => null,
                'provider_id' => null,
            ]);
        } catch (AccountCreationFailedException $e) {
            $this->assertCount(0, User::all());

            return;
        }

        $this->fail('User should not exist in system as social account linking failed.');
    }

    public function createUserWithSocialAccount($user)
    {
        factory(SocialAccount::class)->create([
            'provider' => $user['provider'],
            'provider_id' => $user['provider_id'],
            'user_id' => factory(User::class)->create(['email' => $user['email']])->id
        ]);
    }
}
