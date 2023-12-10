<?php

namespace Kreativdev\Installer;

use Illuminate\Support\ServiceProvider;

class KdInstallerServiceProvider extends ServiceProvider
{
    /** 
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'installer');

        $this->publishes([
            __DIR__.'/config/kdinstaller.php' => config_path('kdinstaller.php'),
        ], 'installer_config');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/kdinstaller.php', 'installer'
        );
    }


}
