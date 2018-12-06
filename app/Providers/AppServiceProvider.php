<?php

namespace App\Providers;

use App\Services\Authentication\AuthenticatesUser;
use App\Services\Authentication\PasswordAuthenticator;

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
