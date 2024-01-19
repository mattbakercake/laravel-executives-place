<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        \App\Models\User::factory()->create([
            'name' => 'Jack Jones',
            'email' => 'jj@example.com',
            'rate_hour' => 25,
            'currency_iso'=> 'GBP'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Amelia Bloggs',
            'email' => 'ab@example.com',
            'rate_hour' => 40,
            'currency_iso' => 'USD'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Gunther Smith',
            'email' => 'gs@example.com',
            'rate_hour' => 30,
            'currency_iso' => 'EUR'
        ]);
    }
}
