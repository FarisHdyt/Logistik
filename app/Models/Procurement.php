<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Procurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procurements';
    
    protected $fillable = [
        'kode_pengadaan',
        'tipe_pengadaan',
        'is_multi_item',
        'prioritas',
        'alasan_pengadaan',
        'catatan',
        'status',
        'user_id',
        'disetujui_oleh',
        'tanggal_disetujui',
        'diproses_oleh',
        'tanggal_diproses',
        'selesai_oleh',
        'tanggal_selesai',
        'dibatalkan_oleh',
        'alasan_pembatalan',
        'tanggal_dibatalkan',
        'alasan_penolakan',
        'tanggal_ditolak',
        // Field untuk backward compatibility (single item)
        'barang_id',
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'satuan_id',
        'jumlah',
        'harga_perkiraan',
        'stok_minimal',
    ];

    protected $casts = [
        'is_multi_item' => 'boolean',
        'tanggal_disetujui' => 'datetime',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_dibatalkan' => 'datetime',
        'tanggal_ditolak' => 'datetime',
        'jumlah' => 'integer',
        'harga_perkiraan' => 'decimal:2',
        'stok_minimal' => 'integer',
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
    
    // Kode prefix
    const KODE_PREFIX = 'PGD';

    // ==================== RELATIONSHIPS ====================
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    // Relationship untuk items (multi item)
    public function items()
    {
        return $this->hasMany(ProcurementItem::class, 'procurement_id');
    }

    // ==================== RELASI UNTUK BACKWARD COMPATIBILITY ====================
    
    /**
     * Relasi ke barang (untuk single item procurement lama)
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi ke kategori (untuk single item procurement lama)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke satuan (untuk single item procurement lama)
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Untuk backward compatibility: Ambil nama barang
     */
    public function getNamaBarangAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->isMultiItem()) {
            $firstItem = $this->items->first();
            return $firstItem ? $firstItem->nama_barang : 'Multi Barang';
        }
        
        return $this->barang ? $this->barang->nama_barang : null;
    }

    /**
     * Untuk backward compatibility: Ambil kode barang
     */
    public function getKodeBarangAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->isMultiItem()) {
            $firstItem = $this->items->first();
            return $firstItem ? $firstItem->kode_barang : 'MULTI';
        }
        
        return $this->barang ? $this->barang->kode_barang : null;
    }

    /**
     * Untuk backward compatibility: Ambil kategori
     */
    public function getKategoriAttribute()
    {
        if ($this->kategori_id) {
            return $this->kategori ? $this->kategori->nama_kategori : null;
        }
        
        if ($this->isMultiItem() && $this->items->isNotEmpty()) {
            $firstItem = $this->items->first();
            return $firstItem ? $firstItem->kategori : null;
        }
        
        return $this->barang && $this->barang->kategori ? 
               $this->barang->kategori->nama_kategori : null;
    }

    /**
     * Untuk backward compatibility: Ambil satuan
     */
    public function getSatuanAttribute()
    {
        if ($this->satuan_id) {
            return $this->satuan ? $this->satuan->nama_satuan : null;
        }
        
        if ($this->isMultiItem() && $this->items->isNotEmpty()) {
            $firstItem = $this->items->first();
            return $firstItem ? $firstItem->satuan : null;
        }
        
        return $this->barang && $this->barang->satuan ? 
               $this->barang->satuan->nama_satuan : null;
    }

    /**
     * Untuk backward compatibility: Ambil jumlah total
     */
    public function getJumlahAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->isMultiItem()) {
            return $this->items->sum('jumlah');
        }
        
        return $value;
    }

    /**
     * Untuk backward compatibility: Ambil harga perkiraan rata-rata
     */
    public function getHargaPerkiraanAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->isMultiItem() && $this->items->isNotEmpty()) {
            $total = $this->items->sum('subtotal');
            $totalJumlah = $this->items->sum('jumlah');
            return $totalJumlah > 0 ? $total / $totalJumlah : 0;
        }
        
        return $value;
    }

    /**
     * Untuk backward compatibility: Ambil stok minimal rata-rata
     */
    public function getStokMinimalAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->isMultiItem() && $this->items->isNotEmpty()) {
            return $this->items->average('stok_minimal');
        }
        
        return $value;
    }

    // ==================== SCOPES ====================
    
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

    // Scope untuk pencarian kode pengadaan
    public function scopeByKodePengadaan($query, $kode)
    {
        return $query->where('kode_pengadaan', $kode);
    }

    // Scope untuk multi item
    public function scopeMultiItem($query)
    {
        return $query->where('is_multi_item', true);
    }

    // Scope untuk single item
    public function scopeSingleItem($query)
    {
        return $query->where('is_multi_item', false);
    }

    // ==================== HELPER METHODS ====================
    
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

    /**
     * Cek apakah ini multi item
     * PERBAIKAN: Gunakan items count jika is_multi_item tidak akurat
     */
    public function isMultiItem()
    {
        if ($this->items && $this->items->count() > 1) {
            return true;
        }
        
        return $this->is_multi_item;
    }

    /**
     * Total perkiraan biaya - PERBAIKAN: Ambil dari items atau field single
     */
    public function getTotalPerkiraanAttribute()
    {
        if ($this->items && $this->items->count() > 0) {
            return $this->items->sum(function ($item) {
                return ($item->jumlah ?? 0) * ($item->harga_perkiraan ?? 0);
            });
        }
        
        // Untuk backward compatibility dengan single item procurement lama
        if ($this->jumlah && $this->harga_perkiraan) {
            return $this->jumlah * $this->harga_perkiraan;
        }
        
        return 0;
    }

    /**
     * Total jumlah barang - PERBAIKAN: Ambil dari items atau field single
     */
    public function getTotalJumlahAttribute()
    {
        if ($this->items && $this->items->count() > 0) {
            return $this->items->sum('jumlah');
        }
        
        // Untuk backward compatibility dengan single item procurement lama
        return $this->jumlah ?? 0;
    }

    /**
     * Format status untuk display
     */
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REJECTED => 'Ditolak',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Format prioritas untuk display
     */
    public function getPrioritasDisplayAttribute()
    {
        $priorities = [
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_URGENT => 'Mendesak',
        ];

        return $priorities[$this->prioritas] ?? $this->prioritas;
    }

    /**
     * Format tipe pengadaan untuk display
     */
    public function getTipePengadaanDisplayAttribute()
    {
        $types = [
            self::TYPE_NEW => 'Baru',
            self::TYPE_RESTOCK => 'Restock',
        ];

        return $types[$this->tipe_pengadaan] ?? $this->tipe_pengadaan;
    }

    /**
     * Menghitung status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_COMPLETED => 'info',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_REJECTED => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Menghitung prioritas badge
     */
    public function getPrioritasBadgeAttribute()
    {
        $badges = [
            self::PRIORITY_NORMAL => 'success',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
        ];

        return $badges[$this->prioritas] ?? 'secondary';
    }

    /**
     * Format total perkiraan
     */
    public function getTotalPerkiraanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_perkiraan, 0, ',', '.');
    }

    /**
     * Format harga perkiraan per unit
     */
    public function getHargaPerkiraanFormattedAttribute()
    {
        $harga = $this->harga_perkiraan ?? 0;
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }

    /**
     * Cek apakah bisa di-approve
     */
    public function canBeApproved()
    {
        return $this->isPending();
    }

    /**
     * Cek apakah bisa diproses
     */
    public function canBeProcessed()
    {
        return $this->isApproved() || $this->isProcessing();
    }

    /**
     * Cek apakah bisa diselesaikan
     */
    public function canBeCompleted()
    {
        return $this->isApproved() || $this->isProcessing();
    }

    /**
     * Cek apakah bisa dibatalkan
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_PROCESSING
        ]);
    }

    /**
     * Cek apakah bisa ditolak
     */
    public function canBeRejected()
    {
        return $this->isPending();
    }

    /**
     * Method untuk generate kode pengadaan yang benar-benar UNIK
     */
    public static function generateUniqueKodePengadaan()
    {
        $prefix = self::KODE_PREFIX;
        $tahunBulan = date('Ym');
        
        // Gunakan microtime(true) untuk mendapatkan detik dan mikrodetik
        $microtime = microtime(true);
        $microtimeStr = str_replace('.', '', (string)$microtime);
        
        // Ambil 8-10 digit terakhir dari microtime
        $uniquePart = substr($microtimeStr, -10);
        
        // Tambahkan random 4 digit untuk extra safety
        $randomPart = mt_rand(1000, 9999);
        
        // Format: PGD-YYYYMM-MICROTIME-RANDOM
        $kode = sprintf('%s-%s-%s-%s', $prefix, $tahunBulan, $uniquePart, $randomPart);
        
        // Pastikan panjang tidak melebihi batas database (biasanya 255)
        if (strlen($kode) > 50) {
            $kode = substr($kode, 0, 50);
        }
        
        return $kode;
    }

    /**
     * Method untuk sequential code dengan uniqueness guarantee
     */
    public static function generateSequentialKode()
    {
        $prefix = self::KODE_PREFIX;
        $tahunBulan = date('Ym');
        
        // Dapatkan counter untuk bulan ini
        $counter = self::where('kode_pengadaan', 'like', "{$prefix}-{$tahunBulan}-%")
            ->withTrashed()
            ->count() + 1;
        
        // Format: PGD-YYYYMM-XXXXXX
        return sprintf('%s-%s-%06d', $prefix, $tahunBulan, $counter);
    }

    // ==================== EVENTS ====================
    
    protected static function boot()
    {
        parent::boot();

        // Auto generate kode_pengadaan saat create
        static::creating(function ($procurement) {
            \Log::info('Procurement creating event triggered', [
                'has_kode_pengadaan' => !empty($procurement->kode_pengadaan),
                'kode_pengadaan' => $procurement->kode_pengadaan,
                'user_id' => $procurement->user_id,
                'is_multi_item' => $procurement->is_multi_item,
            ]);
            
            // Jika kode_pengadaan sudah ada, cek apakah unik
            if (!empty($procurement->kode_pengadaan)) {
                $exists = self::where('kode_pengadaan', $procurement->kode_pengadaan)
                    ->when($procurement->id, function($query, $id) {
                        $query->where('id', '!=', $id);
                    })
                    ->exists();
                
                if ($exists) {
                    \Log::warning('Duplicate kode_pengadaan detected: ' . $procurement->kode_pengadaan);
                    throw new \Exception("Kode pengadaan '{$procurement->kode_pengadaan}' sudah digunakan. Silakan coba lagi.");
                }
            } else {
                // Generate kode baru yang UNIK dengan retry logic
                $maxAttempts = 10;
                $attempts = 0;
                $generated = false;
                
                while (!$generated && $attempts < $maxAttempts) {
                    $kode = self::generateUniqueKodePengadaan();
                    
                    // Cek apakah kode sudah ada
                    $exists = self::where('kode_pengadaan', $kode)->exists();
                    
                    if (!$exists) {
                        $procurement->kode_pengadaan = $kode;
                        $generated = true;
                        \Log::info('Generated new kode_pengadaan: ' . $kode, ['attempts' => $attempts + 1]);
                    } else {
                        $attempts++;
                        \Log::warning('Duplicate kode detected, retrying...', [
                            'kode' => $kode,
                            'attempt' => $attempts
                        ]);
                        
                        // Tunggu sebentar sebelum mencoba lagi (mencegah race condition)
                        if ($attempts < $maxAttempts) {
                            usleep(100000 * $attempts); // 0.1s * attempts
                        }
                    }
                }
                
                // Jika masih gagal setelah semua percobaan
                if (!$generated) {
                    \Log::error('Failed to generate unique kode_pengadaan after ' . $maxAttempts . ' attempts');
                    throw new \Exception('Gagal menghasilkan kode pengadaan yang unik. Silakan coba lagi.');
                }
            }
            
            // Set is_multi_item berdasarkan data yang masuk
            if ($procurement->is_multi_item === null) {
                // Coba deteksi dari request jika ada
                if (request()->has('items') && is_array(request()->input('items'))) {
                    $itemsCount = count(request()->input('items'));
                    $procurement->is_multi_item = $itemsCount > 1;
                } else {
                    $procurement->is_multi_item = false;
                }
            }
            
            \Log::info('Procurement creating completed', [
                'kode_pengadaan' => $procurement->kode_pengadaan,
                'is_multi_item' => $procurement->is_multi_item,
            ]);
        });

        // Validasi UNIQUE saat saving
        static::saving(function ($procurement) {
            // Jika kode_pengadaan ada, validasi keunikan
            if (!empty($procurement->kode_pengadaan)) {
                $exists = self::where('kode_pengadaan', $procurement->kode_pengadaan)
                    ->when($procurement->id, function($query, $id) {
                        $query->where('id', '!=', $id);
                    })
                    ->exists();
                
                if ($exists) {
                    \Log::error('Duplicate kode_pengadaan validation failed: ' . $procurement->kode_pengadaan);
                    throw new \Exception("Kode pengadaan '{$procurement->kode_pengadaan}' sudah digunakan.");
                }
            }
        });

        // Auto update tanggal berdasarkan status
        static::updating(function ($procurement) {
            $originalStatus = $procurement->getOriginal('status');
            $newStatus = $procurement->status;

            // Log perubahan status
            if ($originalStatus !== $newStatus) {
                \Log::info('Procurement status changed', [
                    'id' => $procurement->id,
                    'kode_pengadaan' => $procurement->kode_pengadaan,
                    'from' => $originalStatus,
                    'to' => $newStatus,
                ]);
            }

            if ($originalStatus !== $newStatus) {
                $now = now();
                $userId = auth()->check() ? auth()->id() : null;
                
                switch ($newStatus) {
                    case self::STATUS_APPROVED:
                        $procurement->tanggal_disetujui = $now;
                        $procurement->disetujui_oleh = $userId;
                        break;
                    case self::STATUS_PROCESSING:
                        $procurement->tanggal_diproses = $now;
                        $procurement->diproses_oleh = $userId;
                        break;
                    case self::STATUS_COMPLETED:
                        $procurement->tanggal_selesai = $now;
                        $procurement->selesai_oleh = $userId;
                        break;
                    case self::STATUS_CANCELLED:
                        $procurement->tanggal_dibatalkan = $now;
                        $procurement->dibatalkan_oleh = $userId;
                        break;
                    case self::STATUS_REJECTED:
                        $procurement->tanggal_ditolak = $now;
                        $procurement->disetujui_oleh = $userId;
                        break;
                }
            }
        });

        // Auto delete items saat procurement dihapus
        static::deleting(function ($procurement) {
            if ($procurement->isForceDeleting()) {
                $procurement->items()->forceDelete();
            } else {
                $procurement->items()->delete();
            }
        });
    }

    // ==================== CUSTOM METHODS ====================
    
    /**
     * Method untuk menambahkan item
     */
    public function addItem($data)
    {
        return $this->items()->create($data);
    }

    /**
     * Method untuk menambahkan multiple items
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        return $this;
    }
    
    /**
     * Method untuk mendapatkan timeline events
     */
    public function getTimelineEvents()
    {
        $events = [];
        
        // Event: Diajukan
        $events[] = [
            'type' => 'created',
            'title' => 'Diajukan',
            'description' => 'Pengajuan pengadaan dibuat',
            'user' => $this->user?->name ?? 'System',
            'timestamp' => $this->created_at,
            'icon' => 'bi-clock',
            'color' => 'secondary'
        ];
        
        // Event: Disetujui
        if ($this->tanggal_disetujui) {
            $events[] = [
                'type' => 'approved',
                'title' => 'Disetujui',
                'description' => 'Pengadaan disetujui',
                'user' => $this->disetujuiOleh?->name ?? 'Admin',
                'timestamp' => $this->tanggal_disetujui,
                'icon' => 'bi-check-circle',
                'color' => 'success'
            ];
        }
        
        // Event: Diproses
        if ($this->tanggal_diproses) {
            $events[] = [
                'type' => 'processing',
                'title' => 'Diproses',
                'description' => 'Pengadaan sedang diproses',
                'user' => $this->diprosesOleh?->name ?? 'Admin',
                'timestamp' => $this->tanggal_diproses,
                'icon' => 'bi-gear',
                'color' => 'primary'
            ];
        }
        
        // Event: Selesai
        if ($this->tanggal_selesai) {
            $events[] = [
                'type' => 'completed',
                'title' => 'Selesai',
                'description' => 'Pengadaan telah selesai',
                'user' => $this->selesaiOleh?->name ?? 'Admin',
                'timestamp' => $this->tanggal_selesai,
                'icon' => 'bi-check-all',
                'color' => 'info'
            ];
        }
        
        // Event: Dibatalkan
        if ($this->tanggal_dibatalkan) {
            $events[] = [
                'type' => 'cancelled',
                'title' => 'Dibatalkan',
                'description' => $this->alasan_pembatalan ? 'Alasan: ' . $this->alasan_pembatalan : 'Pengadaan dibatalkan',
                'user' => $this->dibatalkanOleh?->name ?? 'Admin',
                'timestamp' => $this->tanggal_dibatalkan,
                'icon' => 'bi-x-circle',
                'color' => 'danger'
            ];
        }
        
        // Event: Ditolak
        if ($this->tanggal_ditolak) {
            $events[] = [
                'type' => 'rejected',
                'title' => 'Ditolak',
                'description' => $this->alasan_penolakan ? 'Alasan: ' . $this->alasan_penolakan : 'Pengadaan ditolak',
                'user' => $this->disetujuiOleh?->name ?? 'Admin',
                'timestamp' => $this->tanggal_ditolak,
                'icon' => 'bi-slash-circle',
                'color' => 'danger'
            ];
        }
        
        // Urutkan berdasarkan waktu
        usort($events, function($a, $b) {
            return $a['timestamp'] <=> $b['timestamp'];
        });
        
        return $events;
    }
    
    /**
     * Method untuk mendapatkan summary
     */
    public function getSummary()
    {
        return [
            'total_items' => $this->items->count() ?: 1,
            'total_quantity' => $this->total_jumlah,
            'total_value' => $this->total_perkiraan,
            'total_value_formatted' => $this->total_perkiraan_formatted,
            'status' => $this->status_display,
            'priority' => $this->prioritas_display,
            'type' => $this->tipe_pengadaan_display,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'created_by' => $this->user?->name,
        ];
    }
    
    /**
     * Method untuk memperbaiki semua kode yang duplikat
     */
    public static function fixDuplicateCodes()
    {
        try {
            DB::beginTransaction();
            
            \Log::info('Starting duplicate procurement code fix...');
            
            // Temukan semua kode yang duplikat
            $duplicateCodes = self::select('kode_pengadaan', DB::raw('COUNT(*) as count'))
                ->groupBy('kode_pengadaan')
                ->having('count', '>', 1)
                ->get();
            
            $fixedCount = 0;
            
            foreach ($duplicateCodes as $duplicate) {
                \Log::warning('Found duplicate code: ' . $duplicate->kode_pengadaan . ' (' . $duplicate->count . ' records)');
                
                // Ambil semua procurement dengan kode ini
                $procurements = self::where('kode_pengadaan', $duplicate->kode_pengadaan)
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->get();
                
                // Pertahankan yang pertama, ubah yang lainnya
                $keepFirst = true;
                foreach ($procurements as $index => $procurement) {
                    if ($keepFirst) {
                        \Log::info('Keeping original code for ID ' . $procurement->id);
                        $keepFirst = false;
                        continue;
                    }
                    
                    // Generate kode baru
                    $newCode = self::generateUniqueKodePengadaan();
                    
                    // Pastikan kode baru tidak bentrok
                    $attempts = 0;
                    while (self::where('kode_pengadaan', $newCode)->exists() && $attempts < 10) {
                        $newCode = self::generateUniqueKodePengadaan();
                        $attempts++;
                    }
                    
                    // Update kode
                    $oldCode = $procurement->kode_pengadaan;
                    $procurement->kode_pengadaan = $newCode;
                    $procurement->save();
                    
                    \Log::info('Fixed duplicate code', [
                        'id' => $procurement->id,
                        'old_code' => $oldCode,
                        'new_code' => $newCode,
                    ]);
                    
                    $fixedCount++;
                }
            }
            
            DB::commit();
            
            \Log::info("Fixed {$fixedCount} duplicate procurement codes");
            return [
                'success' => true,
                'message' => "Berhasil memperbaiki {$fixedCount} kode duplikat",
                'fixed_count' => $fixedCount,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error fixing duplicate codes: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Gagal memperbaiki kode duplikat: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Method untuk mengecek kesehatan kode
     */
    public static function checkCodeHealth()
    {
        $total = self::count();
        
        // Hitung duplikat
        $duplicates = self::select('kode_pengadaan', DB::raw('COUNT(*) as count'))
            ->groupBy('kode_pengadaan')
            ->having('count', '>', 1)
            ->count();
        
        // Hitung null/empty
        $nullCodes = self::whereNull('kode_pengadaan')
            ->orWhere('kode_pengadaan', '')
            ->count();
        
        // Contoh valid kode
        $sampleCode = self::whereNotNull('kode_pengadaan')
            ->where('kode_pengadaan', '!=', '')
            ->orderBy('created_at', 'desc')
            ->first();
        
        return [
            'total_procurements' => $total,
            'duplicate_codes' => $duplicates,
            'null_or_empty_codes' => $nullCodes,
            'health_status' => ($duplicates == 0 && $nullCodes == 0) ? 'HEALTHY' : 'NEEDS_ATTENTION',
            'sample_code' => $sampleCode ? $sampleCode->kode_pengadaan : null,
            'recommendation' => $duplicates > 0 ? 'Run fixDuplicateCodes()' : 'All good',
        ];
    }

    /**
     * Helper untuk mendapatkan semua barang dalam procurement
     */
    public function getAllBarang()
    {
        if ($this->isMultiItem()) {
            return $this->items->map(function($item) {
                return [
                    'kode_barang' => $item->kode_barang,
                    'nama_barang' => $item->nama_barang,
                    'kategori' => $item->kategori,
                    'satuan' => $item->satuan,
                    'jumlah' => $item->jumlah,
                    'harga_perkiraan' => $item->harga_perkiraan,
                    'subtotal' => $item->subtotal,
                    'tipe_pengadaan' => $item->tipe_pengadaan,
                    'stok_minimal' => $item->stok_minimal,
                ];
            });
        }
        
        // Untuk single item
        return [[
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'kategori' => $this->kategori,
            'satuan' => $this->satuan,
            'jumlah' => $this->jumlah,
            'harga_perkiraan' => $this->harga_perkiraan,
            'subtotal' => $this->total_perkiraan,
            'tipe_pengadaan' => $this->tipe_pengadaan,
            'stok_minimal' => $this->stok_minimal,
        ]];
    }
}