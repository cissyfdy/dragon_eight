<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('unit_id');
            $table->string('pelatih_id', 10);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();

            $table->foreign('unit_id')->references('unit_id')->on('unit')->onDelete('cascade');
            $table->foreign('pelatih_id')->references('pelatih_id')->on('pelatih')->onDelete('cascade');
            $table->index(['hari', 'jam_mulai']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};