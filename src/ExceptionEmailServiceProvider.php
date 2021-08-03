<?php

namespace Webmonks\ExceptionEmail;

use Illuminate\Support\ServiceProvider;

class ExceptionEmailServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'exceptionemail');

        $this->publishes([
            __DIR__ . '/../resources/views/email' => resource_path('views/vendor/exceptionemail/email')
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/exceptionemail.php' => config_path('exceptionemail.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webmonks\ExceptionEmail\Commands\EmailTest::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/exceptionemail.php', 'exceptionemail'
        );

        $this->app->singleton('exceptionemail', function () {
            return $this->app->make(ExceptionEmail::class);
        });
    }
}
