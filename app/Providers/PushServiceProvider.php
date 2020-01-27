<?php

namespace App\Providers;

use App\Components\Push\Client;
use App\Components\Push\Facades\Push;
use App\Components\Push\PushManager;
use Illuminate\Support\ServiceProvider;

class PushServiceProvider extends ServiceProvider
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
            __DIR__ . '/config/push.php' => config_path('push.php')
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
        $this->app->singleton('push', function () {
            return new PushManager($this->app);
        });
        $this->app->singleton('push.client', function () {
            $driver = $this->app['push']->driver();
            return new Client($driver);
        });

        $this->app->alias('push', Push::class);

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['push'];
    }
}
