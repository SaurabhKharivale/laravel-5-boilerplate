<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Carbon\Carbon;

trait PasswordResetTrait
{
    public function assertPasswordResetTokenExists($email)
    {
        $record = $this->getPasswordReset($email);
        $this->assertCount(1, $record, "Password reset token not found for user with email '{$email}'");
        $this->assertNotNull($record->first()->token);
    }

    public function assertPasswordResetTokenDoesNotExists($email)
    {
        $record = $this->getPasswordReset($email);
        $this->assertCount(0, $record);
    }

    public function getPasswordReset($email)
    {
        return \DB::table('password_resets')->where('email', $email)->get();
    }

    public function getPasswordResetToken($email)
    {
        $record = $this->getPasswordReset($email);

        return count($record) ? $record->first()->token : null;
    }

    public function assertUserPasswordIs($plain_text_password, $user)
    {
        $this->assertTrue(\Hash::check($plain_text_password, $user->fresh()->password), 'Password did not match.');
    }

    public function assertAdminPasswordIs($plain_text_password, $user)
    {
        $this->assertUserPasswordIs($plain_text_password, $user);
    }

    public function generatePasswordResetToken($user)
    {
        $repo = $this->getTokenRepository($user);

        return $repo->create($user);
    }

    public function generateExpiredPasswordResetToken($user)
    {
        $repo = $this->getTokenRepository($user);

        $token = $repo->create($user);

        \DB::table('password_resets')->where('email', $user->email)
                ->update(['created_at' => Carbon::parse('-1 week')]);

        return $token;
    }

    public function getTokenRepository($user)
    {
        $key = app('config')['app.key'];

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return new DatabaseTokenRepository(
            app('db')->connection(),
            app('hash'),
            'password_resets',
            $key
        );
    }
}
