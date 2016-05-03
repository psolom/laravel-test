<?php

namespace App\Providers\Twilio;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        $config = include __DIR__ . '/config/config.php';
        config(['twilio.config' => $config]);

        // Register manager for usage with the Facade.
        $this->app->singleton('twilio', function () {
            return new Container(config('twilio.config'));
        });
    }

}