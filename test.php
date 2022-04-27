<?php

use Akaunting\Money\Currency;
use Akaunting\Money\Money;

require 'vendor/autoload.php';

$money = Money::USD(1000)->convert(Currency::CAD());

die($money);
