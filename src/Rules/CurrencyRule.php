<?php

namespace Akaunting\Money\Rules;

use Akaunting\Money\Currency;
use Illuminate\Contracts\Validation\Rule;

class CurrencyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return key_exists(strtoupper($value), Currency::getCurrencies());
    }

    public function message()
    {
        return __('money.invalid-currency');
    }
}
