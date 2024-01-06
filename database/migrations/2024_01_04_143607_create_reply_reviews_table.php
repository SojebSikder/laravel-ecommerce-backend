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
        Schema::create('reply_reviews', function (Blueprint $table) {
            $table->id();

            $table->string('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreignId('review_id')->nullable()->constrained('reviews')->onDelete('cascade');

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
        Schema::dropIfExists('reply_reviews');
    }
};
