<?php

use Akaunting\Money\Casts\CurrencyCast;
use Akaunting\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class CurrencyCastTest extends TestCase
{
    public function testItWillNotGetCurrencyFromNonStrings()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new CurrencyCast)->get($model, 'currency', 1, []);
    }

    public function testItWillNotSetCurrencyFromNonCurrencies()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new CurrencyCast)->set($model, 'currency', 'USD', []);
    }

    public function testItGetsCurrencyFromString()
    {
        $model = $this->getMockBuilder(Model::class)->getMock();

        $value = (new CurrencyCast)->get($model, 'currency', 'USD', []);

        $this->assertEquals(Currency::USD(), $value);
    }

    public function testItSetsCurrencyAsString()
    {
        $mock = $this->getMockBuilder(Model::class)->getMock();

        $value = (new CurrencyCast)->set($mock, 'currency', Currency::USD(), []);

        $this->assertSame('USD', $value);
    }
}
