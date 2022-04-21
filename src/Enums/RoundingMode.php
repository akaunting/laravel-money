<?php

namespace Akaunting\Money\Enums;

enum RoundingMode: int
{
    case HalfUp = 1;
    case HalfDown = 2;
    case HalfEven = 3;
    case HalfOdd = 4;
}
