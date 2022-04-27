<?php

namespace Akaunting\Money\Tests;

use Akaunting\Money\Provider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [Provider::class];
    }
}
