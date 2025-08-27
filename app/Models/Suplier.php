<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    protected $fillable =[
        'nama_supplier',
        'alamat',
        'no_handphone',
        'email',
        
    ];
    protected $primaryKey = 'id_supplier';
    protected $table = 'supplier';

}
