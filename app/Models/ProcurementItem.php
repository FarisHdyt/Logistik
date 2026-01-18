<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    use HasFactory;

    protected $table = 'procurement_items';
    
    protected $fillable = [
        'procurement_id',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'kategori',
        'kategori_id', // DITAMBAHKAN dari controller
        'satuan',
        'satuan_id', // DITAMBAHKAN dari controller
        'gudang',
        'gudang_id', // DITAMBAHKAN dari controller
        'jumlah',
        'harga_perkiraan',
        'subtotal',
        'tipe_pengadaan',
        'deskripsi',
        'stok_minimal',
        'keterangan',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'alasan_penolakan',
    ];

    protected $casts = [
        'harga_perkiraan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'jumlah' => 'integer',
        'stok_minimal' => 'integer',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Relationship ke procurement
    public function procurement()
    {
        return $this->belongsTo(Procurement::class, 'procurement_id');
    }

    // Relationship ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Relationship ke kategori
    public function kategoriRel()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relationship ke satuan
    public function satuanRel()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    // Relationship ke gudang
    public function gudangRel()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        if (isset($this->attributes['subtotal']) && $this->attributes['subtotal'] !== null) {
            return $this->attributes['subtotal'];
        }
        
        // Hitung otomatis jika tidak ada
        return $this->jumlah * $this->harga_perkiraan;
    }

    // Mutator untuk subtotal
    public function setSubtotalAttribute($value)
    {
        if ($value === null) {
            // Hitung otomatis jika null
            $this->attributes['subtotal'] = $this->jumlah * $this->harga_perkiraan;
        } else {
            $this->attributes['subtotal'] = $value;
        }
    }

    // Accessor untuk harga perkiraan formatted
    public function getHargaPerkiraanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_perkiraan, 0, ',', '.');
    }

    // Accessor untuk subtotal formatted
    public function getSubtotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Accessor untuk jumlah display dengan satuan
    public function getJumlahDisplayAttribute()
    {
        return $this->jumlah . ' ' . ($this->satuan ?? 'unit');
    }

    // Accessor untuk tipe pengadaan display
    public function getTipePengadaanDisplayAttribute()
    {
        $tipes = [
            'restock' => 'Restock',
            'baru' => 'Baru'
        ];
        
        return $tipes[$this->tipe_pengadaan] ?? ucfirst($this->tipe_pengadaan);
    }

    // Accessor untuk status display
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai'
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    // Scope untuk item yang pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk item yang approved
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope untuk item yang rejected
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Scope berdasarkan tipe pengadaan
    public function scopeTipePengadaan($query, $tipe)
    {
        return $query->where('tipe_pengadaan', $tipe);
    }

    // Scope untuk barang baru
    public function scopeBarangBaru($query)
    {
        return $query->where('tipe_pengadaan', 'baru');
    }

    // Scope untuk restock
    public function scopeRestock($query)
    {
        return $query->where('tipe_pengadaan', 'restock');
    }

    // Helper untuk mengecek apakah item adalah barang baru
    public function isBarangBaru()
    {
        return $this->tipe_pengadaan === 'baru';
    }

    // Helper untuk mengecek apakah item adalah restock
    public function isRestock()
    {
        return $this->tipe_pengadaan === 'restock';
    }

    // Helper untuk mengecek status
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Event untuk menghitung subtotal otomatis sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Hitung subtotal otomatis jika jumlah atau harga berubah
            if ($item->isDirty(['jumlah', 'harga_perkiraan']) && $item->subtotal === null) {
                $item->subtotal = $item->jumlah * $item->harga_perkiraan;
            }
        });
    }
}