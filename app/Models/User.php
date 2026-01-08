<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'nrp',
        'email',
        'password',
        'role',
        'satker_id',
        'jabatan',
        'pangkat',
        'no_hp',
        'is_active',
        'last_login_at',
        'current_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'current_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function permintaan()
    {
        return $this->hasMany(Permintaan::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

   // Tambahkan hubungan dengan Procurement
    public function procurements()
    {
        return $this->hasMany(Procurement::class, 'user_id');
    }

    public function approvedProcurements()
    {
        return $this->hasMany(Procurement::class, 'disetujui_oleh');
    }

    public function processedProcurements()
    {
        return $this->hasMany(Procurement::class, 'diproses_oleh');
    }

    public function completedProcurements()
    {
        return $this->hasMany(Procurement::class, 'selesai_oleh');
    }

    public function cancelledProcurements()
    {
        return $this->hasMany(Procurement::class, 'dibatalkan_oleh');
    }
}
