<?php
/**
 * Created by PhpStorm.
 * User: marce
 * Date: 13.11.2018
 * Time: 14:42
 */

namespace App\ClassContainer\Authentication;


use App\LoginToken;
use App\ResetToken;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\ClassContainer\SessionManager;
use PHPUnit\Framework\Exception;

class AuthenticatesUser {


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
        $user = $token->user;

        $message = ($user->firstAuthentication() ?
            Lang::get('authentication.confirmation', ['name' => $user->name]) :
            Lang::get('authentication.already_confirmed', ['name' => $user->name]));

        return redirect()->route('login')->with('message', $message);

    }

    /**
     * Attempts to Log in the user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function login($credentials)
    {
        $this->rememberUser($credentials);
        return $this->loginAttempt($credentials);
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

        $this->createResetToken($user)
            ->sendResetEmail();

        return redirect()->route('login')->with('message', Lang::get('authentication.reset_password_message'));
    }

    /**
     * For the returned token, grabs the user for this token and generates the view with to user remember_token
     * @param ResetToken $token
     * @return $this
     */
    public function createNewPasswordForm(ResetToken $token)
    {
        $user = $token->user;

        abort_if(! $user,401,"This user is not authorized");

        return view("authentication.login.changePasswordForm", compact('user'));
    }


    /**
     * Attempts to change password for the user with email address
     * @param $credentials
     * @return RedirectResponse
     * @throws \Exception
     */
    public function changePassword($credentials)
    {
        $user = User::byEmail($credentials['email']);

        $user->changePassword($credentials);

        $this->rememberUser($credentials);

        return redirect()->route('login')->with('message', Lang::get('authentication.password_reset_successful'));

    }

    /**
     * If user with email exists returns it, otherwise creates a user
     * @param $credentials
     * @return mixed
     * @throws \Exception
     */
    private function resolveUser($credentials)
    {
        $user= User::byEmail($credentials['email']);

        if($user){
            return $user;
        }
        else {
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
        return User::create($credentials+['remember_token'=>str_random(50)]);
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
     * @param $user
     * @return mixed
     */
    private function createResetToken($user)
    {
        return ResetToken::generateFor($user);
    }

    /**
     * If checkbox , remembers the user in current session
     * @param $credentials
     */
    private function rememberUser($credentials)
    {
        if (array_key_exists('remember-me',$credentials))
        {
            SessionManager::rememberUser($credentials);
        }
    }


    /**
     * Attempts to login the user if the extra-conditions pass and also user-password matches
     * @param $credentials
     * @return RedirectResponse
     */
    private function loginAttempt($credentials)
    {
        if (Auth::attempt($credentials))
        {
            return redirect()->intended(request('home'));
        }
        return redirect()->route('login')->withErrors(Lang::get('authentication.wrong_password'));
    }

}

