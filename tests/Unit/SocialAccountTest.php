<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\SocialAccount;

class SocialAccountTest extends TestCase
{
    /** @test */
    public function can_retrive_associated_user_with_social_account()
    {
        $user = factory(User::class)->create(['email' => 'test@gmail.com']);
        $social_accounts = factory(SocialAccount::class)->create(['provider' => 'google', 'provider_id' => '123456', 'user_id' => $user->id]);

        $user = $social_accounts->first()->user;

        $this->assertNotNull($user, 'User not retrived using social account');
        $this->assertEquals('test@gmail.com', $user->email);
    }
}
