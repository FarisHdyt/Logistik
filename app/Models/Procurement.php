<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procurements';
    
    protected $fillable = [
        'tipe_pengadaan',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan_id',
        'jumlah',
        'harga_perkiraan',
        'prioritas',
        'alasan_pengadaan',
        'catatan',
        'status',
        'user_id', // DITAMBAHKAN
        'disetujui_oleh',
        'tanggal_disetujui',
        'diproses_oleh',
        'tanggal_diproses',
        'selesai_oleh',
        'tanggal_selesai',
        'dibatalkan_oleh',
        'alasan_pembatalan',
        'tanggal_dibatalkan',
        'alasan_penolakan', // DITAMBAHKAN
        'tanggal_ditolak', // DITAMBAHKAN
    ];

    protected $casts = [
        'harga_perkiraan' => 'decimal:2',
        'jumlah' => 'integer',
        'tanggal_disetujui' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_dibatalkan' => 'datetime',
        'tanggal_ditolak' => 'datetime', // DITAMBAHKAN
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    // Prioritas constants
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'tinggi';
    const PRIORITY_URGENT = 'mendesak';

    // Tipe pengadaan constants
    const TYPE_NEW = 'baru';
    const TYPE_RESTOCK = 'restock';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function selesaiOleh()
    {
        return $this->belongsTo(User::class, 'selesai_oleh');
    }

    public function dibatalkanOleh()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeNewItems($query)
    {
        return $query->where('tipe_pengadaan', self::TYPE_NEW);
    }

    public function scopeRestocks($query)
    {
        return $query->where('tipe_pengadaan', self::TYPE_RESTOCK);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isNewItem()
    {
        return $this->tipe_pengadaan === self::TYPE_NEW;
    }

    public function isRestock()
    {
        return $this->tipe_pengadaan === self::TYPE_RESTOCK;
    }

    // Total perkiraan biaya
    public function getTotalPerkiraanAttribute()
    {
        return $this->jumlah * $this->harga_perkiraan;
    }

    // Format status untuk display
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_PROCESSING => 'Sedang Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Format prioritas untuk display
    public function getPrioritasDisplayAttribute()
    {
        $priorities = [
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_URGENT => 'Mendesak',
        ];

        return $priorities[$this->prioritas] ?? $this->prioritas;
    }

    // Format tipe pengadaan untuk display
    public function getTipePengadaanDisplayAttribute()
    {
        $types = [
            self::TYPE_NEW => 'Barang Baru',
            self::TYPE_RESTOCK => 'Restock',
        ];

        return $types[$this->tipe_pengadaan] ?? $this->tipe_pengadaan;
    }

    // Menghitung status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REJECTED => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    // Menghitung prioritas badge
    public function getPrioritasBadgeAttribute()
    {
        $badges = [
            self::PRIORITY_NORMAL => 'info',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
        ];

        return $badges[$this->prioritas] ?? 'secondary';
    }

    // Format harga perkiraan
    public function getHargaPerkiraanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_perkiraan, 0, ',', '.');
    }

    // Format total perkiraan
    public function getTotalPerkiraanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_perkiraan, 0, ',', '.');
    }

    // Cek apakah bisa di-approve
    public function canBeApproved()
    {
        return $this->isPending();
    }

    // Cek apakah bisa diproses
    public function canBeProcessed()
    {
        return $this->isApproved() || $this->isProcessing();
    }

    // Cek apakah bisa diselesaikan
    public function canBeCompleted()
    {
        return $this->isApproved() || $this->isProcessing();
    }

    // Cek apakah bisa dibatalkan
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_PROCESSING
        ]);
    }

    // Cek apakah bisa ditolak
    public function canBeRejected()
    {
        return $this->isPending();
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Auto update tanggal berdasarkan status
        static::updating(function ($procurement) {
            $originalStatus = $procurement->getOriginal('status');
            $newStatus = $procurement->status;

            if ($originalStatus !== $newStatus) {
                $now = now();
                switch ($newStatus) {
                    case self::STATUS_APPROVED:
                        $procurement->tanggal_disetujui = $now;
                        break;
                    case self::STATUS_PROCESSING:
                        $procurement->tanggal_diproses = $now;
                        break;
                    case self::STATUS_COMPLETED:
                        $procurement->tanggal_selesai = $now;
                        break;
                    case self::STATUS_CANCELLED:
                        $procurement->tanggal_dibatalkan = $now;
                        break;
                    case self::STATUS_REJECTED:
                        $procurement->tanggal_ditolak = $now;
                        break;
                }
            }
        });
    }
}