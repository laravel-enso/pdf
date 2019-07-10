<?php

namespace LaravelEnso\Pdf;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/snappy.php', 'snappy');
        
        $this->publishes([__DIR__.'/config' => config_path()], 'enso-pdf-config');
    }
}
