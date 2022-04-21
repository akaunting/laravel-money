<?php

namespace Akaunting\Tests\Money\View\Components;

use Akaunting\Tests\Money\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class CurrencyTest extends TestCase
{
    use InteractsWithViews;

    public function testRenderingCurrency()
    {
        $this->blade('<x-currency currency="USD" />')->assertSee('USD (US Dollar)');
    }
}
