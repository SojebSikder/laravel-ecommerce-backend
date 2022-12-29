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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->string('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            // variant id
            $table->string('product_id')->nullable()->references('id')->on('products')->onDelete('set null');
            $table->decimal('discount')->nullable();
            $table->decimal('price')->nullable();
            // with discount
            $table->decimal('total_price')->nullable();
            $table->bigInteger('quantity')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('order_items');
    }
};
