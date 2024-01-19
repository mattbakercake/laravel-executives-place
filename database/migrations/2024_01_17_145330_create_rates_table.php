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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_iso',3);
            $table->string('rate_iso',3);
            $table->decimal('rate',10,6); //e.g. 2747.310462
            $table->timestamps();

            //unique composite key - prevent duplicate rates
            $table->unique(['base_iso', 'rate_iso']);

            //iso cols must relate to currency iso codes present in currencies table
            $table->foreign('base_iso')->references('iso_code')->on('currencies');
            $table->foreign('rate_iso')->references('iso_code')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['base_iso']);
            $table->dropForeign(['rate_iso']);
        });

        Schema::dropIfExists('rates');
    }
};
