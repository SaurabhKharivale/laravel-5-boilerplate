<?php

namespace Tests\Traits;

use App\User;

trait UserAssertions
{
    public function assertUserDoesNotExistsWithEmail($email)
    {
        $this->assertCount(0, User::where('email', $email)->get(), "User should not exist with email $email");
    }

    public function assertUserExistsWithEmail($email)
    {
        $users = User::where('email', $email)->get();

        $this->assertCount(1, $users, "Not able to find a user with email $email");

        return $users->first();
    }

    public function assertSocialAccountIsLinkedToUser($user, $details)
    {
        $social_account = $user->socialAccounts->first();
        $this->assertNotNull($social_account, 'No social account is linked with the user.');
        $this->assertEquals($details['provider_id'], $social_account->provider_id, 'Provider id did not match with linked social account.');
        $this->assertEquals($details['provider'], $social_account->provider, 'Provider did not match with linked social account.');
    }

    public function assertNoSocialAccountIsLinkedWithUser($user)
    {
        $this->assertCount(0, $user->fresh()->socialAccounts, 'User must not have any linked social account.');
    }

    public function assertPasswordMatches($plain, $hashed)
    {
        $this->assertTrue(\Hash::check($plain, $hashed), "User password did not match: $plain" );
    }
}
