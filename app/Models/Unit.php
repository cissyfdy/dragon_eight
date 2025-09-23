<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Unit extends Model
{
    public $timestamps = false;
    protected $table = 'unit';
    protected $primaryKey = 'unit_id';
    public $incrementing = false;  
    protected $keyType = 'string'; 
    protected $fillable = [
        'unit_id',
        'nama_unit',
        'alamat',
    ];

    public function murid()
    {
        return $this->hasMany(Murid::class, 'unit_id');
    }


}
