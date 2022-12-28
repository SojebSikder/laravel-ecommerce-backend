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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // If method is code then coupon code is required. 
            // if automatic then its not required. user will be get discount  autometically
            // values: code, auto
            $table->string('method')->nullable()->default('code');
            $table->string('code')->nullable();
            // if code is auto, then title will show
            $table->string('title')->nullable();
            // The human readable voucher code name
            $table->string('name')->nullable();
            // The description of the voucher - Not necessary 
            $table->text('description')->nullable();
            $table->decimal('amount')->nullable();
            // Whether or not the voucher is a percentage or a fixed price. 
            // values: percentage,fixed
            $table->string('amount_type')->nullable()->default('percentage');
            // The number of uses currently
            $table->integer('uses')->unsigned()->nullable()->default(0);
            // The max uses this voucher has (Total)
            $table->integer('max_uses')->unsigned()->nullable();
            // How many times a user can use this voucher. (Total for single user)
            $table->integer('max_uses_user')->unsigned()->nullable();
            // Coupon discount on specific type of product
            // - Category wise = category -> get discount for categorize product
            // - Product wise = product -> get discount for specific product
            // - order wise = order -> get discount for order
            $table->string('coupon_type')->nullable()->default('order');
            // If coupon_type filled with product or category
            // then it will be required
            // such as: category_id or product_id
            $table->string('coupon_id')->nullable(); // format: for product, category: [{id: id}]
            // When the voucher begins
            $table->date('starts_at')->nullable();
            // When the voucher ends
            $table->date('expires_at')->nullable();
            // Minimum purchase requirements
            $table->string('min_type')->nullable(); // values: none, amount, quantity
            $table->decimal('min_amount')->nullable();
            $table->decimal('min_qnty')->nullable();
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('coupons');
    }
};
