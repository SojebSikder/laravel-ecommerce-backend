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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');

            // variant details
            // $table->string('size')->nullable();
            // $table->string('color')->nullable();
            // $table->string('material')->nullable();
            // $table->foreignId('attribute_id')->nullable()->constrained('attributes')->onDelete('set null');
            // $table->foreignId('attribute_value_id')->nullable()->constrained('attribute_values')->onDelete('set null');

            // price and quantity
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('old_discount')->nullable();
            $table->tinyInteger('track_quantity')->nullable()->default(0);
            $table->bigInteger('quantity')->nullable();
            $table->string('sku')->nullable(); // stock keeping unit
            $table->string('barcode')->nullable();
            $table->tinyInteger('is_sale')->nullable()->default(0);

            // for internal use
            $table->decimal('cost_per_item')->nullable();

            // shipping details
            $table->decimal('weight')->nullable();
            // available values:
            // kg - kilogram, 
            // lb - pound, 
            // oz - ounce,
            // g - gram
            $table->string('weight_unit')->nullable();


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
        Schema::dropIfExists('variants');
    }
};
