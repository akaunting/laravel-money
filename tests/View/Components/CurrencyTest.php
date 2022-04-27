<?php

namespace Akaunting\Money\Tests\View\Components;

use Akaunting\Money\Tests\TestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class CurrencyTest extends TestCase
{
    use InteractsWithViews;

    public function testRenderingCurrency()
    {
        $this->blade('<x-currency currency="USD" />')->assertSee('USD (US Dollar)');
    }
}
