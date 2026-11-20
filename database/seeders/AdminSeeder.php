<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@nihongo-lc.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123!'),
                'role'     => 'admin',
                'phone'    => '08123456789',
                'is_active' => true,
            ]
        );

        // Akun Pengajar Demo
        $instructor = User::firstOrCreate(
            ['email' => 'pengajar@nihongo-lc.id'],
            [
                'name'     => 'Sensei Demo',
                'password' => Hash::make('pengajar123!'),
                'role'     => 'instructor',
                'phone'    => '08987654321',
                'is_active' => true,
            ]
        );

        // Profil pengajar
        $instructor->instructorProfile()->firstOrCreate([
            'user_id' => $instructor->id,
        ], [
            'employee_id'            => 'INST-001',
            'specialization'         => 'Bahasa Jepang JLPT N3-N5',
            'japanese_certification' => 'JLPT N2',
            'join_date'              => now(),
            'status'                 => 'active',
        ]);

        // Akun Siswa Demo
        $student = User::firstOrCreate(
            ['email' => 'siswa@nihongo-lc.id'],
            [
                'name'     => 'Siswa Demo',
                'password' => Hash::make('siswa123!'),
                'role'     => 'student',
                'phone'    => '08111222333',
                'is_active' => true,
            ]
        );

        // Profil siswa
        $student->studentProfile()->firstOrCreate([
            'user_id' => $student->id,
        ], [
            'registration_number' => 'STD-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'gender'              => 'L',
            'education_level'     => 'SMA',
            'japanese_level'      => 'Dasar',
            'status'              => 'active',
        ]);

        // Tagihan & pembayaran lunas untuk siswa demo
        \App\Models\Payment::firstOrCreate(
            ['student_id' => $student->id, 'description' => 'Biaya Program Pelatihan Demo'],
            [
                'invoice_number' => 'INV-DEMO-0001',
                'amount'         => 2500000,
                'amount_paid'    => 2500000,
                'status'         => 'paid',
                'paid_at'        => now(),
                'payment_method' => 'transfer',
            ]
        );

        $this->command->info('✅ Data admin, pengajar, dan siswa demo berhasil dibuat.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',   'admin@nihongo-lc.id',   'admin123!'],
                ['Pengajar','pengajar@nihongo-lc.id', 'pengajar123!'],
                ['Siswa',   'siswa@nihongo-lc.id',    'siswa123!'],
            ]
        );
    }
}
