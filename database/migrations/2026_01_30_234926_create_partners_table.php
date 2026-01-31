<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('id_fullname');
            $table->string('id_number');
            $table->date('id_birth_date');
            $table->string('id_image');
            $table->string('dl_fullname');
            $table->string('dl_number');
            $table->date('dl_birth_date');
            $table->string('dl_image');
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
        Schema::dropIfExists('partners');
    }
};