<?php

namespace App\Providers;

use App\Contracts\Authentication\PasswordAuthenticator;

use App\Services\Authentication\AuthenticatesUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PasswordAuthenticator::class, AuthenticatesUser::class);
    }
}
