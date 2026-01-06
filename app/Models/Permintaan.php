<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_permintaan',
        'user_id',
        'barang_id', // Masih ada untuk kompatibilitas
        'satker_id',
        'jumlah', // Total jumlah semua barang
        'total_items', // Jumlah jenis barang
        'total_harga', // Total harga semua barang
        'keterangan',
        'tanggal_dibutuhkan',
        'status',
        'approved_by',
        'approved_at',
        'delivered_at',
        'delivered_by',
        'alasan_penolakan'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'delivered_at' => 'datetime',
        'tanggal_dibutuhkan' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Barang utama (untuk kompatibilitas dengan sistem lama)
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deliverer()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function pengeluaran()
    {
        return $this->hasOne(Pengeluaran::class);
    }

    // Relationship dengan PermintaanDetail (multi barang)
    public function details()
    {
        return $this->hasMany(PermintaanDetail::class);
    }

    // Alias untuk approvedBy (untuk view compatibility)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_DELIVERED = 'delivered';

    // Scope untuk status
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    // Helper methods untuk multi barang
    public function getJumlahBarangAttribute()
    {
        if ($this->details->count() > 0) {
            return $this->details->sum('jumlah');
        }
        return $this->jumlah;
    }

    public function getTotalJenisBarangAttribute()
    {
        if ($this->details->count() > 0) {
            return $this->details->count();
        }
        return 1; // Untuk kompatibilitas dengan data lama
    }

    public function getTotalHargaAttribute()
    {
        if ($this->details->count() > 0) {
            return $this->details->sum('subtotal');
        }
        return $this->total_harga ?? 0;
    }

    // Method untuk mendapatkan semua barang dalam permintaan
    public function getBarangListAttribute()
    {
        if ($this->details->count() > 0) {
            return $this->details->map(function ($detail) {
                return [
                    'id' => $detail->barang_id,
                    'nama' => $detail->barang->nama_barang ?? 'N/A',
                    'kode' => $detail->barang->kode_barang ?? '-',
                    'jumlah' => $detail->jumlah,
                    'satuan' => $detail->barang->satuan->nama_satuan ?? 'unit',
                    'harga_satuan' => $detail->harga_satuan,
                    'subtotal' => $detail->subtotal
                ];
            });
        }
        
        // Fallback untuk data lama (single barang)
        return collect([[
            'id' => $this->barang_id,
            'nama' => $this->barang->nama_barang ?? 'N/A',
            'kode' => $this->barang->kode_barang ?? '-',
            'jumlah' => $this->jumlah,
            'satuan' => $this->barang->satuan->nama_satuan ?? 'unit',
            'harga_satuan' => $this->barang->harga ?? 0,
            'subtotal' => ($this->barang->harga ?? 0) * $this->jumlah
        ]]);
    }

    // Status badge color
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'approved' => 'badge bg-success',
            'rejected' => 'badge bg-danger',
            'delivered' => 'badge bg-info'
        ];
        
        return $badges[$this->status] ?? 'badge bg-secondary';
    }

    // Status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'delivered' => 'Dikirim'
        ];
        
        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    // Cek apakah bisa diedit (hanya pending)
    public function getCanEditAttribute()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Cek apakah bisa dihapus (hanya pending)
    public function getCanDeleteAttribute()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Cek apakah sudah disetujui
    public function getIsApprovedAttribute()
    {
        return $this->status === self::STATUS_APPROVED || $this->status === self::STATUS_DELIVERED;
    }

    // Cek apakah sudah dikirim
    public function getIsDeliveredAttribute()
    {
        return $this->status === self::STATUS_DELIVERED;
    }
}