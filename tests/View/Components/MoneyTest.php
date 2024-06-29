<?php

namespace Akaunting\Money\Tests\View\Components;

use Akaunting\Money\Currency;
use Akaunting\Money\Tests\TestCase;
use Akaunting\Money\View\Components\Money;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class MoneyTest extends TestCase
{
    use InteractsWithViews;

    public function testRenderingMoney()
    {
        $this->blade('<x-money amount="1000" />')->assertSee('$10.00');
        $this->blade('<x-money amount="1000" convert />')->assertSee('$1,000.00');
        $this->blade('<x-money amount="1000" currency="GBP" convert />')->assertSee('£1,000.00');
    }

    public function testRenderingWithCurrencyObject()
    {
        $this->blade('<x-money amount="1000" :currency="\Akaunting\Money\Currency::GBP()" />')->assertSee('£10.00');
        $this->blade('<x-money amount="1000" :currency="\Akaunting\Money\Currency::GBP()" convert />')->assertSee('£1,000.00');
    }
}
