<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'nama_proyek',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai_aktual',
        'id_addendum',
        'nama_client',
        'lokasi_proyek',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai_aktual' => 'date',
    ];

    /**
     * Relasi dengan DetailBiayaPekerjaan
     */
    public function detailBiayaPekerjaan(): HasMany
    {
        return $this->hasMany(DetailBiayaPekerjaan::class, 'proyek_id', 'id_proyek');
    }

    /**
     * Relasi dengan Invoice
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'proyek_id', 'id_proyek');
    }

    /**
     * Get total biaya proyek
     */
    public function getTotalBiayaAttribute()
    {
        return $this->detailBiayaPekerjaan->sum('biaya_total');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'aktif' => 'bg-success',
            'selesai' => 'bg-primary',
            'ditunda' => 'bg-warning',
            'dibatalkan' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
