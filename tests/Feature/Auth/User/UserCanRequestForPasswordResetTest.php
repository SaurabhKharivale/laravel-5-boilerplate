<?php

namespace Tests\Feature\Auth\User;

use App\User;
use Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\Feature\Auth\CanRequestForPasswordResetTests;

class UserCanRequestForPasswordResetTest extends TestCase
{
    use CanRequestForPasswordResetTests;

    private $reset_form_url = '/password/reset';

    private $reset_email_url = '/password/email';

    protected function getResetLinkUrl()
    {
        return $this->reset_email_url;
    }

    protected function passwordResetInitiationFormURL()
    {
        return $this->reset_form_url;
    }

    protected function getNotificationClass()
    {
        return ResetPassword::class;
    }


    protected function createUserOrAdmin($email)
    {
        return factory(User::class)->create(['email' => $email]);
    }
}
