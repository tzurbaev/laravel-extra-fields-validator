<?php

declare(strict_types=1);

namespace Laravel\ExtraFieldsValidator;

use Illuminate\Support\ServiceProvider;

class ExtraFieldsValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../config/extra-validator.php') => config_path('extra-validator.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/extra-validator.php'), 'extra-validator');
    }
}
