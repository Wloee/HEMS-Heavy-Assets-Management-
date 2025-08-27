<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class sparepart_pembelian extends Model
{
 use HasFactory;

    protected $table = 'sparepart_pengadaan';
    protected $primaryKey = 'id_pembelian';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_pembelian',
        'supplier_id',
        'total_harga',
    ];

    public function supplier()
    {
        return $this->belongsTo(Suplier::class, 'supplier_id', 'id_supplier');
    }
}
