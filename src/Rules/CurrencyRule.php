<?php

namespace Akaunting\Money\Rules;

use Akaunting\Money\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrencyRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($value)) {
            $fail('money.invalid-currency')->translate();
        }
    }

    protected function passes(mixed $value): bool
    {
        return is_string($value) && array_key_exists(strtoupper($value), Currency::getCurrencies());
    }
}
