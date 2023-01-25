<?php

namespace Akaunting\Money\Tests\Rules;

use Akaunting\Money\Currency;
use Akaunting\Money\Rules\CurrencyRule;
use Akaunting\Money\Tests\TestCase;
use Illuminate\Support\Facades\Lang;

class CurrencyRuleTest extends TestCase
{
    /**
     * @dataProvider currencies
     */
    public function testRulePassesForAllCurrencies($currency)
    {
        $this->assertTrue(
            (new CurrencyRule)->passes('currency', $currency)
        );
    }

    public function testRuleFails()
    {
        $this->assertFalse(
            (new CurrencyRule)->passes('currency', 'rad')
        );
    }

    public function testErrorMessageUsesLang()
    {
        $this->assertSame('money.invalid-currency', (new CurrencyRule)->message());
    }

    public function currencies(): array
    {
        $currencies = [];

        foreach (Currency::getCurrencies() as $currency => $data) {
            $currencies["{$data['name']} is a valid currency"] = [$currency];
        }

        return $currencies;
    }
}
