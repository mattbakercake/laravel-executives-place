<?php

namespace App\Services;

use App\Models\Rate;

Class RatesLocal implements Rates {

    /**
     * fetches conversion rate from local db
     * 
     * @var string $base_iso the base currency
     * @var string $rate_iso the currency to convert to
     * 
     * @return float the conversion rate
     */
    public function fetchRate(string $base_iso, string $rate_iso)
    {
        return (float)Rate::where([
                    ['base_iso','=',$base_iso],
                    ['rate_iso', '=', $rate_iso]
                ])->first()->rate;
    }
}