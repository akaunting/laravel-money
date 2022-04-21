<?php

namespace Akaunting\Tests\Money;

use Akaunting\Money\Provider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [Provider::class];
    }
}
