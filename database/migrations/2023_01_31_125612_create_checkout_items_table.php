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
        Schema::create('checkout_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->nullable()->constrained('carts')->onDelete('cascade');
            $table->foreignId('checkout_id')->nullable()->constrained('checkouts')->onDelete('cascade');
            // product id
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            // if product is variant then variant_id will be set
            $table->foreignId('variant_id')->nullable()->constrained('variants')->onDelete('cascade');

            $table->decimal('discount')->nullable();
            $table->decimal('price')->nullable();
            // with discount
            $table->decimal('total_price')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->text('attribute')->nullable(); // store product option sets

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
        Schema::dropIfExists('checkout_items');
    }
};
