<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable =['name','label'];
    public function getRouteKeyName()
    {
        return 'name';
    }

    public function setUpdatedAt($value)
    {
        return NULL;
    }

    public function setCreatedAt($value)
    {
        return NULL;
    }


    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Gives the specified permission to this role
     * @param Permission $permission
     * @return bool
     * True = if it's a new permission that is added to this role
     * False = if already has this permission
     */
    public function givePermissionTo(Permission $permission)
    {
        if(! $this->hasPermissionTo($permission)){
            $this->permissions()->save($permission);
            return true;
        }
        return false;
    }

    /**
     * Checks if this role has a given permission
     * @param Permission $permission
     * @return mixed
     */
    private function hasPermissionTo(Permission $permission)
    {
        return $this->permissions->contains($permission);
    }

}
