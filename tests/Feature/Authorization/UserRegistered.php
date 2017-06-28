<?php

namespace App\Events;

use App\User;

class UserRegistered implements SendEmailEvent
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
