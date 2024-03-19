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
    protected Model $model;
    protected function setUp():void
    {
        $this->model = $model = $this->getMockBuilder(Model::class)->disableOriginalConstructor()->getMock();
    }

    public function testItWillNotGetMoneyFromNonString()
    {
        $this->expectException(UnexpectedValueException::class);


        (new MoneyCast)->get($this->model, 'money', [], []);
    }

    public function testItWillNotGetMoneyFromNonJson()
    {
        $this->expectException(UnexpectedValueException::class);


        (new MoneyCast)->get($this->model, 'money', 'testing', []);
    }

    public function testItWillNotGetMoneyFromIllFormedJson()
    {
        $this->expectException(UnexpectedValueException::class);


        (new MoneyCast)->get($this->model, 'money', '{"key":"value"}', []);
    }

    public function testItGetsMoneyFromJson()
    {
        $json = '{"amount":1000,"currency":"USD"}';

        $value = (new MoneyCast)->get($this->model, 'money', $json, []);

        $this->assertEquals(
            new Money('1000', new Currency('USD')),
            $value
        );
    }

    public function testItWillNotSetNonMoneyAsJson()
    {
        $this->expectException(UnexpectedValueException::class);


        (new MoneyCast)->set($this->model, 'money', 1000, []);
    }

    public function testItSetsMoneyAsJson()
    {
        $money = new Money('1200', Currency::USD());

        $value = (new MoneyCast)->set($this->model, 'money', $money, []);

        $this->assertSame(
            '{"amount":1200,"currency":"USD"}',
            $value
        );
    }
}
