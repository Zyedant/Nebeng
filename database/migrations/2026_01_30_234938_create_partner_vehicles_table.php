<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partner_vihecles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partners')->onDelete('cascade');
            $table->enum('vihecle_type', ['Mobil', 'Motor']);
            $table->string('vihecle_plate_number');
            $table->string('vihecle_brand');
            $table->string('vihecle_name');
            $table->string('vihecle_color');
            $table->string('registration_number');
            $table->string('registration_vihecle_identity_number');
            $table->string('registration_engine_number');
            $table->string('registration_image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partner_vihecles');
    }
};