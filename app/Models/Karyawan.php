<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
        use HasFactory;
        protected $primaryKey = 'id_karyawan';
        protected $table = 'karyawan';
        public $incrementing = true;     // auto increment
        protected $keyType = 'int';
        protected $fillable = [
            // Data Pribadi
            'nama_karyawan',
            'tanggal_lahir',
            'no_handphone',
            'no_nik',

            // Data Pekerjaan
            'posisi_id',
            'departemen_id',
            'tanggal_bergabung',

            // Data Penggajian
            'Gaji',
            'Tunjangan',
            'Intensif',
        ];

    // Relasi dengan model lain (jika ada)
    public function posisi()
    {
        return $this->belongsTo(Posisi::class, 'posisi_id', 'id_posisi');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id', 'id_departemen');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenKaryawan::class, 'karyawan_id');
    }


}
