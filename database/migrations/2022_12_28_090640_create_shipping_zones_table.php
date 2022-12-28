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
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipping_id')->nullable()->constrained('shippings')->onDelete('cascade');
            $table->string('name')->nullable();
            // shipping rate
            $table->decimal('price')->nullable();
            // define a estimated delivery shipping time
            $table->integer('shipping_time_start')->nullable();
            $table->integer('shipping_time_end')->nullable();
            //
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
        Schema::dropIfExists('shipping_zones');
    }
};
