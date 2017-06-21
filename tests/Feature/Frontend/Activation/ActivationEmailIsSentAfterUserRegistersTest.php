<?php

namespace Tests\Feature\Frontend\Activation;

use Tests\TestCase;
use App\Events\UserRegistered;

class ActivationEmailIsSentAfterUserRegistersTest extends TestCase
{
    use ConformsToEmailContractTests;

    public function fireEventToSendActivationEmail($user)
    {
        $this->assertUserDoesNotHaveActivationToken($user);

        event(new UserRegistered($user));

        $this->assertUserHasActivationToken($user);
    }
}
