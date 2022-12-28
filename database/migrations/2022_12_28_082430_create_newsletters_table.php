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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('type')->nullable(); // promotion
            $table->tinyInteger('global')->nullable()->default(0); // 0 => newsletter will send to all user even if they don't have subscribed
            // date time
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->tinyInteger('is_repeat')->nullable()->default(0); // 1-> active, 0-> deactive
            $table->decimal('repeat_after')->nullable()->default(0); // 1-> active, 0-> deactive
            //
            $table->tinyInteger('status')->nullable()->default(1); // 1-> active, 0-> deactive
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
        Schema::dropIfExists('newsletters');
    }
};
