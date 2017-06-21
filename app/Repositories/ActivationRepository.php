<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivationRepository
{
    protected $table = 'user_activations';

    private function generateRandomToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createToken(User $user)
    {
        $token = $this->generateRandomToken();
        \DB::table($this->table)->insert([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $token;
    }

    public function getToken(User $user)
    {
        $record = \DB::table($this->table)->where('user_id', $user->id)->first();

        return $record->token;
    }

    public function activateUserAccount($token)
    {
        $user = $this->findUserByToken($token);
        $user->verified = true;
        $user->save();

        $this->deleteToken($user);

        return $user;
    }

    public function deleteToken(User $user)
    {
        \DB::table($this->table)->where('user_id', $user->id)->delete();
    }

    public function findUserByToken($token)
    {
        $record = \DB::table($this->table)->where('token', $token)->first();

        if(! $record) {
            throw new ModelNotFoundException("Error Processing Request", 1);
        }

        return User::find($record->user_id);
    }
}
