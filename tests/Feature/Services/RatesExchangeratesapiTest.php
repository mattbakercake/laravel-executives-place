<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\RatesExchangeratesapi;

class RatesExchangeratesapiTest extends TestCase
{
    public function test_gbp_rates()
    {
        // Fake the API response
        Http::fake(function ($request) {
            $url = $request->url();

            if (strpos($url, 'base=GBP') !== false && strpos($url, 'symbols=EUR') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "GBP",
                    "date" => "2024-01-18",
                    "rates" => [
                        "EUR" => 1.1
                    ]
                ], 200);
            };

            if (strpos($url, 'base=GBP') !== false && strpos($url, 'symbols=USD') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "GBP",
                    "date" => "2024-01-18",
                    "rates" => [
                        "USD" => 1.3
                    ]
                ], 200);
            };

        });

        //RatesLocal Service
        $ratesService = new RatesExchangeratesapi();

        //check service returns EUR rate
        $rate = $ratesService->fetchRate('GBP', 'EUR');
        $this->assertEquals('1.1', $rate);

        //check service returns USD rate
        $rate = $ratesService->fetchRate('GBP', 'USD');
        $this->assertEquals('1.3', $rate);

    }

    public function test_eur_rates()
    {
        // Fake the API response
        Http::fake(function ($request) {
            $url = $request->url();

            if (strpos($url, 'base=EUR') !== false && strpos($url, 'symbols=GBP') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "EUR",
                    "date" => "2024-01-18",
                    "rates" => [
                        "GBP" => 0.9
                    ]
                ], 200);
            };

            if (strpos($url, 'base=EUR') !== false && strpos($url, 'symbols=USD') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "EUR",
                    "date" => "2024-01-18",
                    "rates" => [
                        "USD" => 0.7
                    ]
                ], 200);
            };
        });

        //RatesLocal Service
        $ratesService = new RatesExchangeratesapi();

        //check service returns EUR rate
        $rate = $ratesService->fetchRate('EUR', 'GBP');
        $this->assertEquals('0.9', $rate);

        //check service returns USD rate
        $rate = $ratesService->fetchRate('EUR', 'USD');
        $this->assertEquals('0.7', $rate);
    }

    public function test_usd_rates()
    {
        // Fake the API response
        Http::fake(function ($request) {
            $url = $request->url();

            if (strpos($url, 'base=USD') !== false && strpos($url, 'symbols=GBP') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "USD",
                    "date" => "2024-01-18",
                    "rates" => [
                        "GBP" => 0.72
                    ]
                ], 200);
            };

            if (strpos($url, 'base=USD') !== false && strpos($url, 'symbols=EUR') !== false) {
                return Http::response([
                    "success" => true,
                    "timestamp" => 1519296206,
                    "base" => "USD",
                    "date" => "2024-01-18",
                    "rates" => [
                        "EUR" => 0.85
                    ]
                ], 200);
            };
        });

        //RatesLocal Service
        $ratesService = new RatesExchangeratesapi();

        //check service returns EUR rate
        $rate = $ratesService->fetchRate('USD', 'GBP');
        $this->assertEquals('0.72', $rate);

        //check service returns USD rate
        $rate = $ratesService->fetchRate('USD', 'EUR');
        $this->assertEquals('0.85', $rate);
    }
}
