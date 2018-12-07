<?php

namespace App;

use App\Mail\Authentication\resetPasswordConfirmationEmail;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class User extends Authenticatable
{
    use Notifiable;

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

    /**
     * One to One realtionship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resetTokens()
    {
        return $this->hasMany('App\ResetToken');
    }

    /**
     * If user was already authenticated returns FALSE, otherwise will authenticate and return TRUE
     * @return bool
     */
    public function firstAuthentication()
    {
        if (! $this->email_verified_at){
            $this->update(['email_verified_at'=>Carbon::now()]);
            return true;
        }
        return false;
    }

    /**
     * Checks if the user has email verified
     * @return bool
     */
    public function isEmailVerified() : bool
    {
        return true && $this->email_verified_at;
    }

    /**
     * extracts user by email
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    public static function byEmail($email)
    {
        return static::where('email',$email)->first();
    }

    /**
     * @return mixed
     */
    private function refreshRememberToken()
    {
        return tap($this,function(){
            $this->remember_token = str_random(50);
            $this->save();
        });
    }

    /**
     * @param $password
     * @return
     */
    public function changePassword($password)
    {
        return tap($this)->update(['password'=>$password])
            ->refreshRememberToken()
            ->sendResetConfirmationEmail($password);

    }

    /**
     *Triggers a Job with the purpose of sending an email
     */
    public function sendResetConfirmationEmail($password)
    {
        $url = url(route('login'));
        Mail::to($this)->queue(new ResetPasswordConfirmationEmail($url,$password));
    }


}
