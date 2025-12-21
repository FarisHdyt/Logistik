<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Gudang;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = Kategori::all();
        $satuans = Satuan::all();
        $gudangs = Gudang::all();
        
        if ($kategoris->count() == 0 || $satuans->count() == 0 || $gudangs->count() == 0) {
            $this->command->error('Harap jalankan KategoriSeeder, SatuanSeeder, dan GudangSeeder terlebih dahulu!');
            return;
        }

        // Data barang dengan tanggal yang berbeda-beda selama 12 bulan terakhir
        $barangs = [
            // Barang 1: Dibuat 11 bulan lalu (Januari)
            [
                'kode_barang' => 'BRG-2024-001',
                'nama_barang' => 'Bola Lampu LED 10W',
                'kategori_id' => $kategoris->where('nama_kategori', 'Elektronik')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pcs')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 100,
                'stok_minimal' => 20,
                'lokasi' => 'Rak A1',
                'keterangan' => 'Lampu LED putih 10 watt',
                'created_at' => Carbon::now()->subMonths(11)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(11)->startOfMonth(),
            ],
            // Barang 2: Dibuat 10 bulan lalu (Februari)
            [
                'kode_barang' => 'BRG-2024-002',
                'nama_barang' => 'Kertas A4 80gr',
                'kategori_id' => $kategoris->where('nama_kategori', 'Alat Tulis Kantor')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Rim')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang ATK')->first()->id,
                'stok' => 50,
                'stok_minimal' => 10,
                'lokasi' => 'Rak B2',
                'keterangan' => 'Kertas HVS A4 80 gram',
                'created_at' => Carbon::now()->subMonths(10)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(10)->startOfMonth(),
            ],
            // Barang 3: Dibuat 9 bulan lalu (Maret)
            [
                'kode_barang' => 'BRG-2024-003',
                'nama_barang' => 'Meja Kantor',
                'kategori_id' => $kategoris->where('nama_kategori', 'Furniture')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Unit')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Furniture')->first()->id,
                'stok' => 15,
                'stok_minimal' => 5,
                'lokasi' => 'Area Furniture',
                'keterangan' => 'Meja kantor standar ukuran 120x60 cm',
                'created_at' => Carbon::now()->subMonths(9)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(9)->startOfMonth(),
            ],
            // Barang 4: Dibuat 8 bulan lalu (April)
            [
                'kode_barang' => 'BRG-2024-004',
                'nama_barang' => 'Air Mineral Galon',
                'kategori_id' => $kategoris->where('nama_kategori', 'Konsumsi')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Botol')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Konsumsi')->first()->id,
                'stok' => 30,
                'stok_minimal' => 10,
                'lokasi' => 'Rak Minuman',
                'keterangan' => 'Air mineral kemasan galon 19 liter',
                'created_at' => Carbon::now()->subMonths(8)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(8)->startOfMonth(),
            ],
            // Barang 5: Dibuat 7 bulan lalu (Mei)
            [
                'kode_barang' => 'BRG-2024-005',
                'nama_barang' => 'Laptop Dell Latitude',
                'kategori_id' => $kategoris->where('nama_kategori', 'Komputer & IT')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Unit')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 8,
                'stok_minimal' => 3,
                'lokasi' => 'Lemari Elektronik',
                'keterangan' => 'Laptop Dell Latitude i5, 8GB RAM, 256GB SSD',
                'created_at' => Carbon::now()->subMonths(7)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(7)->startOfMonth(),
            ],
            // Barang 6: Dibuat 6 bulan lalu (Juni)
            [
                'kode_barang' => 'BRG-2024-006',
                'nama_barang' => 'Sapu Lantai',
                'kategori_id' => $kategoris->where('nama_kategori', 'Perlengkapan Kebersihan')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pcs')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Utama')->first()->id,
                'stok' => 25,
                'stok_minimal' => 10,
                'lokasi' => 'Rak Kebersihan',
                'keterangan' => 'Sapu lantai plastik',
                'created_at' => Carbon::now()->subMonths(6)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(6)->startOfMonth(),
            ],
            // Barang 7: Dibuat 5 bulan lalu (Juli)
            [
                'kode_barang' => 'BRG-2024-007',
                'nama_barang' => 'Seragam Dinas Harian',
                'kategori_id' => $kategoris->where('nama_kategori', 'Pakaian Dinas')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Set')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Utama')->first()->id,
                'stok' => 40,
                'stok_minimal' => 15,
                'lokasi' => 'Lemari Seragam',
                'keterangan' => 'Seragam dinas harian ukuran L',
                'created_at' => Carbon::now()->subMonths(5)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(5)->startOfMonth(),
            ],
            // Barang 8: Dibuat 4 bulan lalu (Agustus)
            [
                'kode_barang' => 'BRG-2024-008',
                'nama_barang' => 'Walkie Talkie',
                'kategori_id' => $kategoris->where('nama_kategori', 'Perlengkapan Komunikasi')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Unit')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 12,
                'stok_minimal' => 5,
                'lokasi' => 'Rak Komunikasi',
                'keterangan' => 'Walkie talkie UHF 5 watt',
                'created_at' => Carbon::now()->subMonths(4)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(4)->startOfMonth(),
            ],
            // Barang 9: Dibuat 3 bulan lalu (September)
            [
                'kode_barang' => 'BRG-2024-009',
                'nama_barang' => 'Paku Beton',
                'kategori_id' => $kategoris->where('nama_kategori', 'Bahan Bangunan')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Kg')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Utama')->first()->id,
                'stok' => 50,
                'stok_minimal' => 20,
                'lokasi' => 'Rak Bangunan',
                'keterangan' => 'Paku beton ukuran 3 inch',
                'created_at' => Carbon::now()->subMonths(3)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(3)->startOfMonth(),
            ],
            // Barang 10: Dibuat 2 bulan lalu (Oktober)
            [
                'kode_barang' => 'BRG-2024-010',
                'nama_barang' => 'Bola Basket',
                'kategori_id' => $kategoris->where('nama_kategori', 'Alat Olahraga')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Buah')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Utama')->first()->id,
                'stok' => 10,
                'stok_minimal' => 3,
                'lokasi' => 'Rak Olahraga',
                'keterangan' => 'Bola basket ukuran 7',
                'created_at' => Carbon::now()->subMonths(2)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(2)->startOfMonth(),
            ],
            // Barang 11: Dibuat 1 bulan lalu (November)
            [
                'kode_barang' => 'BRG-2024-011',
                'nama_barang' => 'Printer Epson L3110',
                'kategori_id' => $kategoris->where('nama_kategori', 'Komputer & IT')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Unit')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 6,
                'stok_minimal' => 2,
                'lokasi' => 'Rak Printer',
                'keterangan' => 'Printer All-in-One Epson L3110',
                'created_at' => Carbon::now()->subMonths(1)->startOfMonth(),
                'updated_at' => Carbon::now()->subMonths(1)->startOfMonth(),
            ],
            // Barang 12: Dibuat bulan ini (Desember)
            [
                'kode_barang' => 'BRG-2024-012',
                'nama_barang' => 'Stop Kontak 4 Lubang',
                'kategori_id' => $kategoris->where('nama_kategori', 'Elektronik')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pcs')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 45,
                'stok_minimal' => 15,
                'lokasi' => 'Rak A2',
                'keterangan' => 'Stop kontak 4 lubang dengan surge protector',
                'created_at' => Carbon::now()->startOfMonth(),
                'updated_at' => Carbon::now()->startOfMonth(),
            ],
            // Barang 13: Dibuat 2 minggu lalu
            [
                'kode_barang' => 'BRG-2024-013',
                'nama_barang' => 'Tinta Printer Hitam',
                'kategori_id' => $kategoris->where('nama_kategori', 'Komputer & IT')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Botol')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang ATK')->first()->id,
                'stok' => 20,
                'stok_minimal' => 5,
                'lokasi' => 'Rak Tinta',
                'keterangan' => 'Tinta printer hitam untuk Epson L-series',
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            // Barang 14: Dibuat 1 minggu lalu
            [
                'kode_barang' => 'BRG-2024-014',
                'nama_barang' => 'Buku Agenda',
                'kategori_id' => $kategoris->where('nama_kategori', 'Alat Tulis Kantor')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Buah')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang ATK')->first()->id,
                'stok' => 35,
                'stok_minimal' => 10,
                'lokasi' => 'Rak Buku',
                'keterangan' => 'Buku agenda ukuran A5',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            // Barang 15: Dibuat hari ini
            [
                'kode_barang' => 'BRG-2024-015',
                'nama_barang' => 'Mouse Wireless',
                'kategori_id' => $kategoris->where('nama_kategori', 'Komputer & IT')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pcs')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 25,
                'stok_minimal' => 8,
                'lokasi' => 'Rak Aksesoris',
                'keterangan' => 'Mouse wireless 2.4GHz',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Barang 16: Dibuat 3 bulan lalu (stok kritis)
            [
                'kode_barang' => 'BRG-2024-016',
                'nama_barang' => 'Kursi Kantor',
                'kategori_id' => $kategoris->where('nama_kategori', 'Furniture')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Unit')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Furniture')->first()->id,
                'stok' => 3, // Stok kritis (di bawah stok minimal)
                'stok_minimal' => 5,
                'lokasi' => 'Area Furniture',
                'keterangan' => 'Kursi kantor ergonomis',
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(3),
            ],
            // Barang 17: Dibuat 4 bulan lalu (stok habis)
            [
                'kode_barang' => 'BRG-2024-017',
                'nama_barang' => 'Baterai AA',
                'kategori_id' => $kategoris->where('nama_kategori', 'Elektronik')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pack')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 0, // Stok habis
                'stok_minimal' => 10,
                'lokasi' => 'Rak Baterai',
                'keterangan' => 'Baterai AA alkaline 4 pcs per pack',
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now()->subMonths(2), // Diupdate 2 bulan lalu
            ],
            // Barang 18: Dibuat 6 bulan lalu (stok rendah)
            [
                'kode_barang' => 'BRG-2024-018',
                'nama_barang' => 'Spidol Whiteboard',
                'kategori_id' => $kategoris->where('nama_kategori', 'Alat Tulis Kantor')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pack')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang ATK')->first()->id,
                'stok' => 12, // Stok rendah
                'stok_minimal' => 10,
                'lokasi' => 'Rak Spidol',
                'keterangan' => 'Spidol whiteboard warna hitam, merah, biru',
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(1), // Diupdate 1 bulan lalu
            ],
            // Barang 19: Dibuat 8 bulan lalu
            [
                'kode_barang' => 'BRG-2024-019',
                'nama_barang' => 'Kabel HDMI',
                'kategori_id' => $kategoris->where('nama_kategori', 'Elektronik')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pcs')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang Elektronik')->first()->id,
                'stok' => 18,
                'stok_minimal' => 10,
                'lokasi' => 'Rak Kabel',
                'keterangan' => 'Kabel HDMI 2.0 panjang 2 meter',
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now()->subMonths(8),
            ],
            // Barang 20: Dibuat 10 bulan lalu
            [
                'kode_barang' => 'BRG-2024-020',
                'nama_barang' => 'Pensil 2B',
                'kategori_id' => $kategoris->where('nama_kategori', 'Alat Tulis Kantor')->first()->id,
                'satuan_id' => $satuans->where('nama_satuan', 'Pack')->first()->id,
                'gudang_id' => $gudangs->where('nama_gudang', 'Gudang ATK')->first()->id,
                'stok' => 22,
                'stok_minimal' => 15,
                'lokasi' => 'Rak Pensil',
                'keterangan' => 'Pensil 2B isi 12 pcs',
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subMonths(5), // Diupdate 5 bulan lalu
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        $this->command->info('Data barang dengan tanggal berbeda-beda berhasil ditambahkan!');
        $this->command->info('Total barang: ' . count($barangs));
        
        // Tampilkan summary berdasarkan tanggal
        $this->command->info("\nSummary berdasarkan tanggal:");
        $this->command->info("============================");
        
        $barangCounts = Barang::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
            
        foreach ($barangCounts as $count) {
            $namaBulan = Carbon::create()->month($count->bulan)->translatedFormat('F');
            $this->command->info("Bulan {$namaBulan}: {$count->total} barang");
        }
    }
}