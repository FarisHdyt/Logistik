<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relationship dengan Permintaan
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    // Relationship dengan Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Scope untuk barang tertentu
    public function scopeBarang($query, $barangId)
    {
        return $query->where('barang_id', $barangId);
    }

    // Scope untuk permintaan tertentu
    public function scopePermintaan($query, $permintaanId)
    {
        return $query->where('permintaan_id', $permintaanId);
    }
}