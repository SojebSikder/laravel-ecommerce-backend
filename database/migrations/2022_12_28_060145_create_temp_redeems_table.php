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
        Schema::create('temp_redeems', function (Blueprint $table) {
            $table->id();

            // user id who redeems the voucher
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            // coupon id
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('temp_redeems');
    }
};
