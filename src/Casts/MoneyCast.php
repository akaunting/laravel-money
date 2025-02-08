<?php

namespace Akaunting\Money\Casts;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use UnexpectedValueException;

/**
 * @template-implements CastsAttributes<Money,Money>
 */
class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Money
    {
        if (! is_string($value)) {
            throw new UnexpectedValueException;
        }

        /** @var null|array{amount:mixed, currency:string} $extractedValue */
        $extractedValue = json_decode($value, true);

        if (! is_array($extractedValue) || ! isset($extractedValue['amount']) || ! isset($extractedValue['currency'])) {
            throw new UnexpectedValueException;
        }

        return new Money(
            $extractedValue['amount'],
            new Currency($extractedValue['currency'])
        );
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (! $value instanceof Money) {
            throw new UnexpectedValueException;
        }

        return (string) json_encode([
            'amount' => $value->getAmount(),
            'currency' => $value->getCurrency()->getCurrency(),
        ]);
    }
}
