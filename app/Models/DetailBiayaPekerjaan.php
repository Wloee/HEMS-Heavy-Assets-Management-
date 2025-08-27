<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBiayaPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'detail_biaya_pekerjaan';
    protected $primaryKey = 'id_detail_biaya';

    protected $fillable = [
        'proyek_id',
        'jenis_pekerjaan_id',
        'deskripsi',
        'biaya_total',
    ];

    protected $casts = [
        'biaya_total' => 'decimal:2',
    ];

    /**
     * Relasi dengan Proyek
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id_proyek');
    }

    /**
     * Relasi dengan JenisPekerjaan
     */
    public function jenisPekerjaan(): BelongsTo
    {
        return $this->belongsTo(JenisPekerjaan::class, 'jenis_pekerjaan_id', 'id_jenis_pekerjaan');
    }

    /**
     * Format biaya total
     */
    public function getFormattedBiayaTotalAttribute()
    {
        return 'Rp ' . number_format($this->biaya_total, 0, ',', '.');
    }
}
