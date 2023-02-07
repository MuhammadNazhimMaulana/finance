<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function toIDR($value)
    {
        return number_format($value,0,',','.');
    }
}
