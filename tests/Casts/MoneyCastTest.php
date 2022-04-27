<?php

namespace Akaunting\Money\Tests\Casts;

use Akaunting\Money\Casts\MoneyCast;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class MoneyCastTest extends TestCase
{
    public function testItWillNotGetMoneyFromNonString()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new MoneyCast)->get($model, 'money', [], []);
    }

    public function testItWillNotGetMoneyFromNonJson()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new MoneyCast)->get($model, 'money', 'testing', []);
    }

    public function testItWillNotGetMoneyFromIllFormedJson()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new MoneyCast)->get($model, 'money', '{"key":"value"}', []);
    }

    public function testItGetsMoneyFromJson()
    {
        $model = $this->getMockBuilder(Model::class)->getMock();
        $json = '{"amount":1000,"currency":"USD"}';

        $value = (new MoneyCast)->get($model, 'money', $json, []);

        $this->assertEquals(
            new Money('1000', new Currency('USD')),
            $value
        );
    }

    public function testItWillNotSetNonMoneyAsJson()
    {
        $this->expectException(UnexpectedValueException::class);

        $model = $this->getMockBuilder(Model::class)->getMock();

        (new MoneyCast)->set($model, 'money', 1000, []);
    }

    public function testItSetsMoneyAsJson()
    {
        $model = $this->getMockBuilder(Model::class)->getMock();
        $money = new Money('1200', Currency::USD());

        $value = (new MoneyCast)->set($model, 'money', $money, []);

        $this->assertSame(
            '{"amount":1200,"currency":"USD"}',
            $value
        );
    }
}
