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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            // code
            $table->string('country_code')->nullable();
            $table->string('dial_code')->nullable();
            // country flag
            $table->string('flag')->nullable();

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
        Schema::dropIfExists('countries');
    }
};
