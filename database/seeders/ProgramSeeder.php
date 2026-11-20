<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'name'              => 'Bahasa Jepang Dasar',
                'type'              => 'dasar',
                'short_description' => 'Mulai perjalanan belajar Bahasa Jepang dari nol. Pelajari Hiragana, Katakana, kosakata dasar, dan percakapan sederhana.',
                'target_participant'=> 'Pemula yang belum pernah belajar Bahasa Jepang',
                'duration_weeks'    => 12,
                'session_per_week'  => '3x seminggu',
                'session_duration_minutes' => 90,
                'method'            => 'Offline',
                'price'             => 1200000,
                'sort_order'        => 1,
            ],
            [
                'name'              => 'Persiapan JLPT N5',
                'type'              => 'jlpt_n5',
                'short_description' => 'Program intensif untuk mempersiapkan ujian JLPT N5. Fokus pada kosakata, tata bahasa, dan latihan soal N5.',
                'target_participant'=> 'Siswa yang sudah menguasai Hiragana dan Katakana',
                'duration_weeks'    => 16,
                'session_per_week'  => '3x seminggu',
                'session_duration_minutes' => 90,
                'method'            => 'Offline',
                'price'             => 1500000,
                'sort_order'        => 2,
            ],
            [
                'name'              => 'Persiapan JLPT N4',
                'type'              => 'jlpt_n4',
                'short_description' => 'Program intensif untuk mempersiapkan ujian JLPT N4. Kosakata lebih luas, tata bahasa tingkat menengah, dan latihan soal N4.',
                'target_participant'=> 'Siswa yang sudah lulus atau setara JLPT N5',
                'duration_weeks'    => 20,
                'session_per_week'  => '3x seminggu',
                'session_duration_minutes' => 90,
                'method'            => 'Offline',
                'price'             => 1800000,
                'sort_order'        => 3,
            ],
            [
                'name'              => 'Percakapan Bahasa Jepang (Kaiwa)',
                'type'              => 'kaiwa',
                'short_description' => 'Program komunikasi aktif dalam Bahasa Jepang sehari-hari. Fokus pada kemampuan berbicara dan mendengar.',
                'target_participant'=> 'Siswa yang ingin meningkatkan kemampuan percakapan',
                'duration_weeks'    => 12,
                'session_per_week'  => '2x seminggu',
                'session_duration_minutes' => 90,
                'method'            => 'Offline',
                'price'             => 1400000,
                'sort_order'        => 4,
            ],
            [
                'name'              => 'Persiapan Kerja ke Jepang',
                'type'              => 'kerja',
                'short_description' => 'Program komprehensif mencakup Bahasa Jepang untuk lingkungan kerja, budaya kerja Jepang, dan persiapan wawancara.',
                'target_participant'=> 'Calon tenaga kerja yang ingin bekerja di Jepang',
                'duration_weeks'    => 24,
                'session_per_week'  => '4x seminggu',
                'session_duration_minutes' => 120,
                'method'            => 'Offline',
                'price'             => 3500000,
                'sort_order'        => 5,
            ],
            [
                'name'              => 'Tokutei Ginou',
                'type'              => 'tokutei_ginou',
                'short_description' => 'Program persiapan bahasa dan materi pendukung untuk program Specified Skilled Worker (Tokutei Ginou) sesuai bidang kerja.',
                'target_participant'=> 'Calon peserta program Tokutei Ginou',
                'duration_weeks'    => 20,
                'session_per_week'  => '4x seminggu',
                'session_duration_minutes' => 120,
                'method'            => 'Offline',
                'price'             => 3000000,
                'sort_order'        => 6,
            ],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(
                ['slug' => Str::slug($program['name'])],
                array_merge($program, [
                    'slug'      => Str::slug($program['name']),
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('✅ ' . count($programs) . ' program pelatihan berhasil dibuat.');
    }
}
