<?php

namespace Nldou\Xiaoe;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Nldou\Xiaoe\Message\Server;
use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/Config' => config_path()], 'nldou-xiaoe-config');
        }
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $source = realpath(__DIR__.'/Config/xiaoe.php');
        $this->mergeConfigFrom($source, 'xiaoe');

        $appid = config('xiaoe.appid');
        $clientid = config('xiaoe.clientid');
        $secretKey = config('xiaoe.secret_key');
        $tokenClient = new TokenClient($appid, $clientid, $secretKey);

        $aeskey = config('xiaoe.aes_key');
        $token = config('xiaoe.token');
        $server = new Server($appid, $token, $aeskey);

        $this->app->singleton(Xiaoe::class, function ($laravelApp) use ($tokenClient, $server) {
            return new Xiaoe($tokenClient, $server);
        });
        $this->app->alias(Xiaoe::class, 'xiaoe');
    }

    public function provides()
    {
        return [Xiaoe::class, 'xiaoe'];
    }
}
