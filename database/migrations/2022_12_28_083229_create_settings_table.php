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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_type_id')->nullable()->constrained()->onDelete('set null');
            $table->string('label')->nullable();
            $table->string('key')->nullable();
            $table->longText('value')->nullable();
            // values: text, textarea, html, file, color, checkbox
            $table->string('value_type')->nullable()->default('text');
            // if value_type is file.
            // image format: 90x90. Must separeted by 'x' letter.
            // default unit is px(pixel)
            $table->string('size')->nullable();
            $table->text('description')->nullable();
            //
            $table->string('collection_name')->nullable();
            $table->text('collection_details')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
