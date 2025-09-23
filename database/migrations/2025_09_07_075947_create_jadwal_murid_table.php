<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_murid', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_id');
            $table->string('murid_id', 10);
            $table->enum('status_kehadiran', ['hadir', 'tidak_hadir', 'izin', 'sakit'])->nullable();
            $table->date('tanggal_latihan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
            $table->foreign('murid_id')->references('murid_id')->on('murid')->onDelete('cascade');
            $table->unique(['jadwal_id', 'murid_id', 'tanggal_latihan']);
            $table->index('tanggal_latihan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_murid');
    }
};