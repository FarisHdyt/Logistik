<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Satker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MultiRoleUsersSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada satker dulu - ambil semua satker yang ada
        $satkers = Satker::all();
        
        if ($satkers->isEmpty()) {
            // Jalankan seeder satker terlebih dahulu
            $this->call(SatkerSeeder::class);
            $satkers = Satker::all();
        }

        // Ambil beberapa satker untuk distribusi user
        $bagOps = $satkers->firstWhere('kode_satker', 'BAG-OPS');
        $bagLog = $satkers->firstWhere('kode_satker', 'BAG-LOG');
        $satReskrim = $satkers->firstWhere('kode_satker', 'SAT-RESKRIM');
        $satLantas = $satkers->firstWhere('kode_satker', 'SAT-LANTAS');
        $sieTik = $satkers->firstWhere('kode_satker', 'SIE-TIK');
        $bagSdm = $satkers->firstWhere('kode_satker', 'BAG-SDM');
        $bagRen = $satkers->firstWhere('kode_satker', 'BAG-REN');
        $satResnarkoba = $satkers->firstWhere('kode_satker', 'SAT-RESNARKOBA');
        $satIntelkam = $satkers->firstWhere('kode_satker', 'SAT-INTELKAM');
        $satBinmas = $satkers->firstWhere('kode_satker', 'SAT-BINMAS');
        $satSamapta = $satkers->firstWhere('kode_satker', 'SAT-SAMAPTA');
        $sieHumas = $satkers->firstWhere('kode_satker', 'SIE-HUMAS');
        $sieKeu = $satkers->firstWhere('kode_satker', 'SIE-KEU');

        $users = [];

        // ==================== SUPERADMIN ====================
        $users[] = [
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'nrp' => '10000001',
            'email' => 'superadmin@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'satker_id' => $bagLog->id, // Superadmin di Bagian Logistik
            'jabatan' => 'Kepala Bagian Logistik',
            'pangkat' => 'KOMPOL',
            'no_hp' => '081100000001',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(5)->subDays(15),
            'updated_at' => Carbon::now()->subMonths(5)->subDays(15),
        ];

        // ==================== ADMIN ====================
        $users[] = [
            'name' => 'Administrator',
            'username' => 'admin',
            'nrp' => '10000002',
            'email' => 'admin@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'satker_id' => $bagLog->id, // Admin di Bagian Logistik
            'jabatan' => 'Administrator Sistem Logistik',
            'pangkat' => 'IPDA',
            'no_hp' => '081100000002',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(4)->subDays(10),
            'updated_at' => Carbon::now()->subMonths(4)->subDays(10),
        ];

        // ==================== USER BIASA ====================
        $users[] = [
            'name' => 'Brigadir Ahmad',
            'username' => 'ahmad123',
            'nrp' => '20000001',
            'email' => 'ahmad@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satReskrim->id, // User di Sat Reskrim
            'jabatan' => 'Anggota Reserse',
            'pangkat' => 'BRIPKA',
            'no_hp' => '081200000001',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(3)->subDays(25),
            'updated_at' => Carbon::now()->subMonths(3)->subDays(25),
        ];

        $users[] = [
            'name' => 'Aipda Siti',
            'username' => 'siti456',
            'nrp' => '20000002',
            'email' => 'siti@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $bagOps->id, // User di Bagian Ops
            'jabatan' => 'Operator Sistem',
            'pangkat' => 'AIPDA',
            'no_hp' => '081200000002',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(3)->subDays(20),
            'updated_at' => Carbon::now()->subMonths(3)->subDays(20),
        ];

        $users[] = [
            'name' => 'Briptu Joko',
            'username' => 'joko789',
            'nrp' => '20000003',
            'email' => 'joko@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satLantas->id, // User di Sat Lantas
            'jabatan' => 'Petugas Lalu Lintas',
            'pangkat' => 'BRIPTU',
            'no_hp' => '081200000003',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(3)->subDays(15),
            'updated_at' => Carbon::now()->subMonths(3)->subDays(15),
        ];

        // ==================== USER TANPA SATKER ====================
        $users[] = [
            'name' => 'User Tanpa Satker',
            'username' => 'nosatker',
            'nrp' => '30000001',
            'email' => 'nosatker@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => null, // Tidak punya satker
            'jabatan' => 'Staff',
            'pangkat' => '-',
            'no_hp' => '081300000001',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(28),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(28),
        ];

        // ==================== TAMBAHAN USER UNTUK MEMENUHI 15 USER ====================
        $users[] = [
            'name' => 'Ipda Rudi Hartono',
            'username' => 'rudi_h',
            'nrp' => '20000004',
            'email' => 'rudi@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $bagSdm->id, // User di Bagian SDM
            'jabatan' => 'Koordinator SDM',
            'pangkat' => 'IPDA',
            'no_hp' => '081200000004',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(3)->subDays(10),
            'updated_at' => Carbon::now()->subMonths(3)->subDays(10),
        ];

        $users[] = [
            'name' => 'Aiptu Bambang',
            'username' => 'bambang78',
            'nrp' => '20000005',
            'email' => 'bambang@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $bagRen->id, // User di Bagian Perencanaan
            'jabatan' => 'Perencana',
            'pangkat' => 'AIPTU',
            'no_hp' => '081200000005',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(3)->subDays(5),
            'updated_at' => Carbon::now()->subMonths(3)->subDays(5),
        ];

        $users[] = [
            'name' => 'Brigadir Linda',
            'username' => 'linda_s',
            'nrp' => '20000006',
            'email' => 'linda@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satResnarkoba->id, // User di Sat Resnarkoba
            'jabatan' => 'Anggota Narkoba',
            'pangkat' => 'BRIGADIR',
            'no_hp' => '081200000006',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(25),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(25),
        ];

        $users[] = [
            'name' => 'Aipda Hendra',
            'username' => 'hendra_k',
            'nrp' => '20000007',
            'email' => 'hendra@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satIntelkam->id, // User di Sat Intelkam
            'jabatan' => 'Anggota Intelijen',
            'pangkat' => 'AIPDA',
            'no_hp' => '081200000007',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(20),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(20),
        ];

        $users[] = [
            'name' => 'Briptu Andi',
            'username' => 'andi99',
            'nrp' => '20000008',
            'email' => 'andi@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satBinmas->id, // User di Sat Binmas
            'jabatan' => 'Anggota Binmas',
            'pangkat' => 'BRIPTU',
            'no_hp' => '081200000008',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(15),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(15),
        ];

        $users[] = [
            'name' => 'Ipda Mulyadi',
            'username' => 'mulyadi',
            'nrp' => '10000003',
            'email' => 'mulyadi@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'satker_id' => $bagLog->id, // Admin di Bagian Logistik
            'jabatan' => 'Admin Logistik',
            'pangkat' => 'IPDA',
            'no_hp' => '081100000003',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(4)->subDays(5),
            'updated_at' => Carbon::now()->subMonths(4)->subDays(5),
        ];

        $users[] = [
            'name' => 'Aiptu Surya',
            'username' => 'surya_a',
            'nrp' => '20000009',
            'email' => 'surya@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $bagLog->id, // User di Bagian Logistik
            'jabatan' => 'Petugas Gudang',
            'pangkat' => 'AIPTU',
            'no_hp' => '081200000009',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(10),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(10),
        ];

        $users[] = [
            'name' => 'Brigadir Rina',
            'username' => 'rina_m',
            'nrp' => '20000010',
            'email' => 'rina@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $sieHumas->id, // User di Seksi Humas
            'jabatan' => 'Petugas Humas',
            'pangkat' => 'BRIGADIR',
            'no_hp' => '081200000010',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(2)->subDays(5),
            'updated_at' => Carbon::now()->subMonths(2)->subDays(5),
        ];

        $users[] = [
            'name' => 'Aipda Dedi',
            'username' => 'dedi_c',
            'nrp' => '20000011',
            'email' => 'dedi@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satSamapta->id, // User di Sat Samapta
            'jabatan' => 'Anggota Samapta',
            'pangkat' => 'AIPDA',
            'no_hp' => '081200000011',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(1)->subDays(25),
            'updated_at' => Carbon::now()->subMonths(1)->subDays(25),
        ];

        $users[] = [
            'name' => 'Superadmin 2',
            'username' => 'superadmin2',
            'nrp' => '10000004',
            'email' => 'superadmin2@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'satker_id' => $bagLog->id, // Superadmin di Bagian Logistik
            'jabatan' => 'Wakil Kepala Bagian Logistik',
            'pangkat' => 'AKP',
            'no_hp' => '081100000004',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(5)->subDays(5),
            'updated_at' => Carbon::now()->subMonths(5)->subDays(5),
        ];

        $users[] = [
            'name' => 'Aiptu Eko',
            'username' => 'eko_p',
            'nrp' => '20000012',
            'email' => 'eko@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $sieKeu->id, // User di Seksi Keuangan
            'jabatan' => 'Petugas Keuangan',
            'pangkat' => 'AIPTU',
            'no_hp' => '081200000012',
            'is_active' => true,
            'created_at' => Carbon::now()->subMonths(1)->subDays(20),
            'updated_at' => Carbon::now()->subMonths(1)->subDays(20),
        ];

        // Insert semua user ke database
        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('15 users created successfully with different creation times!');
        $this->command->info('----------------------------------------');
        $this->command->info('SUPERADMIN: superadmin / password123 (BAG-LOG)');
        $this->command->info('SUPERADMIN: superadmin2 / password123 (BAG-LOG)');
        $this->command->info('ADMIN: admin / password123 (BAG-LOG)');
        $this->command->info('ADMIN: mulyadi / password123 (BAG-LOG)');
        $this->command->info('USER: ahmad123 / password123 (SAT-RESKRIM)');
        $this->command->info('USER: siti456 / password123 (BAG-OPS)');
        $this->command->info('USER: joko789 / password123 (SAT-LANTAS)');
        $this->command->info('USER: rudi_h / password123 (BAG-SDM)');
        $this->command->info('USER: bambang78 / password123 (BAG-REN)');
        $this->command->info('USER: linda_s / password123 (SAT-RESNARKOBA)');
        $this->command->info('USER: hendra_k / password123 (SAT-INTELKAM)');
        $this->command->info('USER: andi99 / password123 (SAT-BINMAS)');
        $this->command->info('USER: surya_a / password123 (BAG-LOG)');
        $this->command->info('USER: rina_m / password123 (SIE-HUMAS)');
        $this->command->info('USER: dedi_c / password123 (SAT-SAMAPTA)');
        $this->command->info('USER: eko_p / password123 (SIE-KEU)');
        $this->command->info('NOSATKER: nosatker / password123 (TANPA SATKER)');
        $this->command->info('----------------------------------------');
        
        // Tampilkan distribusi satker
        $satkerDistribution = [];
        foreach ($users as $user) {
            if ($user['satker_id']) {
                $satkerName = Satker::find($user['satker_id'])->nama_satker ?? 'Unknown';
                $satkerDistribution[$satkerName] = ($satkerDistribution[$satkerName] ?? 0) + 1;
            }
        }
        
        $this->command->info('Distribusi Satker:');
        foreach ($satkerDistribution as $satkerName => $count) {
            $this->command->info("  - {$satkerName}: {$count} user");
        }
        $this->command->info('  - TANPA SATKER: 1 user');
        
        $this->command->info('----------------------------------------');
        $this->command->info('Distribusi Bagian Logistik:');
        $this->command->info('  - 2 Superadmin (Kepala dan Wakil Kepala)');
        $this->command->info('  - 2 Admin (Administrator Sistem)');
        $this->command->info('  - 1 User (Petugas Gudang)');
        
        $this->command->info('----------------------------------------');
        $this->command->info('Total: 2 Superadmin, 2 Admin, 11 User, 1 No Satker');
        $this->command->info('Creation times range from 5 months ago to 1 month ago');
    }
}