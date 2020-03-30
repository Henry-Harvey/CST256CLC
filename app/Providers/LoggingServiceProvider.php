<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Utility\Logger;

class LoggingServiceProvider extends ServiceProvider
{
    protected $defer = true;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Models\Services\Utility\LoggerInterface', function ($app) {
            return new Logger();
        });
    }
    
    /**
     * For defered providers only return the interface that this provider implements.
     *
     * @return array
     */
    public function provides(){
        return ['App\Models\Services\Utility\LoggerInterface'];
    }
}
