<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMurid extends Model
{
    use HasFactory;

    protected $table = 'jadwal_murid';
    
    protected $fillable = [
        'jadwal_id',
        'murid_id',
        'status_kehadiran',
        'tanggal_latihan',
        'catatan'
    ];

    protected $casts = [
        'tanggal_latihan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status kehadiran enum values
    const STATUS_HADIR = 'hadir';
    const STATUS_TIDAK_HADIR = 'tidak_hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';

    public static function getStatusKehadiranOptions()
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_TIDAK_HADIR => 'Tidak Hadir',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit'
        ];
    }

    // Relationships
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id', 'murid_id');
    }

    // Scopes
    public function scopeByJadwal($query, $jadwalId)
    {
        return $query->where('jadwal_id', $jadwalId);
    }

    public function scopeByMurid($query, $muridId)
    {
        return $query->where('murid_id', $muridId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal_latihan', $tanggal);
    }

    public function scopeHadir($query)
    {
        return $query->where('status_kehadiran', self::STATUS_HADIR);
    }

    public function scopeTidakHadir($query)
    {
        return $query->where('status_kehadiran', self::STATUS_TIDAK_HADIR);
    }

    public function scopeIzin($query)
    {
        return $query->where('status_kehadiran', self::STATUS_IZIN);
    }

    public function scopeSakit($query)
    {
        return $query->where('status_kehadiran', self::STATUS_SAKIT);
    }

    // Accessor
    public function getStatusKehadiranLabelAttribute()
    {
        $options = self::getStatusKehadiranOptions();
        return $options[$this->status_kehadiran] ?? $this->status_kehadiran;
    }

    // Helper methods
    public function isHadir()
    {
        return $this->status_kehadiran === self::STATUS_HADIR;
    }

    public function isTidakHadir()
    {
        return $this->status_kehadiran === self::STATUS_TIDAK_HADIR;
    }

    public function isIzin()
    {
        return $this->status_kehadiran === self::STATUS_IZIN;
    }

    public function isSakit()
    {
        return $this->status_kehadiran === self::STATUS_SAKIT;
    }
}
