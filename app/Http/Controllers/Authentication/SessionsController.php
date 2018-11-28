<?php

namespace App\Http\Controllers\Authentication;

use App\ClassContainer\Authentication\AuthenticatesUser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\ResetToken;
use App\Rules\MustHaveUppLowNum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionsController extends Controller {

    public function __construct()
    {
        $this->middleware('guest')-> except('destroy');
        $this->middleware('email_verified')->only("store");
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
     * @param AuthenticatesUser $auth
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(AuthenticatesUser $auth)
    {
        $credentials = request()->validate(['email'=>'required|email|exists:users,email',
                                            'password'=>'required']);
        return $auth->login($credentials);
    }

    /**
     * Loggs out the user
     * @param AuthenticatesUser $auth
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AuthenticatesUser $auth)
    {
        return $auth->logOut();
    }


    /**
     * Generates the view for the new password
     * @param AuthenticatesUser $auth
     * @param ResetToken $token
     * @return AuthenticatesUser
     */
    public function CreateNewPassword(AuthenticatesUser $auth, ResetToken $token)
    {
        return $auth->createNewPasswordForm($token);
    }

    /**
     * @param AuthenticatesUser $auth
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function StoreNewPassword(AuthenticatesUser $auth)
    {
        $credentials = request()->validate([
            'email'=> 'required|email|exists:users,email',
            'password'=>['required','confirmed','min:6',new MustHaveUppLowNum],
        ]);

        return $auth->changePassword($credentials+request(['remember_token','reset_token']));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPasswordForm()
    {
        return view("authentication.login.resetPasswordForm");
    }

    /**
     * @param AuthenticatesUser $auth
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function resetPassword(AuthenticatesUser $auth)
    {
        $email=request()->validate([
            'email'=> 'required|email|exists:users,email',
        ]);

        return $auth->resetPassword($email);
    }
}
