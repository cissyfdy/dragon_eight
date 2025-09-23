<?php
// ========================================
// MODEL UJIAN (app/Models/Ujian.php)
// ========================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujian';
    protected $primaryKey = 'ujian_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_ujian',
        'tanggal_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'unit_id',
        'pelatih_id',
        'sabuk_dari',
        'sabuk_ke',
        'biaya_ujian',
        'kuota_peserta',
        'status_ujian',
        'persyaratan',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'biaya_ujian' => 'decimal:0'
    ];

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    // Relasi ke Pelatih
    public function pelatih()
    {
        return $this->belongsTo(Pelatih::class, 'pelatih_id', 'pelatih_id');
    }

    // Relasi ke Pendaftaran Ujian
    public function pendaftaranUjian()
    {
        return $this->hasMany(PendaftaranUjian::class, 'ujian_id', 'ujian_id');
    }

    // Scope untuk filter status
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status_ujian', $status);
        }
        return $query;
    }

    // Scope untuk filter unit
    public function scopeByUnit($query, $unitId)
    {
        if ($unitId) {
            return $query->where('unit_id', $unitId);
        }
        return $query;
    }

    // Accessor untuk jumlah peserta terdaftar
    public function getJumlahPesertaAttribute()
    {
        return $this->pendaftaranUjian()->where('status_pendaftaran', 'diterima')->count();
    }

    // Accessor untuk sisa kuota
    public function getSisaKuotaAttribute()
    {
        return $this->kuota_peserta - $this->jumlah_peserta;
    }

    // Accessor untuk status kuota
    public function getStatusKuotaAttribute()
    {
        if ($this->sisa_kuota <= 0) {
            return 'penuh';
        } elseif ($this->sisa_kuota <= 5) {
            return 'terbatas';
        }
        return 'tersedia';
    }
}