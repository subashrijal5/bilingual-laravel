<?php

namespace Subashrijal5\Bilingual;

use Illuminate\Support\ServiceProvider;
use Subashrijal5\Bilingual\Commands\GetTranslatedStrings;
use Subashrijal5\Bilingual\Commands\PushStringsToTranslate;

class BilingualServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Bilingual');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'Bilingual');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bilingual.php' => config_path('bilingual.php'),
            ], 'bilingual-config');

            $this->commands(GetTranslatedStrings::class);
            $this->commands(PushStringsToTranslate::class);

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/Bilingual'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/Bilingual'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/Bilingual'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/bilingual.php', 'bilingual');

        // Register the main class to use with the facade
        $this->app->singleton('Bilingual', function () {
            return new Bilingual;
        });
    }
}
