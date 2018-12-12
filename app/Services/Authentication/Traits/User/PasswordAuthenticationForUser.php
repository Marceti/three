<?php
/**
 * Created by PhpStorm.
 * User: marce
 * Date: 12.12.2018
 * Time: 14:56
 */

namespace App\Services\Authentication\Traits\User;

use App\Mail\Authentication\RegistrationConfirmationEmail;
use App\Mail\Authentication\resetPasswordConfirmationEmail;
use App\Mail\Authentication\ResetPasswordEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

trait PasswordAuthenticationForUser {

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
     * Registration
     * Creates a new token for this user and saves it to cache for a specific duration, default = 1week
     * @return mixed
     * @throws \Exception
     */
    public function generateLoginToken($duration = 10080)
    {
        $loginToken = str_random(50);

        //user:{ID}:login:token => {TOKEN}
        $userLoginTokenKey = 'user:'. $this->id.':login:token';
        cache()->put($userLoginTokenKey,$loginToken,$duration);

        //login:token:{TOKEN} => {ID}
        $tokenKeyUser = 'login:token:'.$loginToken.':user';
        cache()->put($tokenKeyUser,$this->id,$duration);

        return $this;
    }

    /**
     * Registration
     * Triggers a Job with the purpose of sending an email
     */
    public function sendRegistrationEmail()
    {
        $loginToken=$this->extractTokenFromCache('login');

        $url = url('register/token',$loginToken);
        Mail::to($this)->queue(new RegistrationConfirmationEmail($url));
    }




    /**
     * Registration - Authenticate
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
     * Sends the confirmation Email to this user
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
     * Session-resetPassword
     * Sends an email with token-link
     */
    public function sendResetEmail()
    {
        $resetToken=$this->extractTokenFromCache('reset')['reset_token'];

        $url = url('/'.$this->id.'/resetPassword',$resetToken);
        Mail::to($this)->queue(new ResetPasswordEmail($url));
    }

    /**
     * Session-resetPassword
     * extracts token from cache, for this user
     * Token Types : [reset , login]
     * @return mixed
     * @throws \Exception
     */
    private function extractTokenFromCache($tokenType)
    {
        $userStoreKey = 'user:'. $this->id.':'.$tokenType.':token';

        return cache()->get($userStoreKey);
    }



    /**
     * Session-resetPassword
     * Checks: if the saved in cache (reset_token) = this token
     *                  AND
     *         if the saved in cache (remember_token) is still the same as the users rememberToken
     * @param $token
     * @return bool
     * @throws \Exception
     */
    public function activeResetToken($token)
    {
        $resetTokens=extractTokenFromCache('reset');

        $resetToken=$this->$resetTokens['reset_token'];
        $rememberToken=$this->$resetTokens['remember_token'];

        return ($this->remember_token==$rememberToken && $token==$resetToken);
    }

    /**
     * Session-resetPassword
     * extracts user by Token
     * Token Types : [reset , login]
     * @param $token
     * @return mixed
     * @throws \Exception
     */
    public static function byToken($token,$tokenType)
    {
        $tokenKeyUser = $tokenType.':token:'.$token.':user';
        $userIdFromCash = cache()->get($tokenKeyUser,null);
        return ($userIdFromCash ? static::where('id',$userIdFromCash)->first() : null);
    }
}