<?php namespace App\Helpers;

class Currency
{
    static public function format($amount)
    {
        $moneyFormater = new \NumberFormatter(app()->getLocale() . "_US", \NumberFormatter::CURRENCY);

        return $moneyFormater->format($amount);
    }
}
