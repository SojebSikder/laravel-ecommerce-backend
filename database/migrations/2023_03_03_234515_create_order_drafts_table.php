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
        Schema::create('order_drafts', function (Blueprint $table) {
            $table->id();
            // customer
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            // admin comment
            $table->text('comment')->nullable();

            // address
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('country')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // geo location
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

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
        Schema::dropIfExists('order_drafts');
    }
};
