<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pelatih extends Model
{
    use HasFactory;

    protected $table = 'pelatih';
    protected $primaryKey = 'pelatih_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = [
        'pelatih_id',
        'nama_pelatih',
        'id',
        'alamat',
        'no_hp'
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    // Relationship dengan Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'pelatih_id', 'pelatih_id');
    }

    // Method untuk mendapatkan jadwal aktif
    public function jadwalAktif()
    {
        return $this->jadwal()->where('status', 'aktif');
    }

    // Method untuk mendapatkan jumlah jadwal mengajar
    public function getJumlahJadwal()
    {
        return $this->jadwal()->where('status', 'aktif')->count();
    }

    // Method untuk mendapatkan total murid yang diajar
    public function getTotalMurid()
    {
        return $this->jadwal()
                   ->where('status', 'aktif')
                   ->withCount(['pendaftaranJadwal' => function($query) {
                       $query->where('status', 'aktif');
                   }])
                   ->get()
                   ->sum('pendaftaran_jadwal_count');
    }

    // Method untuk mendapatkan unit yang diajar
    public function getUnitMengajar()
    {
        return $this->jadwal()
                   ->where('status', 'aktif')
                   ->with('unit')
                   ->get()
                   ->pluck('unit')
                   ->unique('unit_id');
    }

    // Method untuk mendapatkan jadwal berdasarkan hari
    public function getJadwalByHari($hari)
    {
        return $this->jadwal()
                   ->where('status', 'aktif')
                   ->where('hari', $hari)
                   ->with('unit')
                   ->orderBy('jam_mulai')
                   ->get();
    }

    // Method untuk mendapatkan total jam mengajar per minggu
    public function getTotalJamMengajar()
    {
        $jadwalList = $this->jadwal()->where('status', 'aktif')->get();
        $totalJam = 0;
        
        foreach ($jadwalList as $jadwal) {
            $start = \Carbon\Carbon::parse($jadwal->jam_mulai);
            $end = \Carbon\Carbon::parse($jadwal->jam_selesai);
            $totalJam += $end->diffInHours($start);
        }
        
        return $totalJam;
    }

    // Scope untuk pencarian berdasarkan nama
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_pelatih', 'LIKE', '%' . $search . '%');
    }

    // Accessor untuk format nomor HP
    public function getFormattedNoHpAttribute()
    {
        if (!$this->no_hp) return 'Tidak tersedia';
        
        // Format nomor HP Indonesia
        $hp = preg_replace('/[^0-9]/', '', $this->no_hp);
        if (substr($hp, 0, 1) === '0') {
            $hp = '62' . substr($hp, 1);
        }
        
        return '+' . $hp;
    }


}