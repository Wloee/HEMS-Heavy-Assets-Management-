<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id_satuan';
    public $timestamps = true;

    protected $fillable = [
        'nama_satuan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sparepart()
    {
        return $this->hasMany(Sparepart::class, 'satuan_id', 'id_satuan');
    }
}
