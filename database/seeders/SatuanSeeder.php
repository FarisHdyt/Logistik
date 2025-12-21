<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Satuan;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $satuans = [
            ['kode_satuan' => 'SAT-001', 'nama_satuan' => 'Unit', 'simbol' => 'u'],
            ['kode_satuan' => 'SAT-002', 'nama_satuan' => 'Pcs', 'simbol' => 'pcs'],
            ['kode_satuan' => 'SAT-003', 'nama_satuan' => 'Pack', 'simbol' => 'pk'],
            ['kode_satuan' => 'SAT-004', 'nama_satuan' => 'Lusin', 'simbol' => 'lzn'],
            ['kode_satuan' => 'SAT-005', 'nama_satuan' => 'Rim', 'simbol' => 'rm'],
            ['kode_satuan' => 'SAT-006', 'nama_satuan' => 'Kg', 'simbol' => 'kg'],
            ['kode_satuan' => 'SAT-007', 'nama_satuan' => 'Liter', 'simbol' => 'L'],
            ['kode_satuan' => 'SAT-008', 'nama_satuan' => 'Meter', 'simbol' => 'm'],
            ['kode_satuan' => 'SAT-009', 'nama_satuan' => 'Buah', 'simbol' => 'bh'],
            ['kode_satuan' => 'SAT-010', 'nama_satuan' => 'Set', 'simbol' => 'st'],
            ['kode_satuan' => 'SAT-011', 'nama_satuan' => 'Botol', 'simbol' => 'btl'],
            ['kode_satuan' => 'SAT-012', 'nama_satuan' => 'Kaleng', 'simbol' => 'klg'],
            ['kode_satuan' => 'SAT-013', 'nama_satuan' => 'Dus', 'simbol' => 'ds'],
            ['kode_satuan' => 'SAT-014', 'nama_satuan' => 'Kardus', 'simbol' => 'kds'],
            ['kode_satuan' => 'SAT-015', 'nama_satuan' => 'Roll', 'simbol' => 'rl'],
            ['kode_satuan' => 'SAT-016', 'nama_satuan' => 'Lembar', 'simbol' => 'lbr'],
            ['kode_satuan' => 'SAT-017', 'nama_satuan' => 'Batang', 'simbol' => 'btg'],
            ['kode_satuan' => 'SAT-018', 'nama_satuan' => 'Buku', 'simbol' => 'bk'],
            ['kode_satuan' => 'SAT-019', 'nama_satuan' => 'Bungkus', 'simbol' => 'bks'],
            ['kode_satuan' => 'SAT-020', 'nama_satuan' => 'Pasang', 'simbol' => 'psg'],
        ];

        foreach ($satuans as $satuan) {
            Satuan::create($satuan);
        }

        $this->command->info('Data satuan berhasil ditambahkan!');
    }
}