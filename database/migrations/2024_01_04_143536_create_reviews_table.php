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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->decimal('rating_value')->nullable(); // 1 to 5
            $table->string('title')->nullable();
            $table->text('body')->nullable();

            $table->tinyInteger('status')->nullable()->default(1); // 1-> active, 0-> deactive

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
        Schema::dropIfExists('reviews');
    }
};
