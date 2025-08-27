<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pekerjaan';
    protected $primaryKey = 'id_jenis_pekerjaan';

    protected $fillable = [
        'nama_jenis_pekerjaan',
        'deskripsi',
    ];

    /**
     * Relasi dengan DetailBiayaPekerjaan
     */
    public function detailBiayaPekerjaan(): HasMany
    {
        return $this->hasMany(DetailBiayaPekerjaan::class, 'jenis_pekerjaan_id', 'id_jenis_pekerjaan');
    }
}
