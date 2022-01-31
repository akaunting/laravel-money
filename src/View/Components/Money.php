<?php

namespace Akaunting\Money\View\Components;

use Illuminate\View\Component;

class Money extends Component
{
    /**
     * The money amount.
     *
     * @var mixed
     */
    public $amount;

    /**
     * The money currency.
     *
     * @var string
     */
    public $currency;

    /**
     * The money convert.
     *
     * @var bool
     */
    public $convert;
    /**
     * Create the component instance.
     *
     * @param mixed  $amount
     * @param string $currency
     * @param bool   $convert
     * @return void
     */
    public function __construct($amount, $currency = null, $convert = false)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->convert = $convert;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('money::components.money');
    }
}
