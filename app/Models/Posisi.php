<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    protected $table = 'posisi';
    protected $primaryKey = 'id_posisi';
    protected $fillable = ['nama_posisi'];
    public $timestamps = true;
}
