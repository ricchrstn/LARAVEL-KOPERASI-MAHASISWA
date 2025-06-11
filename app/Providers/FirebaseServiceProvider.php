<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FirebaseAuth::class, function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(base_path('firebase.json'))
                ->withDatabaseUri(config('firebase.database_url'));

            return $factory->createAuth();
        });

        $this->app->singleton('firebase.auth', function ($app) {
            return $app->make(FirebaseAuth::class);
        });
    }

    public function provides()
    {
        return [FirebaseAuth::class, 'firebase.auth'];
    }

    public function boot()
    {
        //
    }
}
