<?php

namespace App;



use App\Services\Authentication\Traits\User\PasswordAuthenticationForUser;

use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Mockery\Exception;


class User extends Authenticatable
{
    use Notifiable;
    use PasswordAuthenticationForUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * One to One realtionship
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loginToken()
    {
        return $this->hasOne('App\LoginToken');
    }






}
