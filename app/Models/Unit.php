<?php

// App/Models/Unit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'unit';
    protected $primaryKey = 'id_unit';

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'jenis_unit_id',
        'merk',
        'model',
        'tahun_pembuatan',
        'no_rangka',
        'no_mesin',
        'no_polisi',
        'pemilik_id',
        'alamat_unit',
        'kota',
        'provinsi',
        'jam_operasi',
        'status_kepemilikan',
        'status_kondisi',
        'status_operasional',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_operasi' => 'integer',
        'tahun_pembuatan' => 'integer'
    ];

    // Relationships
    public function jenisUnit()
    {
        return $this->belongsTo(JenisUnit::class, 'jenis_unit_id', 'id_jenis_unit');
    }

    public function pemilikUnit()
    {
        return $this->belongsTo(PemilikUnit::class, 'pemilik_id', 'id_pemilik');
    }

    public function gambarUnit()
    {
        return $this->hasOne(GambarUnit::class, 'unit_id', 'id_unit');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOperasional($query)
    {
        return $query->where('status_operasional', 'operasional');
    }

    public function scopeByJenisUnit($query, $jenisUnitId)
    {
        return $query->where('jenis_unit_id', $jenisUnitId);
    }
}
