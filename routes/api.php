<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes/api.php
Route::get('/api/barang/search', function (Request $request) {
    $query = $request->get('q');
    
    $barang = \App\Models\Barang::with(['kategori', 'satuan', 'gudang'])
        ->where('stok', '>', 0) // Hanya barang dengan stok > 0
        ->where(function($q) use ($query) {
            $q->where('nama_barang', 'LIKE', "%{$query}%")
              ->orWhere('kode_barang', 'LIKE', "%{$query}%");
        })
        ->orderBy('nama_barang')
        ->limit(10)
        ->get();
    
    return response()->json($barang);
})->middleware('auth');