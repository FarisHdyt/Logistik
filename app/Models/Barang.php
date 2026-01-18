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

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function procurements()
    {
        return $this->hasMany(Procurement::class, 'barang_id');
    }

    public function procurementItems()
    {
        return $this->hasMany(ProcurementItem::class, 'barang_id');
    }


        // ==================== ACCESSOR METHODS ====================
    
    /**
     * Get nama_kategori attribute
     */
    public function getNamaKategoriAttribute()
    {
        return $this->kategori ? $this->kategori->nama_kategori : 'Belum Ada Kategori';
    }

    /**
     * Get nama_satuan attribute
     */
    public function getNamaSatuanAttribute()
    {
        return $this->satuan ? $this->satuan->nama_satuan : 'Belum Ada Satuan';
    }

    /**
     * Get nama_gudang attribute
     */
    public function getNamaGudangAttribute()
    {
        return $this->gudang ? $this->gudang->nama_gudang : 'Belum Ada Gudang';
    }

    /**
     * Get kode_gudang attribute
     */
    public function getKodeGudangAttribute()
    {
        return $this->gudang ? $this->gudang->kode_gudang : '-';
    }

    /**
     * Get lokasi_gudang attribute
     */
    public function getLokasiGudangAttribute()
    {
        return $this->gudang ? $this->gudang->lokasi : '-';
    }

    // Scope untuk barang dengan stok rendah
    public function scopeLowStock($query)
    {
        return $query->where('stok', '<=', \DB::raw('stok_minimal'));
    }
}