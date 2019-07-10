<?php

namespace LaravelEnso\Pdf;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->publish();
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/config/snappy.php', 'snappy');

        return $this;
    }

    private function publish()
    {
        $this->publishes([__DIR__.'/config' => config_path()], 'enso-pdf-config');
    }
}
