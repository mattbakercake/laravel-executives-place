<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('rate_hour');
            $table->string('currency_iso');

            //currency iso must relate to iso in currencies table
            $table->foreign('currency_iso')->references('iso_code')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['currency_iso']);
            $table->dropColumn(['rate_hour','currency_iso']);
        });
    }
};
