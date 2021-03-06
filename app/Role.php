<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'label', 'description',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function grantPermission($permission)
    {
        if(! is_array($permission)) {
            $permission = [$permission];
        }

        return $this->permissions()->saveMany($permission);
    }
}
