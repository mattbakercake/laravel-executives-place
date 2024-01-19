<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Rate;
use App\Services\RatesLocal;

class RatesLocalTest extends TestCase
{
    public function test_gbp_rates()
    {
        //fetch rates directly from DB
        $rates = Rate::where('base_iso','=','GBP')->get();

        //RatesLocal Service
        $ratesLocal = new RatesLocal();

        //check service returns EUR rate
        $serviceRate = $ratesLocal->fetchRate('GBP','EUR');
        $this->assertEquals($rates->firstWhere('rate_iso','EUR')['rate'], $serviceRate);

        //check service returns USD rate
        $serviceRate = $ratesLocal->fetchRate('GBP', 'USD');
        $this->assertEquals($rates->firstWhere('rate_iso', 'USD')['rate'], $serviceRate);
    }

    public function test_eur_rates()
    {
        //fetch rates directly from DB
        $rates = Rate::where('base_iso', '=', 'EUR')->get();

        //RatesLocal Service
        $ratesLocal = new RatesLocal();

        //check service returns GBP rate
        $serviceRate = $ratesLocal->fetchRate('EUR', 'GBP');
        $this->assertEquals($rates->firstWhere('rate_iso', 'GBP')['rate'], $serviceRate);

        //check service returns USD rate
        $serviceRate = $ratesLocal->fetchRate('EUR', 'USD');
        $this->assertEquals($rates->firstWhere('rate_iso', 'USD')['rate'], $serviceRate);
    }

    public function test_usd_rates()
    {
        //fetch rates directly from DB
        $rates = Rate::where('base_iso', '=', 'USD')->get();

        //RatesLocal Service
        $ratesLocal = new RatesLocal();

        //check service returns GBP rate
        $serviceRate = $ratesLocal->fetchRate('USD', 'GBP');
        $this->assertEquals($rates->firstWhere('rate_iso', 'GBP')['rate'], $serviceRate);

        //check service returns EUR rate
        $serviceRate = $ratesLocal->fetchRate('USD', 'EUR');
        $this->assertEquals($rates->firstWhere('rate_iso', 'EUR')['rate'], $serviceRate);
    }
}
