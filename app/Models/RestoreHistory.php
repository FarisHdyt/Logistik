<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestoreHistory extends Model
{
    use HasFactory;

    protected $table = 'restore_history';
    
    protected $fillable = [
        'filename',
        'format',
        'size',
        'method',
        'total_rows',
        'inserted_rows',
        'skipped_rows',
        'status',
        'message',
        'user_id'
    ];
    
    protected $casts = [
        'total_rows' => 'integer',
        'inserted_rows' => 'integer',
        'skipped_rows' => 'integer',
        'created_at' => 'datetime'
    ];
    
    /**
     * Relasi dengan user yang melakukan restore
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Format tanggal untuk display
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
    
    /**
     * Status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'success' ? 'success' : 'danger';
    }
    
    /**
     * Status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status === 'success' ? 'Berhasil' : 'Gagal';
    }
    
    /**
     * Method text
     */
    public function getMethodTextAttribute()
    {
        return $this->method === 'replace' ? 'Replace' : 'Append';
    }
}