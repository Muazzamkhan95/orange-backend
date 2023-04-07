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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('pickup')->nullable();
            $table->double('pickup_lat');
            $table->double('pickup_lag');
            $table->string('destination')->nullable();
            $table->double('destination_lat');
            $table->double('destination_lag');
            $table->integer('status')->default('1');
            $table->double('amount')->nullable();
            $table->integer('hours_count')->nullable();
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');

            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

            $table->unsignedBigInteger('service_type_id');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');

            $table->unsignedBigInteger('service_detail_id');
            $table->foreign('service_detail_id')->references('id')->on('service_details')->onDelete('cascade');
            $table->string('time');
            $table->string('distance');

            $table->string('notes')->nullable();
            $table->text('device_token')->nullable();
            $table->string('timeofendtrip')->nullable();
            $table->integer('isSchedule')->default('0');
            $table->string('time_of_schedule_booking')->nullable();
            $table->string('date_of_schedule_booking')->nullable();
            $table->unsignedBigInteger('promo_code_id')->nullable();
            $table->foreign('promo_code_id')->references('id')->on('promo_codes')->onDelete('cascade');
            $table->double('discount_price')->nullable();
            $table->double('total_price')->nullable();
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
        Schema::dropIfExists('trips');
    }
};
