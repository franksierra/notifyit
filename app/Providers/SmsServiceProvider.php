<?php

namespace App\Providers;

use App\Components\Sms\Client;
use App\Components\Sms\SmsManager;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sms.php' => config_path('sms.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public
    function register()
    {
        $this->app->singleton('sms', function ($app) {
            return new SmsManager($app);
        });
        $this->app->singleton('sms.client', function ($app) {
            $driver = $app['sms']->driver();
            return new Client($driver);
        });

        $this->app->alias('Sms', \App\Components\Sms\Facades\Sms::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sms'];
    }
}
