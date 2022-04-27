<?php

namespace Akaunting\Money\Tests;

class ProviderTest extends TestCase
{
    public function testPublishingConfig()
    {
        $this->artisan('vendor:publish --tag money');

        $this->assertFileExists(config_path('money.php'));
        $this->assertFileEquals(__DIR__ . '/../config/money.php', config_path('money.php'));
    }

    protected function tearDown(): void
    {
        unlink(config_path('money.php'));

        parent::tearDown();
    }
}
