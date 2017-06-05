<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\SocialAccount;

class UserTest extends TestCase
{
    /** @test */
    public function user_last_name_can_be_nullable()
    {
        $this->assertCount(0, User::all());
        factory(User::class)->create(['last_name' => null]);

        $this->assertCount(1, User::all());
        $user = User::first();
        $this->assertNull($user->last_name);
    }

    /** @test */
    public function user_can_have_multiple_social_accounts()
    {
        $user = factory(User::class)->create();
        $account_one = factory(SocialAccount::class)->create(['provider_id' => '123456', 'provider' => 'google', 'user_id' => $user->id]);
        $account_two = factory(SocialAccount::class)->create(['provider_id' => 'abcxyz', 'provider' => 'twitter', 'user_id' => $user->id]);
        $account_three = factory(SocialAccount::class)->create(['provider_id' => 'zzzaaa', 'provider' => 'facebook', 'user_id' => $user->id]);

        $found_social_accounts = $user->socialAccounts()->get();

        $this->assertTrue($found_social_accounts->contains($account_one));
        $this->assertTrue($found_social_accounts->contains($account_two));
        $this->assertTrue($found_social_accounts->contains($account_three));
    }
}
