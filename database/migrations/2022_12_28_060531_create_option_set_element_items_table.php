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
        Schema::create('option_set_element_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('option_set_element_id')->nullable()->constrained('option_set_elements')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            // price adjustment
            $table->decimal('amount')->nullable();
            // is dollar or percentage
            $table->tinyInteger('is_percentage')->nullable()->default(0);
            // weight adjustment
            $table->decimal('weight')->nullable();
            // available values:
            // kg - kilogram, 
            // lb - pound, 
            // oz - ounce,
            // g - gram
            $table->string('weight_unit')->nullable();

            $table->tinyInteger('is_selected')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1); // 1-> active right 0-> deactive right
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
        Schema::dropIfExists('option_set_element_items');
    }
};
