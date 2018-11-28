<?php

namespace App\Http\Controllers\Authentication;

use App\ClassContainer\Authentication\AuthenticatesUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\LoginToken;
use App\Rules\MustHaveUppLowNum;
use Illuminate\Http\Request;

class RegistrationController extends Controller {

    /**
     * RegistrationController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('authentication.registration.registerForm');
    }

    /**
     * Stores User, and sends Token - link Email
     * @param AuthenticatesUser $auth
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(AuthenticatesUser $auth)
    {
        $credentials = request()->validate([
            'name' => 'required|alpha_num|min:3|unique:users,name',
            'email'=> 'required|email|unique:users,email',
            'password'=>['required','confirmed','min:6',new MustHaveUppLowNum],
        ]);
        return $auth->invite($credentials);
    }

    /**
     * Tries to validate user with the given token
     * @param AuthenticatesUser $auth
     * @param LoginToken $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticate(AuthenticatesUser $auth, LoginToken $token)
    {
        return $auth->authenticate($token);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resendTokenForm()
    {
        return view("authentication.registration.emailConfirmationForm");
    }

    /**
     * Resends the token to the user with the given email
     * @param AuthenticatesUser $auth
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function resendToken(AuthenticatesUser $auth)
    {
        $existingEmail=request()->validate([
            'email'=> 'required|email|exists:users,email',
        ]);
        return $auth->invite($existingEmail);
    }


}
