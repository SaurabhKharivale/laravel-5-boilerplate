<?php

namespace App\Listeners;

use Mail;
use App\Mail\ActivationEmail;
use App\Events\SendEmailEvent;
use App\Events\ActivationEmailRequested;
use App\Repositories\ActivationRepository;

class SendActivationEmail
{
    protected $activationRepo;

    public function __construct(ActivationRepository $activationRepo)
    {
        $this->activationRepo = $activationRepo;
    }

    public function handle(SendEmailEvent $event)
    {
        $user = $event->user;

        $token = ($event instanceOf ActivationEmailRequested) ?
                    $this->activationRepo->getToken($user) :
                    $this->activationRepo->createToken($user);

        Mail::to($user->email)
            ->send(new ActivationEmail($user, $token));
    }
}
