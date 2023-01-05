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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('body')->nullable();
            // grapejs
            $table->tinyInteger('is_gjs')->nullable()->default(0);
            $table->longText('gjs_data')->nullable();
            //
            /**
             * SEO
             */
            $table->text('meta_title')->nullable(); // max limit 320
            $table->text('meta_description')->nullable(); // max limit 320
            $table->text('meta_keyword')->nullable();
            //
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
        Schema::dropIfExists('pages');
    }
};
