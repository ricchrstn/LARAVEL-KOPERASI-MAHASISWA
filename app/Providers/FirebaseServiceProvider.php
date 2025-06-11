<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('firebase.factory', function ($app) {
            return (new Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.credentials.database_url'));
        });

        $this->app->singleton('firebase.auth', function ($app) {
            return $app->make('firebase.factory')->createAuth();
        });

        $this->app->singleton('firebase.firestore', function ($app) {
            return $app->make('firebase.factory')->createFirestore();
        });

        $this->app->singleton('firebase.storage', function ($app) {
            return $app->make('firebase.factory')->createStorage();
        });

        $this->app->singleton('firebase.database', function ($app) {
            return $app->make('firebase.factory')->createDatabase();
        });
    }

    public function provides()
    {
        return [
            'firebase.factory',
            'firebase.auth',
            'firebase.firestore',
            'firebase.storage',
            'firebase.database',
        ];
    }
}