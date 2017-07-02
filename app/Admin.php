<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPassword as AdminResetPasswordNotification;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'admin';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role)
    {
        if(! is_array($role)) {
            $role = [$role];
        }

        $this->roles()->saveMany($role);
    }
}
