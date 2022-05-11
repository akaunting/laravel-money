<?php

use Akaunting\Money\Currency;
use Akaunting\Money\Money;

if (!function_exists('money')) {
    function money(mixed $amount, string $currency = null, bool $convert = false): Money
    {
        if (is_null($currency)) {
            /** @var string $currency */
            $currency = env('DEFAULT_CURRENCY', 'USD');
        }

        return new Money($amount, currency($currency), $convert);
    }
}

if (!function_exists('currency')) {
    function currency(string $currency): Currency
    {
        return new Currency($currency);
    }
}
