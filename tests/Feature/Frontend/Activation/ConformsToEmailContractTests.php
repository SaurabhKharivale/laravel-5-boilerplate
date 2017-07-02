<?php

namespace Tests\Feature\Frontend\Activation;

use App\User;
use App\Mail\ActivationEmail;
use Illuminate\Support\Facades\Mail;
use Tests\Support\Assertions\UserAssertions;

trait ConformsToEmailContractTests
{
    use UserAssertions;

    abstract function fireEventToSendActivationEmail($user);

    /** @test */
    public function activation_email_is_sent_to_user_email_address_after_an_event()
    {
        Mail::fake();
        $user = factory(User::class)->create(['email' => 'john@test.com']);

        $this->fireEventToSendActivationEmail($user);

        Mail::assertSent(ActivationEmail::class, function ($mail) use ($user) {
            return $mail->hasTo('john@test.com') &&
                    $mail->user->id == $user->id;
        });
    }

    /** @test */
    public function correct_activation_token_is_provided_to_email_template()
    {
        Mail::fake();
        $user = factory(User::class)->create(['email' => 'john@test.com']);

        $this->fireEventToSendActivationEmail($user);

        $token = \DB::table('user_activations')->where('user_id', $user->id)->first()->token;
        Mail::assertSent(ActivationEmail::class, function ($mail) use ($user, $token) {
            return $mail->token == $token;
        });
    }

    /**
    *   The above test only checks that a $actication_token variable is available to the view.
    *   It does not test if the correct activation link was sent through the mail.
    *   As of laravel 5.4 there is no way to fluently check for the body of a mail.
    *   So, skipping this test check for mow. I will implement it when a better solution or hack is available.
    */
    // TODO: Need to test mail body actually contains the activation link.

    /** @test */
    public function correct_subject_is_provided_to_email_template()
    {
        Mail::fake();
        $user = factory(User::class)->create(['email' => 'john@test.com']);

        $this->fireEventToSendActivationEmail($user);

        Mail::assertSent(ActivationEmail::class, function ($mail) use ($user) {
            $mail->build();

            return 'Confirm your email address!' == $mail->subject;
        });
    }
}
