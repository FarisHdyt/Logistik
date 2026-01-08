<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model
     *
     * @var string
     */
    protected $table = 'satuans';

    /**
     * Kolom yang dapat diisi secara massal
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_satuan',
        'nama_satuan',
        'simbol',
    ];

    /**
     * Kolom yang harus disembunyikan saat serialisasi
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi ke model Barang
     * Satu satuan bisa digunakan oleh banyak barang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'satuan_id');
    }

    public function procurements()
    {
        return $this->hasMany(Procurement::class, 'satuan_id');
    }

    /**
     * Scope untuk mencari satuan berdasarkan kode
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $kode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_satuan', $kode);
    }

    /**
     * Scope untuk mencari satuan berdasarkan nama
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $nama
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_satuan', 'like', "%{$nama}%");
    }

    /**
     * Accessor untuk menampilkan satuan lengkap
     *
     * @return string
     */
    public function getSatuanLengkapAttribute()
    {
        if ($this->simbol) {
            return "{$this->nama_satuan} ({$this->simbol})";
        }
        return $this->nama_satuan;
    }

    /**
     * Accessor untuk kode dan nama satuan
     *
     * @return string
     */
    public function getKodeNamaAttribute()
    {
        return "{$this->kode_satuan} - {$this->nama_satuan}";
    }

    /**
     * Mutator untuk memastikan kode_satuan selalu uppercase
     *
     * @param string $value
     * @return void
     */
    public function setKodeSatuanAttribute($value)
    {
        $this->attributes['kode_satuan'] = strtoupper($value);
    }

    /**
     * Mutator untuk memastikan simbol selalu uppercase
     *
     * @param string|null $value
     * @return void
     */
    public function setSimbolAttribute($value)
    {
        if ($value) {
            $this->attributes['simbol'] = strtoupper($value);
        } else {
            $this->attributes['simbol'] = null;
        }
    }

    /**
     * Cek apakah satuan sedang digunakan oleh barang
     *
     * @return bool
     */
    public function isUsed()
    {
        return $this->barangs()->count() > 0;
    }

    /**
     * Mendapatkan jumlah barang yang menggunakan satuan ini
     *
     * @return int
     */
    public function getJumlahBarangAttribute()
    {
        return $this->barangs()->count();
    }

    /**
     * Validasi rules untuk satuan
     *
     * @return array
     */
    public static function getValidationRules($id = null)
    {
        $rules = [
            'kode_satuan' => 'required|string|max:20|unique:satuans,kode_satuan',
            'nama_satuan' => 'required|string|max:100',
            'simbol' => 'nullable|string|max:10',
        ];

        if ($id) {
            $rules['kode_satuan'] .= ',' . $id;
        }

        return $rules;
    }

    /**
     * Pesan validasi untuk satuan
     *
     * @return array
     */
    public static function getValidationMessages()
    {
        return [
            'kode_satuan.required' => 'Kode satuan wajib diisi',
            'kode_satuan.unique' => 'Kode satuan sudah digunakan',
            'kode_satuan.max' => 'Kode satuan maksimal 20 karakter',
            'nama_satuan.required' => 'Nama satuan wajib diisi',
            'nama_satuan.max' => 'Nama satuan maksimal 100 karakter',
            'simbol.max' => 'Simbol maksimal 10 karakter',
        ];
    }

    /**
     * Mendapatkan daftar satuan untuk dropdown
     *
     * @return array
     */
    public static function getDropdown()
    {
        return self::orderBy('nama_satuan')
            ->get()
            ->mapWithKeys(function ($satuan) {
                return [$satuan->id => $satuan->nama_satuan . ($satuan->simbol ? " ({$satuan->simbol})" : '')];
            })
            ->toArray();
    }

    /**
     * Mendapatkan daftar satuan dengan kode untuk dropdown
     *
     * @return array
     */
    public static function getDropdownWithCode()
    {
        return self::orderBy('kode_satuan')
            ->get()
            ->mapWithKeys(function ($satuan) {
                return [$satuan->id => "{$satuan->kode_satuan} - {$satuan->nama_satuan}" . ($satuan->simbol ? " ({$satuan->simbol})" : '')];
            })
            ->toArray();
    }
}