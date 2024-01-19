<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->truncate();

        DB::table('currencies')->insert([
            'iso_code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
        ]);

        DB::table('currencies')->insert([
            'iso_code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
        ]);

        DB::table('currencies')->insert([
            'iso_code' => 'GBP',
            'name' => 'Pound Sterling',
            'symbol' => '£',
        ]);
    }
}
