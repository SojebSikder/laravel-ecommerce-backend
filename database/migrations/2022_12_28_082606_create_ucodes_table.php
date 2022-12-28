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
        Schema::create('ucodes', function (Blueprint $table) {
            $table->id();

            $table->string('user_id')->nullable();
            $table->text('token')->nullable();
            $table->string('email')->nullable();
            // available values
            // - password_recover
            // - email_verify
            $table->string('for')->nullable();
            // currently not using
            $table->date('expired_at')->nullable();

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
        Schema::dropIfExists('ucodes');
    }
};
