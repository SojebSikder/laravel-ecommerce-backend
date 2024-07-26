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
        Schema::create('contact_forms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->text('body')->nullable();

            $table->timestamp('read_at')->nullable();
            // mark issue as resolve
            $table->timestamp('resolved_at')->nullable();
            $table->string('resolved_status')->nullable();
            // resolved by
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');

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
        Schema::dropIfExists('contact_forms');
    }
};
