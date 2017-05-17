<?php namespace Bugotech\Foundation\Console;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * List commands.
     * @var array
     */
    protected $commands = [
        'Bugotech\Foundation\Console\Commands\MaintenanceCommand',
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('artisan', function ($app) {
            $artisan = new Artisan($app, $app->make('events'), $app->version());
            if (! $this->app->runningInPhar()) {
                $artisan->resolveCommands($this->commands);
            }

            return $artisan;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        //return array_values($this->commands);
        return [];
    }
}
