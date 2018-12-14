<?php
/**
 * Created by PhpStorm.
 * User: marce
 * Date: 14.12.2018
 * Time: 13:43
 */

namespace App\Services\Traits\Acl;


use App\Role;

trait AccessingRoles {

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Checks if this user has the given role or only one of the given roles
     * @param $roles
     * @return mixed
     */
    public function hasRole($roles)
    {
        if(is_array($roles)){
            return $roles->intersect($this->roles)->count();
        }
        return $this->roles->contains($roles);
    }

    /**
     * Gives the specified permission to this role
     * @param Role $role
     * @return bool
     * True = if it's a new permission that is added to this role
     * False = if already has this permission
     */
    public function assignRole(Role $role)
    {
        if(! $this->hasRole($role)){
            $this->roles()->save($role);
            return true;
        }
        return false;
    }




}