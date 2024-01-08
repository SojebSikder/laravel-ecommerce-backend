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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->boolean('sub_menu')->nullable()->default(false); // true, false
            $table->bigInteger('parent_id')->nullable(); // if sub_menu false then it can be be use for menu items
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->tinyInteger('is_link')->nullable()->default(0); // 1-> active 0-> deactive
            $table->string('link')->nullable(); // if is_link true then it will be use
            // right meta
            $table->tinyInteger('is_right')->nullable()->default(0); // 1-> active right 0-> deactive right
            $table->string('head')->nullable();
            $table->string('text')->nullable();
            $table->string('image')->nullable();
            // for image type file
            $table->text('alt_text')->nullable();
            $table->string('right_link')->nullable();

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
        Schema::dropIfExists('menus');
    }
};
