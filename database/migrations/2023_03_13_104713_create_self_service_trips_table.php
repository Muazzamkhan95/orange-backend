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
        Schema::create('self_service_trips', function (Blueprint $table) {
            $table->id();
            $table->string('pickup')->nullable();
            $table->double('pickup_lat');
            $table->double('pickup_lng');
            $table->string('destination')->nullable();
            $table->double('destination_lat');
            $table->double('destination_lng');
            $table->integer('status')->default('1');
            $table->double('amount')->nullable();
            $table->integer('hours_count')->nullable();
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

            $table->unsignedBigInteger('service_type_id');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');

            $table->unsignedBigInteger('service_detail_id');
            $table->foreign('service_detail_id')->references('id')->on('service_details')->onDelete('cascade');
            $table->string('time');
            $table->string('phone_number');
            $table->string('distance');
            $table->string('timeofendtrip')->nullable();
            $table->text('device_token')->nullable();
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
        Schema::dropIfExists('self_service_trips');
    }
};
