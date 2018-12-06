<?php

namespace App\Http\Controllers\Authentication;


use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\LoginToken;
use App\Rules\MustHaveUppLowNum;
use App\Services\Authentication\PasswordAuthenticator;


class RegistrationController extends Controller {

    /**
     * @var PasswordAuthenticator
     */
    private $auth;

    /**
     * RegistrationController constructor.
     * @param PasswordAuthenticator $auth
     */
    public function __construct(PasswordAuthenticator $auth)
    {
        $this->middleware('guest');

        $this->auth = $auth;
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
     * @return \App\Services\Authentication\RedirectResponse
     * @throws \Exception
     */
    public function store()
    {
        $credentials = request()->validate([
            'name' => 'required|alpha_num|min:3|unique:users,name',
            'email'=> 'required|email|unique:users,email',
            'password'=>['required','confirmed','min:6',new MustHaveUppLowNum],
        ]);
        return $this->auth->invite($credentials);
    }

    /**
     * Tries to validate user with the given token
     * @param LoginToken $token
     * @return \App\Services\Authentication\RedirectResponse|\Illuminate\Routing\Redirect
     */
    public function authenticate(LoginToken $token)
    {
        return $this->auth->authenticate($token);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resendTokenForm()
    {
        //TODO: Auth-Maybe: Se Poate pune un filtru de numar de incercari sau un obiect cu I'm not a robot
        return view("authentication.registration.emailConfirmationForm");
    }

    /**
     * Resends the token to the user with the given email
     * @return \App\Services\Authentication\RedirectResponse
     * @throws \Exception
     */
    public function resendToken()
    {
        $existingEmail=request()->validate([
            'email'=> 'required|email|exists:users,email',
        ]);
        return $this->auth->invite($existingEmail);
    }


}
