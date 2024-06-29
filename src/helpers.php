<?php

use Akaunting\Money\Currency;
use Akaunting\Money\Money;

if (! function_exists('money')) {
    function money(mixed $amount, string|Currency $currency = null, bool $convert = null): Money
    {
        if (is_null($currency)) {
            /** @var string $currency */
            $currency = config('money.defaults.currency');
        }

        if (is_null($convert)) {
            /** @var bool $convert */
            $convert = config('money.defaults.convert');
        }

        return new Money($amount, currency($currency), $convert);
    }
}

if (! function_exists('currency')) {
    function currency(string|Currency $currency = null): Currency
    {
        if (is_null($currency)) {
            /** @var string $currency */
            $currency = config('money.defaults.currency');
        }

        if ($currency instanceof Currency) {
            return $currency;
        }

        return new Currency($currency);
    }
}
