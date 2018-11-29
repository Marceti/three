<?php

namespace App;

use App\Mail\Authentication\ResetPasswordEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class ResetToken extends Model {

    protected $fillable = ['user_id', 'token','user_token'];

    public function getRouteKeyName()
    {
        return 'token';
    }

       /**
     *Triggers a Job with the purpose of sending an email
     */
    public function sendResetEmail()
    {
        $url = url('resetPassword/token',$this);
        Mail::to($this->user)->queue(new ResetPasswordEmail($url));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param $user
     * @return mixed
     */
    public static function createToken($user)
    {
        return static::create([
            'user_id' => $user->id,
            'token'   => Carbon::now()->format('Y-m-d').str_random(50),
            'user_token' => $user->remember_token,

        ]);
    }

    /**
     * @param $token
     * @return mixed
     */
    public static function byToken($token)
    {
        return static::where('token',$token)->firstorfail();
    }


    public function active()
    {
        return ($this->user->remember_token==$this->user_token);
    }
}
