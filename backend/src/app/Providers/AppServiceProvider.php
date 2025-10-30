<?php

namespace App\Providers;

use App\Contracts\SmsProviderInterface;
use App\Services\SmsProviders\DemoSmsProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsProviderInterface::class, DemoSmsProvider::class);
        
        // Регистрируем SmsService как синглтон
        $this->app->singleton(\App\Services\SmsService::class, function ($app) {
            return new \App\Services\SmsService($app->make(SmsProviderInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
