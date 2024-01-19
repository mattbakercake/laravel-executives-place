<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //disable FK constraints to that truncation and reseeding allowed
        Schema::disableForeignKeyConstraints();

        $this->call([
            CurrencySeeder::class,
            RateSeeder::class,
            UserSeeder::class,
        ]);

        //make sure FK constraints back in place after seeding
        Schema::enableForeignKeyConstraints();
    }
}
