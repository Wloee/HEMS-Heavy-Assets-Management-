<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sparepart_pembelian_detail extends Model
{
    use HasFactory;

    protected $table = 'sparepart_pengadaan_detail';
    protected $primaryKey = 'id_detail';
    protected $fillable = [
        'pembelian_id',
        'sparepart_id',
        'subtotal',
        'satuan_id',
        'harga_satuan',
        'jumlah',
        'kode_sparepart',
    ];
    public $timestamps = false;

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

}
