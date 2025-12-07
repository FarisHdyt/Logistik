<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Satker;
use Illuminate\Support\Facades\Hash;

class MultiRoleUsersSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada satker dulu
        $satker = Satker::first();
        
        if (!$satker) {
            // Buat satker dummy jika belum ada
            $satker = Satker::create([
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

        // ==================== SUPERADMIN ====================
        User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'nrp' => '10000001',
            'email' => 'superadmin@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'satker_id' => $satker->id,
            'jabatan' => 'Super Administrator',
            'pangkat' => 'IPDA',
            'no_hp' => '081100000001',
            'is_active' => true,
        ]);

        // ==================== ADMIN ====================
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'nrp' => '10000002',
            'email' => 'admin@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'satker_id' => $satker->id,
            'jabatan' => 'Administrator Sistem',
            'pangkat' => 'AIPTU',
            'no_hp' => '081100000002',
            'is_active' => true,
        ]);

        // ==================== KABID ====================
        User::create([
            'name' => 'Kepala Bidang Logistik',
            'username' => 'kabid_log',
            'nrp' => '10000003',
            'email' => 'kabid@silog-polres.id',
            'password' => Hash::make('password123'),
            'role' => 'kabid',
            'satker_id' => $satker->id,
            'jabatan' => 'Kepala Bidang Logistik',
            'pangkat' => 'KOMPOL',
            'no_hp' => '081100000003',
            'is_active' => true,
        ]);

        // ==================== USER BIASA ====================
        User::create([
            'name' => 'Brigadir Ahmad',
            'username' => 'ahmad123',
            'nrp' => '20000001',
            'email' => 'ahmad@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'jabatan' => 'Brigadir',
            'pangkat' => 'BRIPKA',
            'no_hp' => '081200000001',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Aipda Siti',
            'username' => 'siti456',
            'nrp' => '20000002',
            'email' => 'siti@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'jabatan' => 'Anggota',
            'pangkat' => 'AIPDA',
            'no_hp' => '081200000002',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Briptu Joko',
            'username' => 'joko789',
            'nrp' => '20000003',
            'email' => 'joko@polres.id',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'satker_id' => $satker->id,
            'jabatan' => 'Anggota',
            'pangkat' => 'BRIPTU',
            'no_hp' => '081200000003',
            'is_active' => true,
        ]);

        // ==================== USER TANPA SATKER ====================
        User::create([
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
        ]);

        $this->command->info('Users for all roles created successfully!');
        $this->command->info('----------------------------------------');
        $this->command->info('SUPERADMIN: superadmin / password123');
        $this->command->info('ADMIN: admin / password123');
        $this->command->info('KABID: kabid_log / password123');
        $this->command->info('USER: ahmad123 / password123');
        $this->command->info('USER: siti456 / password123');
        $this->command->info('USER: joko789 / password123');
        $this->command->info('NOSATKER: nosatker / password123');
        $this->command->info('----------------------------------------');
    }
}