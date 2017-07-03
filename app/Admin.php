<?php

namespace App;

use App\Permission;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPassword as AdminResetPasswordNotification;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password'
    ];

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

    public function isSuperAdmin()
    {
        return $this->roles->contains('name', 'super-admin');
    }

    public function assignRole($role)
    {
        if(! is_array($role)) {
            $role = [$role];
        }

        $this->roles()->saveMany($role);
    }

    public function hasPermissionTo($permission)
    {
        $permission = Permission::where('name', $permission)->first();

        if(! $permission) {
            return false;
        }

        return $this->hasRole($permission->roles);
    }

    public function hasRole($role)
    {
        if(is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if($role instanceOf Role) {
            return $this->roles->contains('name', $role->name);
        }

        return !! $role->intersect($this->roles)->count();
    }
}
