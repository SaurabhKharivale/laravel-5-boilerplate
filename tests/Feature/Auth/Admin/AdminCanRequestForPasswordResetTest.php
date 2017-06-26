<?php

namespace Tests\Feature\Auth\Admin;

use App\Admin;
use Tests\TestCase;
use App\Notifications\AdminResetPassword;
use Tests\Feature\Auth\CanRequestForPasswordResetTests;

class AdminCanRequestForPasswordResetTest extends TestCase
{
    use CanRequestForPasswordResetTests;

    private $reset_form_url = '/admin/password/reset';

    private $reset_email_url = '/admin/password/email';

    protected function passwordResetInitiationFormURL()
    {
        return $this->reset_form_url;
    }

    protected function getResetLinkUrl()
    {
        return $this->reset_email_url;
    }

    protected function getNotificationClass()
    {
        return AdminResetPassword::class;
    }

    protected function createUserOrAdmin($email)
    {
        return factory(Admin::class)->create(['email' => $email]);
    }
}
