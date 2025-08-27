<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisUnit extends Model
{
    protected $table = 'jenis_unit';
    protected $primaryKey = 'id_jenis_unit';
    public $timestamps = true;
    protected $fillable = [
        'nama_jenis',
        'deskripsi',
    ];
     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id_jenis_unit';
    }

    /**
     * Scope a query to search jenis units.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_jenis', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to order by nama_jenis.
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('nama_jenis', $direction);
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d F Y') : '-';
    }

    /**
     * Get formatted updated date.
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('d F Y') : '-';
    }

    /**
     * Get formatted created datetime.
     */
    public function getFormattedCreatedDatetimeAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d F Y H:i') : '-';
    }

    /**
     * Get formatted updated datetime.
     */
    public function getFormattedUpdatedDatetimeAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('d F Y H:i') : '-';
    }


    /**
     * Check if jenis unit has description.
     */
    public function hasDescription(): bool
    {
        return !empty($this->deskripsi);
    }



    /**
     * Get units count (if relationship exists).
     * Uncomment and modify based on your actual relationships.
     */
    /*
    public function getUnitsCountAttribute(): int
    {
        return $this->units()->count();
    }
    */

    /**
     * Check if jenis unit can be deleted (not used by other records).
     * Uncomment and modify based on your actual relationships.
     */
    /*
    public function canBeDeleted(): bool
    {
        return $this->units()->count() === 0;
    }
    */

    /**
     * Boot the model.
     */
}
