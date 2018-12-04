<?php

namespace App;

use App\Jobs\RegistrationEmailJob;
use App\Mail\Authentication\RegistrationConfirmationEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class LoginToken extends Model
{

    protected $fillable=['user_id','token'];

    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
     * Creates a new token for the given user if it does not exist, otherwise it will return the existing one
     * @param User $user
     * @return mixed
     */
    public static function generateFor(User $user)
    {
        if (! $user->loginToken) {
            return static::create([
                'user_id'=>$user->id,
                'token' =>str_random(50)
            ]);
        }
        return $user->loginToken;
    }

    /**
     *Triggers a Job with the purpose of sending an email
     */
    public function sendRegistrationEmail()
    {
        $url = url('register/token',$this->token);
        Mail::to($this->user)->queue(new RegistrationConfirmationEmail($url));
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
