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
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->id();

            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();

            // $table->tinyInteger('is_sandbox')->nullable()->default(0);
            // $table->string('client_id')->nullable();
            // $table->string('secret_key')->nullable();

            $table->tinyInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('payment_providers');
    }
};
