<?php
/**
 * Created by PhpStorm.
 * User: marce
 * Date: 13.11.2018
 * Time: 14:42
 */

namespace App\Services\Authentication;


use App\Contracts\Authentication\PasswordAuthenticator;
use App\LoginToken;
use App\ResetToken;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Services\SessionManager;

class AuthenticatesUser implements PasswordAuthenticator {


    /**
     * Invites the user by : creating user or grabbing user, creating token for this user, sending invite email with token link
     * @param $credentials
     * @return RedirectResponse
     * @throws \Exception
     */
    public function invite($credentials)
    {
        $user = $this->resolveUser($credentials);

        if ($user)
        {
            $this->createLoginToken($user)
                ->sendRegistrationEmail();

            return redirect()->route('login')->with('message', Lang::get('authentication.please_confirm'));
        }

        return redirect()->back()->withErrors(Lang::get('authentication.credentials_check'));

    }

    /**
     * Authenticates user with the given Login token
     * @param LoginToken $token
     * @return RedirectResponse|\Illuminate\Routing\Redirect
     */
    public function authenticate(LoginToken $token)
    {
        //TODO: Auth-Maybe: Se Poate pune un filtru de numar de incercari sau un landing page cu I'm not a robot
        $user = $token->user;

        $message = ($user->firstAuthentication() ?
            Lang::get('authentication.confirmation', ['name' => $user->name]) :
            Lang::get('authentication.already_confirmed', ['name' => $user->name]));

        return redirect()->route('login')->with('message', $message);

    }

    /**
     * Attempts to Log in the user
     * @param $credentials
     * @return RedirectResponse
     * @throws \Exception
     */
    public function login($credentials)
    {

        if (! $this->toManyAttemptsOnUser($credentials['email'],request()->ip(),5,2) &&
            ! $this->toManyAttemptsOnIp(request()->ip(),8,2) &&
            Auth::attempt($credentials))
        {
            $this->rememberUser($credentials);

            return redirect()->intended(request('home'));
        }

        return redirect()->route('login')->withErrors(Lang::get('authentication.wrong_password'));
    }

    /**
     * Logs out the user
     * @return RedirectResponse
     */
    public function logOut()
    {
        Auth::logout();

        return redirect()->route('home')->with('message', Lang::get('authentication.log_out'));
    }



    /**
     * grabs the user with given email, for user creates new resetToken, sends the link on users email
     * @return $this|RedirectResponse
     * @throws \Exception
     */
    public function resetPassword($email)
    {
        $user = User::byEmail($email);

        $user->createResetToken($user)
            ->sendResetEmail();

        return redirect()->route('login')->with('message', Lang::get('authentication.reset_password_message'));
    }

    /**
     * For the returned token, grabs the user for this token and generates the view with to user remember_token
     * @param $user
     * @param ResetToken $token
     * @return $this
     */
    public function createNewPasswordForm($user, $token)
    {
        abort_if(! $user->activeResetToken($token), 403, Lang::get('authentication.reset_password_expired'));

        return view("authentication.login.changePasswordForm")->with('resetToken', $token);

    }


    /**
     * Attempts to change password for the user with email address
     * @param $password
     * @param $resetToken
     * @return RedirectResponse
     * @throws \Exception
     */
    public function changePassword($password, $resetToken)
    {
        $user=User::byResetToken($resetToken);

        abort_if(! $user,400,"This operation is not valid");

        $user->changePassword($password);

        return redirect()->route('login')->with('message', Lang::get('authentication.password_reset_successful'));
    }




    //*************************************************************************************************
    //---------------------------------Local Methods----------------------------------------------------
    //*************************************************************************************************


    /**
     * If user with email exists returns it, otherwise creates a user
     * @param $credentials
     * @return mixed
     * @throws \Exception
     */

    private function resolveUser($credentials)
    {
        $user = User::byEmail($credentials['email']);

        if ($user)
        {
            return $user;
        }
        else
        {
            return $this->createUser($credentials);
        }
    }

    /**
     * Saves the credentials if remember-me is on , and creates unconfirmed user
     * @param $credentials
     * @return mixed
     */
    private function createUser($credentials)
    {
        $this->rememberUser($credentials);
        $user= User::create($credentials);
        return $user->refreshRememberToken();
    }

    /**
     * @param $user
     * @return mixed
     */
    private function createLoginToken($user)
    {
        return LoginToken::generateFor($user);
    }

    /**
     * If checkbox , remembers the user in current session
     * @param $credentials
     */
    private function rememberUser($credentials)
    {
        if (array_key_exists('remember-me', request()->input()))
        {
            //TODO : Auth-Maybe De rezolvat : Remember Me - prin alta metoda
            SessionManager::rememberUser($credentials);
        }
    }

    /**
     * Increments the number of attempts for specific EMAIL and IP and
     *      if this value exceeds the maximum allowed attempts returns ABORT(429) otherwise FALSE
     * @param $email
     * @param $ip
     * @param $maxNoAttempts
     * @param $waitTimeMinutes
     * @return bool
     * @throws \Exception
     */
    private function toManyAttemptsOnUser($email, $ip, $maxNoAttempts, $waitTimeMinutes)
    {
        $storeKey = 'login:attempts:email:' . strtolower($email) . ':ip:' . $ip;

        if(! cache()->add($storeKey,1,$waitTimeMinutes)){
            cache()->increment($storeKey);
        }

        abort_if(cache()->get($storeKey, 0) >= $maxNoAttempts,
            429, "OUCH !!! To many attempts! , Try again in (" . $waitTimeMinutes . " min)");
        return false;
    }

    /**
     * Increments the number of attempts for specific IP and
     *      if this value exceeds the maximum allowed attempts returns ABORT(429) otherwise FALSE
     * @param $ip
     * @param $maxNoAttempts
     * @param $waitTimeMinutes
     * @return bool
     * @throws \Exception
     */
    private function toManyAttemptsOnIp($ip, $maxNoAttempts, $waitTimeMinutes)
    {
        $storeKey = 'login:attempts:ip:' . $ip;

        if(! cache()->add($storeKey,1,$waitTimeMinutes)){
            cache()->increment($storeKey);
        }

        abort_if(cache()->get($storeKey, 0) >= $maxNoAttempts,
            429, "OUCH !!! To many attempts! , Try again in (" . $waitTimeMinutes . " min)");
        return false;
    }





}

