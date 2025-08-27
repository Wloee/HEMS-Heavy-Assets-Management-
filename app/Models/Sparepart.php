<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{

    use HasFactory;
    // use SoftDeletes; // Uncomment if you want to use soft deletes

    protected $table = 'sparepart';
    protected $primaryKey = 'id_sparepart';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kode_sparepart',
        'nama_sparepart',
        'merk',
        'supplier_id',
        'stok_minimum',
        'stok_saat_ini',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'id_sparepart' => 'integer',
        'supplier_id' => 'integer',
        'stok_minimum' => 'integer',
        'stok_saat_ini' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     */


    /**
     * Relationship with Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Suplier::class, 'supplier_id', 'id_supplier');
    }

    /**
     * Scope for active spareparts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive spareparts
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope for low stock spareparts
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stok_saat_ini <= stok_minimum');
    }

    /**
     * Scope for critical stock spareparts
     */
    public function scopeCriticalStock($query)
    {
        return $query->whereRaw('stok_saat_ini < stok_minimum');
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('kode_sparepart', 'like', '%' . $search . '%')
              ->orWhere('nama_sparepart', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope for filter by supplier
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Get formatted price accessor
     */

    /**
     * Get stock status accessor
     */
    public function getStatusStokAttribute()
    {
        if ($this->stok_saat_ini <= 0) {
            return 'empty'; // Stok habis
        } elseif ($this->stok_saat_ini < $this->stok_minimum) {
            return 'critical'; // Stok kritis (dibawah minimum)
        } elseif ($this->stok_saat_ini <= ($this->stok_minimum * 1.5)) {
            return 'warning'; // Stok peringatan
        } else {
            return 'normal'; // Stok normal
        }
    }

    /**
     * Get status text accessor
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Get stock color for display
     */
    public function getStockColorAttribute()
    {
        switch ($this->status_stok) {
            case 'empty':
                return '#dc2626'; // Red-600
            case 'critical':
                return '#ef4444'; // Red-500
            case 'warning':
                return '#f59e0b'; // Amber-500
            case 'normal':
                return '#10b981'; // Emerald-500
            default:
                return '#6b7280'; // Gray-500
        }
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d F Y') : '-';
    }

    /**
     * Get formatted updated date
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d F Y H:i') : '-';
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    /**
     * Check if stock is critical
     */
    public function isCriticalStock()
    {
        return $this->stok_saat_ini < $this->stok_minimum;
    }

    /**
     * Check if stock is empty
     */
    public function isEmptyStock()
    {
        return $this->stok_saat_ini <= 0;
    }

    /**
     * Get stock percentage compared to minimum
     */
    public function getStockPercentage()
    {
        if ($this->stok_minimum == 0) {
            return 100;
        }
        return ($this->stok_saat_ini / $this->stok_minimum) * 100;
    }

    /**
     * Calculate stock value
     */


    /**
     * Get formatted stock value
     */
    public function getFormattedStockValueAttribute()
    {
        $value = $this->getStockValue();
        if ($value > 0) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        }
        return '-';
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto generate kode_sparepart if not provided
        static::creating(function ($model) {
            if (empty($model->kode_sparepart)) {
                $model->kode_sparepart = static::generateKode();
            }
        });

        // Log stock changes (uncomment if you have stock movement table)
        /*
        static::updating(function ($model) {
            if ($model->isDirty('stok_saat_ini')) {
                $original = $model->getOriginal('stok_saat_ini');
                $new = $model->stok_saat_ini;

                StockMovement::create([
                    'sparepart_id' => $model->id_sparepart,
                    'type' => $new > $original ? 'in' : 'out',
                    'quantity' => abs($new - $original),
                    'stock_before' => $original,
                    'stock_after' => $new,
                    'keterangan' => 'System update',
                    'created_by' => auth()->id() ?? null
                ]);
            }
        });
        */
    }

    /**
     * Generate unique kode sparepart
     */
    public static function generateKode()
    {
        $prefix = 'SP';
        $date = date('ym'); // format: YYMM

        // Get last number for this month
        $lastSparepart = static::where('kode_sparepart', 'like', $prefix . $date . '%')
                              ->orderBy('kode_sparepart', 'desc')
                              ->first();

        if ($lastSparepart) {
            $lastNumber = intval(substr($lastSparepart->kode_sparepart, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get spareparts that need reorder
     */
    public static function needReorder()
    {
        return static::active()->lowStock()->get();
    }

    /**
     * Get total stock value
     */
    
}
