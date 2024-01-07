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
        // we need to store some data hard coded, 
        // because in future dependent table data may be chaanged or deleted
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            /**
             * Tracking
             */
            // this will be using to track user order
            $table->string('invoice_number')->nullable()->unique();
            // this is payment ref id for other payment gateway like bsecure
            $table->text('payment_ref_id')->nullable();
            // for additional custom information (currently not using) 
            $table->longText('properties')->nullable();
            // tracking number provided by courier company
            $table->string('tracking_number')->nullable();
            $table->string('courier_provider')->nullable()->default('pathao');
            /**
             * price
             */
            // product price total
            $table->decimal('sub_total')->nullable();
            // all total price
            // without shipping_charge
            $table->decimal('order_total')->nullable();

            /**
             * Shipping
             */
            $table->foreignId('shipping_zone_id')->nullable()->constrained('shipping_zones')->onDelete('set null');
            $table->string('shipping_zone_name')->nullable();
            $table->decimal('shipping_charge')->nullable();

            // 1: Cash on delivery -> cod
            // 2. stripe -> stripe
            $table->foreignId('payment_provider_id')->nullable()->constrained('payment_providers')->onDelete('set null');
            $table->string('payment_provider')->nullable();
            // percentage
            // fixed
            $table->string('payment_provider_charge_type')->nullable()->default('percentage');
            $table->string('payment_provider_charge')->nullable();

            /**
             * Payment status. available value:
             * 
             * unpaid
             * paid
             * processing
             * refunded
             * failed
             */
            $table->string('payment_status')->nullable(); // values: 
            // raw information receive from payment provider
            // raw status from payment provider
            $table->string('payment_raw_status')->nullable();
            $table->decimal('paid_amount')->nullable();
            $table->string('paid_currency')->nullable();

            /**
             * // status value handling dynamically
             * available value:
             *
             * 'order_placed',
             * 'order_processing',
             * //'order_picked',
             * //'order_on_way',
             * 'order_delivered',
             * 'order_returned',
             * 'order_cancelled',
             * 'order_paused'
             */
            $table->string('status')->nullable();

            /**
             * if order item is fullfill or not
             * available value:
             * 'unfulfilled'
             * 'fulfilled'
             */
            $table->string('fulfillment_status')->nullable()->default('unfulfilled');
            $table->string('currency')->nullable();
            $table->text('comment')->nullable();

            // user shipping address
            $table->foreignId('order_shipping_address_id')->nullable()->constrained('order_shipping_addresses')->onDelete('set null');
            $table->foreignId('order_billing_address_id')->nullable()->constrained('order_shipping_addresses')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // geo location
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // hardcoded contact info
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
