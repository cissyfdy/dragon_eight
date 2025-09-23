<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran'; // Specify the correct table name
    protected $primaryKey = 'iuran_id';
    public $timestamps = true; // Based on your schema, you have created_at and updated_at
    
    protected $fillable = [
        'murid_id',
        'bulan',
        'tahun',
        'nominal',
        'tanggal_bayar',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:0',
        'tahun' => 'integer',
    ];

    /**
     * Relationship with Murid model
     */
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id', 'murid_id');
    }

    /**
     * Scope for unpaid fees
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'Belum Lunas');
    }

    /**
     * Scope for paid fees
     */
    public function scopeLunas($query)
    {
        return $query->where('status', 'Lunas');
    }

    /**
     * Scope for specific year
     */
    public function scopeTahun($query, $year)
    {
        return $query->where('tahun', $year);
    }

    /**
     * Scope for specific month
     */
    public function scopeBulan($query, $month)
    {
        return $query->where('bulan', $month);
    }

    /**
     * Get formatted nominal
     */
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'Lunas' ? 'badge-success' : 'badge-warning';
    }
}