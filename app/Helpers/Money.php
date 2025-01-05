<?php

/**  2024-12-232 - Updated
 * 
 */

namespace App\Helpers;

use NumberFormatter;

class Money
{
    public static function number($value, $inCents =  true)
    {
        if (! is_numeric($value)) {
            return null;
        }

        return $inCents === true
            ? $value / 100
            : $value;
    }

    public static function currency($value, $inCents = true)
    {
        if (! is_numeric($value)) {
            return null;
        }

        $currencyFormatter = new NumberFormatter(app()->getLocale() . "_US", NumberFormatter::CURRENCY);
        $currencyFormatter->setAttribute (NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $currencyFormatter->setAttribute (NumberFormatter::MAX_FRACTION_DIGITS, 2);

        return $inCents === true
            ? $currencyFormatter->formatCurrency($value / 100,  'USD')
            : $currencyFormatter->formatCurrency($value,  'USD');
    }

}

