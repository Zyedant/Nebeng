<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partner_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('id_fullname');
            $table->string('id_number');
            $table->string('id_image');
            $table->string('terminal_name');
            $table->foreignId('terminal_province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('terminal_regency_id')->constrained('regencies')->onDelete('cascade');
            $table->foreignId('terminal_district_id')->constrained('districts')->onDelete('cascade');
            $table->string('terminal_map_coordinate')->nullable();
            $table->text('terminal_address');
            $table->enum('verified_status', [
                'Belum Verifikasi',
                'Pengajuan', 
                'Terverifikasi', 
                'Ditolak'
            ])->default('Belum Verifikasi');
            $table->text('verified_status_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partner_posts');
    }
};