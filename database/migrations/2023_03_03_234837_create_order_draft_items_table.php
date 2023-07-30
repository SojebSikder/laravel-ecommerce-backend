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
        Schema::create('order_draft_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_draft_id')->nullable()->constrained('order_drafts')->onDelete('cascade');
            // product type
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');

            $table->bigInteger('quantity')->nullable()->default(1);
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
        Schema::dropIfExists('order_draft_items');
    }
};
