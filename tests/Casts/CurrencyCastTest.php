<?php

namespace Akaunting\Money\Tests\Casts;

use Akaunting\Money\Casts\CurrencyCast;
use Akaunting\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class CurrencyCastTest extends TestCase
{
    protected Model $model;

    protected function setUp(): void
    {
        $this->model = $this->getMockBuilder(Model::class)->disableOriginalConstructor()->getMock();
    }

    public function testItWillNotGetCurrencyFromNonStrings()
    {
        $this->expectException(UnexpectedValueException::class);

        (new CurrencyCast)->get($this->model, 'currency', 1, []);
    }

    public function testItWillNotSetCurrencyFromNonCurrencies()
    {
        $this->expectException(UnexpectedValueException::class);

        (new CurrencyCast)->set($this->model, 'currency', 'USD', []);
    }

    public function testItGetsCurrencyFromString()
    {
        $value = (new CurrencyCast)->get($this->model, 'currency', 'USD', []);

        $this->assertEquals(Currency::USD(), $value);
    }

    public function testItSetsCurrencyAsString()
    {
        $value = (new CurrencyCast)->set($this->model, 'currency', Currency::USD(), []);

        $this->assertSame('USD', $value);
    }
}
