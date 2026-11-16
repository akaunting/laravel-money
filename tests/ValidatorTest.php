<?php

namespace Akaunting\Money\Tests;

use PHPUnit\Framework\Attributes\DataProvider;

class ValidatorTest extends TestCase
{
    public static function currencyCodes(): array
    {
        return [
            'valid code' => ['USD', true],
            'lowercase code' => ['usd', false],
            'unknown code' => ['XYZ', false],
            'array' => [['USD'], false],
            'integer' => [840, false],
            'boolean' => [true, false],
        ];
    }

    #[DataProvider('currencyCodes')]
    public function testCurrencyCodeRule(mixed $value, bool $passes)
    {
        $validator = $this->app['validator']->make(
            ['currency' => $value],
            ['currency' => 'currency_code']
        );

        $this->assertSame($passes, $validator->passes());
    }
}
