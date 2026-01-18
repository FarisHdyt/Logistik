<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;

    protected $table = 'gudangs';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'kode_gudang',
        'nama_gudang',
        'satker_id',
        'lokasi',
        'penanggung_jawab',
        'telepon',
        'keterangan',
    ];

    /**
     * Relasi ke Satker
     */
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id');
    }

    /**
     * Relasi ke Barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'gudang_id');
    }
}