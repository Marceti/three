<?php


namespace App\Services\Authentication;


use App\LoginToken;
use App\ResetToken;

interface PasswordAuthenticator {
    /**
     * Invites the user by : creating user or grabbing user, creating token for this user, sending invite email with token link
     * @param $credentials
     */
    public function invite($credentials);

    /**
     * Authenticates user with the given Login token
     * @param LoginToken $token
     */
    public function authenticate(LoginToken $token);

    /**
     * Attempts to Log in the user
     * @param $credentials
     */
    public function login($credentials);

    /**
     * Logs out the user
     */
    public function logOut();

    /**
     * grabs the user with given email, for user creates new resetToken, sends the link on users email
     */
    public function resetPassword($email);

    /**
     * For the returned token, grabs the user for this token and generates the view with to user remember_token
     * @param ResetToken $token
     */
    public function createNewPasswordForm(ResetToken $token);


    /**
     * Attempts to change password for the user with email address
     * @param $password
     * @param $resetToken
     */
    public function changePassword($password,$resetToken);
}