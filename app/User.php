<?php

namespace App;

use App\Mail\Authentication\resetPasswordConfirmationEmail;
use App\Mail\Authentication\ResetPasswordEmail;
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
            $this->setEmailVerified();
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
    private function setEmailVerified()
    {
        return tap($this,function()
        {
            $this->email_verified_at = Carbon::now();
            $this->save();
        });
    }

    /**
     * @return mixed
     */
    public function refreshRememberToken()
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
        return true;
    }


    /**
     * Creates Reset token ,
     *      Writes to Cache : user:ID:reset:token => [reset_token, remember_token]
     *      Writes to Cache : reset:token:TOKEN:user => userID
     * @return mixed
     * @throws \Exception
     */
    public function createResetToken()
    {
        $duration=1440;  //minutes

        $userTokenKey = 'user:'. $this->id.':reset:token';

        $resetToken = str_random(50);

        $tokenKeyUser = 'reset:token:'.$resetToken.':user';

        cache()->put($userTokenKey,[
            'reset_token'=>$resetToken,
            'remember_token'=>$this->remember_token,
            ],$duration);

        cache()->put($tokenKeyUser,$this->id,$duration);

        return $this;

    }

    /**
     *Sends an email with token-link
     */
    public function sendResetEmail()
    {
        $resetToken=$this->extractResetTokenFromCache();

        $url = url('/'.$this->id.'/resetPassword',$resetToken);
        Mail::to($this)->queue(new ResetPasswordEmail($url));
    }

    /**
 * extracts reset_token from cache, for this user
 * @return mixed
 * @throws \Exception
 */
    private function extractResetTokenFromCache()
    {
        $userStoreKey = 'user:'. $this->id.':reset:token';

        $token=cache()->get($userStoreKey)['reset_token'];

        return $token;
    }

    /**
     * extracts remember_token from cache, for this user
     * @return mixed
     * @throws \Exception
     */
    private function extractRememberMeTokenFromCache()
    {
        $userStoreKey = 'user:'. $this->id.':reset:token';

        $token=cache()->get($userStoreKey)['remember_token'];

        return $token;
    }


    /**
     * Checks: if the saved in cache (reset_token) = this token
     *                  AND
     *         if the saved in cache (remember_token) is still the same as the users rememberToken
     * @param $token
     * @return bool
     * @throws \Exception
     */
    public function activeResetToken($token)
    {
        $resetToken=$this->extractResetTokenFromCache();
        $rememberToken=$this->extractRememberMeTokenFromCache();


        //TODO Auth-MUSAI - Mai verifica O data reset passwords, ca acum este in cache
       // dd(["resetTokenC"=>$resetToken,"resetTokenG"=>$token,"rememberTokenC"=>$rememberToken,"rememberTokenG"=>$this->remember_token]);
        return ($this->remember_token==$rememberToken && $token==$resetToken);
    }

    /**
     * extracts user by resetToken
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public static function byResetToken($token)
    {
        $tokenKeyUser = 'reset:token:'.$token.':user';
        $userIdFromCash = cache()->get($tokenKeyUser,null);
        return ($userIdFromCash ? static::where('id',$userIdFromCash)->first() : null);
    }


}
