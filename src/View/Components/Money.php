<?php

namespace Akaunting\Money\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Money extends Component
{
    public function __construct(
        public mixed $amount,
        public ?string $currency = null,
        public bool $convert = false
    ) {
        //
    }

    public function render(): View
    {
        return view('money::components.money');
    }
}
