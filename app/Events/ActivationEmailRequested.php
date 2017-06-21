<?php

namespace App\Events;

use App\User;

class ActivationEmailRequested implements SendEmailEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
