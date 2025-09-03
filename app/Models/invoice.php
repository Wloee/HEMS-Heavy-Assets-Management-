<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'id_invoice';

    protected $fillable = [
        'proyek_id',
        'tanggal_invoice',
        'tanggal_jatuh_tempo',
        'jumlah_tagihan',
        'sisa_piutang',
        'status',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'jumlah_tagihan' => 'decimal:2',
        'sisa_piutang' => 'decimal:2',
    ];

    /**
     * Relasi dengan Proyek
     */
    public function proyek(): BelongsTo
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id_proyek');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-secondary',
            'terkirim' => 'bg-info',
            'dibayar_sebagian' => 'bg-warning',
            'lunas' => 'bg-success',
            'jatuh_tempo' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Format jumlah tagihan
     */
    public function getFormattedJumlahTagihanAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_tagihan, 0, ',', '.');
    }

    /**
     * Format sisa piutang
     */
    public function getFormattedSisaPiutangAttribute()
    {
        return 'Rp ' . number_format($this->sisa_piutang, 0, ',', '.');
    }

    /**
     * Check if invoice is overdue
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->tanggal_jatuh_tempo) {
            return false;
        }

        return now()->gt($this->tanggal_jatuh_tempo) &&
               !in_array($this->status, ['lunas']);
    }public static function statusOptions(): array
    {
        return [
            'draft' => ['label' => 'Draft', 'class' => 'badge bg-secondary'],
            'dibayar_sebagian' => ['label' => 'Dibayar Sebagian', 'class' => 'badge bg-warning'],
            'lunas' => ['label' => 'Lunas', 'class' => 'badge bg-success'],
            'jatuh_tempo' => ['label' => 'Jatuh Tempo', 'class' => 'badge bg-danger'],
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::statusOptions()[$this->status]['label'] ?? 'Draft';
    }

    public function getStatusClassAttribute()
    {
        return self::statusOptions()[$this->status]['class'] ?? 'badge bg-secondary';
    }
}
