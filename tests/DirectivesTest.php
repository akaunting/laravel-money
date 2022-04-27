<?php

namespace Akaunting\Money\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class DirectivesTest extends TestCase
{
    use InteractsWithViews;

    public function testCurrencyDirective()
    {
        $this->blade('@currency("USD")')->assertSee('USD (US Dollar)');
    }

    public function testMoneyDirective()
    {
        $this->blade('@money(500, "USD")')->assertSee('$5.00');
    }
}
