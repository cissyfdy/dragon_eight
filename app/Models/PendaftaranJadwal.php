<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranJadwal extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_jadwal';
    
    protected $fillable = [
        'murid_id',
        'jadwal_id',
        'tanggal_daftar',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    // Relationship dengan Murid
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id', 'murid_id');
    }

    // Relationship dengan Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }

    // Scope untuk status aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk filter berdasarkan murid
    public function scopeMurid($query, $muridId)
    {
        return $query->where('murid_id', $muridId);
    }

    // Scope untuk filter berdasarkan jadwal
    public function scopeJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }
}