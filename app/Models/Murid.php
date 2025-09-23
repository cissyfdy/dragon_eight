<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    public $timestamps = false;
    protected $table = 'murid';
    protected $primaryKey = 'murid_id';
    public $incrementing = false;  
    protected $keyType = 'string'; 
    protected $fillable = [
        'murid_id',
        'id',
        'nama_murid',
        'nomor_register',
        'unit_id',
        'tanggal_lahir',
        'alamat',
        'tingkat_sabuk',
        'no_hp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date'
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    // Relasi ke tabel unit
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }
}
