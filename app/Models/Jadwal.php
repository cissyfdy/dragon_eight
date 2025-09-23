<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';
    
    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'unit_id',
        'pelatih_id',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationship dengan Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    // Relationship dengan Pelatih
    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'pelatih_id', 'pelatih_id');
    }

    // Relationship dengan Pendaftaran Jadwal
    public function pendaftaranJadwal()
    {
        return $this->hasMany(PendaftaranJadwal::class, 'jadwal_id', 'jadwal_id');
    }

    // Relationship dengan Jadwal Murid
    public function jadwalMurid()
    {
        return $this->hasMany(JadwalMurid::class, 'jadwal_id', 'jadwal_id');
    }

    // Scope untuk jadwal aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk filter berdasarkan hari
    public function scopeHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    // Scope untuk filter berdasarkan unit
    public function scopeUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    // Method untuk mendapatkan jadwal dengan format yang readable
    public function getJamFormatted()
    {
        return date('H:i', strtotime($this->jam_mulai)) . ' - ' . date('H:i', strtotime($this->jam_selesai));
    }

    // Method untuk mendapatkan murid yang terdaftar di jadwal ini
    public function getMuridTerdaftar()
    {
        return $this->pendaftaranJadwal()
                   ->where('status', 'aktif')
                   ->with('murid')
                   ->get();
    }

    // Method untuk menghitung jumlah murid terdaftar
    public function getJumlahMuridTerdaftar()
    {
        return $this->pendaftaranJadwal()
                   ->where('status', 'aktif')
                   ->count();
    }
}