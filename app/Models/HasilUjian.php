<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujian';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ujian_id',
        'murid_id',
        'nilai_teori',
        'nilai_praktik',
        'nilai_fisik',
        'nilai_total',
        'status_kelulusan',
        'sabuk_baru',
        'catatan_penguji',
        'tanggal_pengumuman'
    ];

    protected $casts = [
        'nilai_teori' => 'decimal:2',
        'nilai_praktik' => 'decimal:2',
        'nilai_fisik' => 'decimal:2',
        'nilai_total' => 'decimal:2',
        'tanggal_pengumuman' => 'date'
    ];

    // Relasi ke Ujian
    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id', 'ujian_id');
    }

    // Relasi ke Murid
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id', 'murid_id');
    }
}