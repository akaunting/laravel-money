<?php

namespace Akaunting\Money\View\Components;

use Akaunting\Money\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Money extends Component
{
    public function __construct(
        public mixed $amount,
        public null|string|Currency $currency = null,
        public ?bool $convert = null
    ) {
        if ($currency instanceof Currency) {
            $this->currency = $currency->getCurrency();
        }
    }

    /**
     * @psalm-suppress InvalidReturnType,InvalidReturnStatement
     */
    public function render(): View|Factory
    {
        return view('money::components.money');
    }
}
