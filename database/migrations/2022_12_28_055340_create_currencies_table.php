<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // not using right now
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('currency_code')->nullable(); // e.g. USD
            $table->string('currency_sign')->nullable(); // e.g. $
            // The exchange rate against the primary exchange rate currency.
            $table->decimal('rate')->nullable();

            $table->tinyInteger('is_primary_exchange')->nullable()->default(0);
            $table->tinyInteger('is_primary_store')->nullable()->default(0);

            $table->tinyInteger('status')->nullable()->default(1); // 1-> active, 0-> deactive
            $table->integer('sort_order')->nullable()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
