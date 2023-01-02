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
        Schema::create('option_set_elements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('option_set_id')->nullable()->constrained('option_sets')->onUpdate('cascade')->onDelete('cascade');
            /**
             * available values:
             * text, 
             * select, 
             * textarea, 
             * dialog,
             * fontpreview
             * imagepreview
             */
            $table->string('type')->nullable();
            // ----general----
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('required')->nullable()->default(0);
            $table->longText('style')->nullable(); // custom inline css
            $table->text('placeholder')->nullable();
            $table->text('help_text')->nullable(); // html title
            $table->integer('limit')->nullable(); // limit character, for text,textarea
            $table->string('column_width')->nullable();
            $table->string('class_name')->nullable(); // user can add html custom class name
            // ----condition----
            // if condition fullfilled then this element will be show up
            // condition format: {action: show/hide, match: all/any,
            // condition:[{element_name:name, condition:condition, value:value}]}
            $table->tinyInteger('is_condition')->nullable()->default(0);
            $table->longText('condition_data')->nullable();
            // ----dialog type----
            $table->string('dialog_title')->nullable();
            $table->longText('dialog_body')->nullable();
            // ----select type----
            // option format: [{price:price,value:value,text:text}]
            $table->longText('option_value')->nullable();
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
        Schema::dropIfExists('option_set_elements');
    }
};
