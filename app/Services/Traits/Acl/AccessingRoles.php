<?php
/**
 * Created by PhpStorm.
 * User: marce
 * Date: 14.12.2018
 * Time: 13:43
 */

namespace App\Services\Traits\Acl;


trait AccessingRoles {

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole(Role $role)
    {
        $this->roles()->save($role);
    }


    public function hasRole($roles)
    {
        return $roles->intersect($this->roles)->count();
    }

}