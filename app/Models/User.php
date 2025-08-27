<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'karyawan_id',
        'is_active',
        'last_login',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Boot method untuk model events
     */
    protected static function boot()
    {
        parent::boot();

        // Set default values
        static::creating(function ($user) {
            if (is_null($user->is_active)) {
                $user->is_active = true;
            }
        });
    }

    /**
     * Relationship dengan Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id_karyawan');
    }

    /**
     * Scope untuk user aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk user nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope untuk role tertentu
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Mutator untuk email (lowercase)
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Mutator untuk name (trim)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    /**
     * Accessor untuk role name (kapitalisasi)
     */
    public function getRoleNameAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    /**
     * Accessor untuk full info (nama + email)
     */
    public function getFullInfoAttribute()
    {
        return $this->name . ' (' . $this->email . ')';
    }

    /**
     * Check apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check apakah user adalah manager
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Check apakah user adalah operator
     */
    public function isOperator()
    {
        return $this->role === 'operator';
    }

    /**
     * Check apakah user adalah teknisi
     */
    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    /**
     * Check apakah user memiliki role tertentu
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check apakah user memiliki salah satu dari roles
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * Get all available roles
     */
    public static function getRoles()
    {
        return [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'operator' => 'Operator',
            'teknisi' => 'Teknisi'
        ];
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColorAttribute()
    {
        $colors = [
            'admin' => 'danger',
            'manager' => 'primary',
            'operator' => 'success',
            'teknisi' => 'warning'
        ];

        return $colors[$this->role] ?? 'secondary';
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }

    /**
     * Check if user has logged in before
     */
    public function hasLoggedInBefore()
    {
        return !is_null($this->last_login);
    }

    /**
     * Get last login formatted
     */
    public function getLastLoginFormattedAttribute()
    {
        if (!$this->last_login) {
            return 'Belum pernah login';
        }

        return $this->last_login->format('d/m/Y H:i');
    }
}
