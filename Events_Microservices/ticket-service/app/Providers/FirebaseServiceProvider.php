<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
   /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('firebase.firestore', function ($app) {
            $factory = (new Factory)
                ->withServiceAccount(storage_path('firebase-credentials.json'));

            return $factory->createFirestore();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
