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

            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            // if product is variant then variant_id will be set
            $table->foreignId('variant_id')->nullable()->constrained('variants')->onDelete('set null');

            $table->decimal('discount')->nullable();
            $table->decimal('price')->nullable();
            // with discount
            $table->decimal('total_price')->nullable();
            $table->bigInteger('quantity')->nullable();

            // info
            // total product weight
            $table->decimal('weight')->nullable();
            // available values:
            // kg - kilogram, 
            // lb - pound, 
            // oz - ounce,
            // g - gram
            $table->string('weight_unit')->nullable();

            $table->text('attribute')->nullable(); // store product option sets

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
