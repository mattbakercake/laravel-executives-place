<?php

namespace App\Providers;

use App\Services\RatesLocal;
use App\Services\Rates;
use App\Services\RatesExchangeratesapi;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {  
        //Rates Service
        app()->bind(Rates::class, function (){

            //use the exchangeratesapi service if defined in .env
            if (env('RATES_DRIVER', false) === 'exchangeratesapi') {
                return new RatesExchangeratesapi();
            }

            //fallback to local driver
            return new RatesLocal();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
