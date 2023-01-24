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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            // icon
            $table->string('image')->nullable();
            // for image type file
            $table->text('alt_text')->nullable();
            // is this show on user shipment status
            $table->tinyInteger('on_shipping_status')->nullable()->default(0); // 0,1
            // additional feature
            $table->string('color')->nullable();
            //
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
        Schema::dropIfExists('statuses');
    }
};
