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
        Schema::create('sublinks', function (Blueprint $table) {
            $table->id();

            $table->string('head')->nullable(); // sub_menu caption
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string("image")->nullable(); // image size 1296px by 410px
            $table->foreignId('menu_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->tinyInteger('is_link')->nullable()->default(0); // 1-> active 0-> deactive
            $table->string('link')->nullable(); // if is_link true then it will be use
            $table->bigInteger('parent_id')->nullable();
            /**
             * SEO
             */
            $table->text('meta_title')->nullable(); // max limit 320
            $table->text('meta_description')->nullable(); // max limit 320
            $table->text('meta_keyword')->nullable();
            //
            $table->longText('style')->nullable(); // custom inline css
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
        Schema::dropIfExists('sublinks');
    }
};
