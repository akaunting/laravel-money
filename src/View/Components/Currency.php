<?php

namespace Akaunting\Money\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Currency extends Component
{
    public function __construct(public string $currency)
    {
        //
    }

    public function render(): View
    {
        return view('money::components.currency');
    }
}
