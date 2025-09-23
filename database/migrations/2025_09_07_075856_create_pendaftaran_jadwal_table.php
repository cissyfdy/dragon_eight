<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran_jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('murid_id', 10);
            $table->unsignedBigInteger('jadwal_id');
            $table->date('tanggal_daftar');
            $table->enum('status', ['aktif', 'tidak_aktif', 'suspend'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('murid_id')->references('murid_id')->on('murid')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
            $table->unique(['murid_id', 'jadwal_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_jadwal');
    }
};