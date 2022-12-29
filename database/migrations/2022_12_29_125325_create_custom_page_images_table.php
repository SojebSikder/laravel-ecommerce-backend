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
        Schema::create('custom_page_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // image url
            $table->text('url')->nullable();
            // for image type file
            $table->text('alt_text')->nullable();
            // for additional
            $table->string('custom_page_id')->nullable()->references('id')->on('custom_pages')->onDelete('cascade');
            // media query
            $table->text('media')->nullable();
            $table->tinyInteger('status')->nullable()->default(1); // 0,1
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
        Schema::dropIfExists('custom_page_images');
    }
};
