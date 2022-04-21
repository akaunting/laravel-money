<?php

namespace Akaunting\Tests\Money\View\Components;

use Akaunting\Tests\Money\TestCase;
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
}
