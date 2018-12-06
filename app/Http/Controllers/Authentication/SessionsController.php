<?php

namespace App\Http\Controllers\Authentication;

use App\Services\Authentication\AuthenticatesUser;
use App\ResetToken;
use App\Rules\MustHaveUppLowNum;
use App\Http\Controllers\Controller;
use App\Services\Authentication\PasswordAuthenticator;

class SessionsController extends Controller {

    /**
     * @var PasswordAuthenticator
     */
    private $auth;

    public function __construct(PasswordAuthenticator $auth)
    {
        $this->middleware('guest')-> except('destroy');
        $this->middleware('email_verified')->only("store");
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("authentication.login.loginForm");
    }

    /**
     * If all the conditions are true (see middleware), attempts to login the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $credentials = request()->validate(['email'=>'required|email|exists:users,email',
                                            'password'=>'required']);
        return $this->auth->login($credentials);
    }

    /**
     * Loggs out the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        return $this->auth->logOut();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPasswordForm()
    {
        return view("authentication.login.resetPasswordForm");
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function resetPassword()
    {
        $email=request()->validate([
            'email'=> 'required|email|exists:users,email',
        ]);

        return $this->auth->resetPassword($email);
    }


    /**
     * Generates the view for the new password
     * @param ResetToken $token
     * @return AuthenticatesUser
     */
    public function CreateNewPassword(ResetToken $token)
    {
        return $this->auth->createNewPasswordForm($token);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function StoreNewPassword()
    {
        $password_field = request()->validate([
            'password'=>['required','confirmed','min:6',new MustHaveUppLowNum],
        ]);

        return $this->auth->changePassword($password_field['password'],request('reset_token'));
    }
}
