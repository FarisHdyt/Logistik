<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris'; // Tambahkan ini
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
    
    // Relationship dengan barang
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    public function procurements()
    {
        return $this->hasMany(Procurement::class, 'kategori_id');
    }
}