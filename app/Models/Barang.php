<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan_id',
        'gudang_id',
        'stok',
        'stok_minimal',
        'lokasi',
        'keterangan'
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function permintaan()
    {
        return $this->hasMany(Permintaan::class);
    }
}