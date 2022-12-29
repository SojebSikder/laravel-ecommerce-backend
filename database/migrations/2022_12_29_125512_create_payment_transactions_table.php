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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_id')->nullable()->references('id')->on('orders')->onDelete('set null');
            $table->string('transaction_type')->nullable()->default('order');
            $table->text('transaction_id')->nullable();
            // stripe etc..
            $table->string('transaction_provider')->nullable();
            $table->foreignId('user_billing_address_id')->nullable()->constrained('user_shipping_addresses')->onDelete('set null');
            $table->decimal('amount')->nullable();
            $table->string('currency')->nullable();
            /**
             * Available values:
             * succeeded
             * processing
             * requires_payment_method
             */
            $table->string('status')->nullable()->default('requires_payment_method');
            $table->softDeletes();
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
        Schema::dropIfExists('payment_transactions');
    }
};
