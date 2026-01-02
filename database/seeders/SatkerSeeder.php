<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Satker;

class SatkerSeeder extends Seeder
{
    public function run(): void
    {
        $satkers = [
            // Bagian
            [
                'kode_satker' => 'BAG-OPS',
                'nama_satker' => 'BAGIAN OPERASIONAL',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3501001',
                'email' => 'bagops.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Dr. Ahmad Syafii, S.H., M.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010101',
            ],
            [
                'kode_satker' => 'BAG-SDM',
                'nama_satker' => 'BAGIAN SUMBER DAYA MANUSIA',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3501002',
                'email' => 'bagsdm.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Bambang Sutrisno, S.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010202',
            ],
            [
                'kode_satker' => 'BAG-LOG',
                'nama_satker' => 'BAGIAN LOGISTIK',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3501003',
                'email' => 'baglog.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Fitriani, S.E.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010303',
            ],
            [
                'kode_satker' => 'BAG-REN',
                'nama_satker' => 'BAGIAN PERENCANAAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3501004',
                'email' => 'bagren.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Agus Setiawan, S.T.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010404',
            ],

            // Satuan Reserse
            [
                'kode_satker' => 'SAT-RESKRIM',
                'nama_satker' => 'SATUAN RESERSE KRIMINAL',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3502001',
                'email' => 'satreskrim.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Dr. Siti Rahayu, S.H., M.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010505',
            ],
            [
                'kode_satker' => 'SAT-RESNARKOBA',
                'nama_satker' => 'SATUAN RESERSE NARKOBA',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3502002',
                'email' => 'satresnarkoba.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Rudi Hartono, S.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010606',
            ],

            // Satuan Lainnya
            [
                'kode_satker' => 'SAT-INTELKAM',
                'nama_satker' => 'SATUAN INTELIJEN DAN KEAMANAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3503001',
                'email' => 'satintelkam.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Andi Wijaya, S.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010707',
            ],
            [
                'kode_satker' => 'SAT-LANTAS',
                'nama_satker' => 'SATUAN LALU LINTAS',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3503002',
                'email' => 'satlantas.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Budi Santoso, S.H.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010808',
            ],
            [
                'kode_satker' => 'SAT-BINMAS',
                'nama_satker' => 'SATUAN PEMBINAAN MASYARAKAT',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3503003',
                'email' => 'satbinmas.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Dewi Lestari, S.Pd.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72010909',
            ],
            [
                'kode_satker' => 'SAT-SAMAPTA',
                'nama_satker' => 'SATUAN SAMAPTA',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3503004',
                'email' => 'satsamapta.polres@polri.go.id',
                'nama_kepala' => 'KOMPOL Ahmad Fauzi, S.I.K.',
                'pangkat_kepala' => 'KOMPOL',
                'nrp_kepala' => '72011010',
            ],

            // Seksi
            [
                'kode_satker' => 'SIE-PROPAM',
                'nama_satker' => 'SEKSI PROFESI DAN PENGAMANAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504001',
                'email' => 'siepropam.polres@polri.go.id',
                'nama_kepala' => 'AKP Eko Prasetyo, S.H.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011111',
            ],
            [
                'kode_satker' => 'SIE-HUKUM',
                'nama_satker' => 'SEKSI HUKUM',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504002',
                'email' => 'siehukum.polres@polri.go.id',
                'nama_kepala' => 'AKP Dwi Cahyono, S.Sos.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011212',
            ],
            [
                'kode_satker' => 'SIE-HUMAS',
                'nama_satker' => 'SEKSI HUBUNGAN MASYARAKAT',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504003',
                'email' => 'siehumas.polres@polri.go.id',
                'nama_kepala' => 'AKP Yudi Hermawan, S.Kom.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011313',
            ],
            [
                'kode_satker' => 'SIE-DOKKES',
                'nama_satker' => 'SEKSI DOKTER DAN KESEHATAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504004',
                'email' => 'siedokkes.polres@polri.go.id',
                'nama_kepala' => 'AKP dr. Rina Anggraeni, Sp.PD',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011414',
            ],
            [
                'kode_satker' => 'SIE-TIK',
                'nama_satker' => 'SEKSI TEKNOLOGI INFORMASI DAN KOMUNIKASI',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504005',
                'email' => 'sietik.polres@polri.go.id',
                'nama_kepala' => 'AKP Sri Wahyuni, S.E., M.Ak.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011515',
            ],
            [
                'kode_satker' => 'SIE-WAS',
                'nama_satker' => 'SEKSI PENGAWASAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504006',
                'email' => 'siewas.polres@polri.go.id',
                'nama_kepala' => 'AKP Bambang Setiawan, S.H.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011616',
            ],
            [
                'kode_satker' => 'SIE-KEU',
                'nama_satker' => 'SEKSI KEUANGAN',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504007',
                'email' => 'siekeu.polres@polri.go.id',
                'nama_kepala' => 'AKP Fitri Wulandari, S.E.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011717',
            ],
            [
                'kode_satker' => 'SIE-UM',
                'nama_satker' => 'SEKSI UMUM',
                'alamat' => 'Jl. Kramat Raya No. 75, Jakarta Pusat',
                'telepon' => '(021) 3504008',
                'email' => 'sieum.polres@polri.go.id',
                'nama_kepala' => 'AKP Joko Susilo, S.T.',
                'pangkat_kepala' => 'AKP',
                'nrp_kepala' => '72011818',
            ],
        ];

        foreach ($satkers as $satker) {
            Satker::create($satker);
        }

        $this->command->info('Data satker berhasil ditambahkan!');
        $this->command->info('Total satker: ' . count($satkers));
    }
}