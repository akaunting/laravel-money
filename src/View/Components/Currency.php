<?php

namespace Akaunting\Money\View\Components;

use Illuminate\View\Component;

class Currency extends Component
{
    /**
     * The Currency currency.
     *
     * @var string
     */
    public $currency;

    /**
     * Create the component instance.
     *
     * @param string $currency
     * @return void
     */
    public function __construct($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('money::components.currency');
    }
}
