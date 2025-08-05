<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(app_path() . '/../resources/views/macro/*.php') as $filename) {
            require_once($filename);
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {



    }
}
