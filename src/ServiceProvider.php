<?php

namespace Jezzdk\StatamicSecret;

use Jezzdk\StatamicSecret\Console\Commands\GenerateKeys;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        GenerateKeys::class,
    ];

    protected $scripts = [
        __DIR__ . '/../dist/js/statamic-secret.js',
    ];

    protected $fieldtypes = [
        \Jezzdk\StatamicSecret\Fieldtypes\SecretField::class,
    ];

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the main class to use with the facade
        $this->app->singleton('statamic.secret', function () {
            return new StatamicSecret();
        });
    }

    public function bootAddon()
    {
        Statamic::afterInstalled(function ($command) {
            $command->call('secret:generate');
        });
    }
}
