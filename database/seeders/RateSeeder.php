<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rates')->truncate();

        DB::table('rates')->insert([
            'base_iso' => 'GBP',
            'rate_iso' => 'USD',
            'rate' => '1.3',
        ]);

        DB::table('rates')->insert([
            'base_iso' => 'GBP',
            'rate_iso' => 'EUR',
            'rate' => '1.1',
        ]);

        DB::table('rates')->insert([
            'base_iso' => 'EUR',
            'rate_iso' => 'GBP',
            'rate' => '0.9',
        ]);

        DB::table('rates')->insert([
            'base_iso' => 'EUR',
            'rate_iso' => 'USD',
            'rate' => '1.2',
        ]);

        DB::table('rates')->insert([
            'base_iso' => 'USD',
            'rate_iso' => 'GBP',
            'rate' => '0.7',
        ]);

        DB::table('rates')->insert([
            'base_iso' => 'USD',
            'rate_iso' => 'EUR',
            'rate' => '0.8',
        ]);
    }
}
