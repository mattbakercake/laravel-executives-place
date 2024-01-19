<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class RatesExchangeratesapi implements Rates
{
    private string $apiKey;
    private string $base_url = 'http://api.exchangeratesapi.io/v1/latest';

    public function __construct() {
        $this->apiKey = env('RATES_API_KEY', false);
    }

    /**
     * fetches conversion rate from API provider
     * 
     * @var string $base_iso the base currency
     * @var string $rate_iso the currency to convert to
     * 
     * @return float the conversion rate
     */
    public function fetchRate(string $base_iso, string $rate_iso)
    {
        if (env('RATES_API_KEY', false) === false) {
            throw new Exception('RATES_API_KEY is not configured', 422);
        }

        $response = HTTP::get($this->base_url, [
            'access_key' => $this->apiKey,
            'base' => $base_iso,
            'symbols' => $rate_iso
        ]);
        
        return (float)$response->object()->rates->$rate_iso;
    }
}
