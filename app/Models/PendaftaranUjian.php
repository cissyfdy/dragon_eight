<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranUjian extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_ujian';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ujian_id',
        'murid_id',
        'tanggal_daftar',
        'status_pendaftaran',
        'status_pembayaran',
        'tanggal_bayar',
        'catatan_pendaftaran'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'tanggal_bayar' => 'date'
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