<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Satker;

class SatkerSeeder extends Seeder
{
    public function run()
    {
        Satker::create([
            'kode_satker' => 'POL001',
            'nama_satker' => 'POLRES JAKARTA SELATAN',
            'alamat' => 'Jl. Wijaya I No.1, Jakarta Selatan',
            'telepon' => '(021) 7201234',
            'email' => 'polres_jaksel@polri.go.id',
            'nama_kepala' => 'AKBP Budi Santoso',
            'pangkat_kepala' => 'AKBP',
            'nrp_kepala' => '12345678',
        ]);
    }
}