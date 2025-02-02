<?php

namespace Asciisd\Zoho\Providers;

use Asciisd\Zoho\Zoho;
use Asciisd\Zoho\RestClient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;
use Asciisd\Zoho\Console\Commands\ZohoSetupCommand;
use Asciisd\Zoho\Console\Commands\ZohoAuthentication;
use Asciisd\Zoho\Console\Commands\ZohoInstallCommand;

class ZohoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->registerCommands();
        $this->registerSingleton();

        if ( ! class_exists('Zoho')) {
            class_alias('Asciisd\Zoho\Zoho', 'Zoho');
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (Zoho::$registersRoutes) {
            Route::group([
                'prefix' => config('zoho.path'), 'namespace' => 'Asciisd\Zoho\Http\Controllers', 'as' => 'zoho.',
            ], function () {
                Route::get('oauth2callback', 'ZohoController@oauth2callback');
            });
            Route::group([
                'prefix' => config('zohoRX.path'), 'namespace' => 'Asciisd\Zoho\Http\Controllers', 'as' => 'zohoRX.',
            ], function () {
                Route::get('oauth2callback', 'ZohoController@oauth2callbackRX');
            });
        }
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadJsonTranslationsFrom(__DIR__.'/../../resources/lang');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'zoho');
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Zoho::$runsMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/zoho.php' => $this->app->configPath('zoho.php'),
            ], 'zoho-config');

            $this->publishes([
                __DIR__.'/../../database/migrations' => $this->app->databasePath('migrations'),
            ], 'zoho-migrations');

            $this->publishes([
                __DIR__.'/../Storage/oauth' => storage_path('zoho/oauth'),
            ], 'zoho-oauth');
        }
    }

    /**
     * Setup the configuration for Zoho.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/zoho.php', 'zoho'
        );
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ZohoInstallCommand::class,
                ZohoSetupCommand::class,
                ZohoAuthentication::class,
            ]);
        }
    }

    private function registerSingleton()
    {
        $this->app->bind('zoho_manager', function ($app) {
            ZCRMRestClient::initialize(Zoho::zohoOptions());

            return new RestClient(ZCRMRestClient::getInstance());
        });
    }
}
