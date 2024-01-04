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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();

            /**
             * SEO
             */
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable(); // max limit 320
            $table->text('meta_keyword')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            $table->longText('header_custom_html')->nullable();
            $table->longText('footer_custom_html')->nullable();
            $table->longText('robots_txt')->nullable();

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
        Schema::dropIfExists('general_settings');
    }
};
