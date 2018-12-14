<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable =['name','label'];

    public function setUpdatedAt($value)
    {
        return NULL;
    }

    public function setCreatedAt($value)
    {
        return NULL;
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
