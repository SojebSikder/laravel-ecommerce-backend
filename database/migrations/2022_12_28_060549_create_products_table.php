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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // max limit 70
            $table->string('slug')->nullable();

            /**
             * SEO
             */
            $table->text('meta_title')->nullable(); // currently not using
            $table->text('meta_description')->nullable(); // max limit 320
            $table->text('meta_keyword')->nullable();

            // product details
            $table->longText('description')->nullable();
            $table->longText('details')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
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

            //
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('option_set_id')->nullable()->constrained('option_sets')->onDelete('set null');

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->bigInteger("views")->nullable()->default(0);
            $table->tinyInteger('status')->default(1); //1-> active,2->deactive

            // $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
