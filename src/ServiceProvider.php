<?php

namespace Andonovn\LaravelBetsApi;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/bets_api.php' => config_path('bets_api.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BetsApi::class, function ($app) {
            return new BetsApi(
                $app->make(Client::class),
                config('bets_api')
            );
        });
    }
}
