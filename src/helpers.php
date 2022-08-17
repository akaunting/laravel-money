<?php

use Akaunting\Money\Currency;
use Akaunting\Money\Money;

if (!function_exists('money')) {
    function money(mixed $amount, string $currency = null, bool $convert = null): Money
    {
        if (is_null($currency)) {
            /** @var string $currency */
            $currency = env('DEFAULT_CURRENCY', 'USD');
        }
        
        if (is_null($convert)) {
            /** @var bool $currency */
            $convert = env('CONVERT_CURRENCY_DEFAULT', false);
        }

        return new Money($amount, currency($currency), $convert);
    }
}

if (!function_exists('currency')) {
    function currency(string $currency = null): Currency
    {
        if (is_null($currency)) {
            /** @var string $currency */
            $currency = env('DEFAULT_CURRENCY', 'USD');
        }
        
        return new Currency($currency);
    }
}
