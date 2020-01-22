<?php

declare(strict_types=1);

namespace Tests;

use Laravel\ExtraFieldsValidator\ExtraFieldsValidatorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [ExtraFieldsValidatorServiceProvider::class];
    }
}
