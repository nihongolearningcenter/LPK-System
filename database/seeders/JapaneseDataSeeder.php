<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Vocabulary;
use App\Models\User;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JapaneseDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        $program = Program::first();

        // 1. Mata Pelajaran / Subjects
        $subjectN5 = Subject::firstOrCreate(
            ['name' => 'Bahasa Jepang Dasar (N5)'],
            [
                'program_id'  => $program ? $program->id : 1,
                'slug'        => 'bahasa-jepang-dasar-n5',
                'description' => 'Mata pelajaran dasar meliputi hiragana, katakana, kosakata, dan pola kalimat JLPT N5.',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );

        // 2. Kosakata N5 & N4
        $vocabs = [
            // Salam
            ['japanese' => 'おはようございます', 'hiragana' => 'おはようございます', 'romaji' => 'ohayou gozaimasu', 'meaning_id' => 'Selamat pagi (sopan)', 'category' => 'Salam', 'jlpt_level' => 'N5', 'example_sentence_jp' => '先生、おはようございます。', 'example_sentence_id' => 'Guru, selamat pagi.'],
            ['japanese' => 'こんにちは', 'hiragana' => 'こんにちは', 'romaji' => 'konnichiwa', 'meaning_id' => 'Selamat siang / Halo', 'category' => 'Salam', 'jlpt_level' => 'N5', 'example_sentence_jp' => '皆さん、こんにちは。', 'example_sentence_id' => 'Halo semuanya.'],
            ['japanese' => 'こんばんは', 'hiragana' => 'こんばんは', 'romaji' => 'konbanwa', 'meaning_id' => 'Selamat malam', 'category' => 'Salam', 'jlpt_level' => 'N5', 'example_sentence_jp' => 'こんばんは、お元気ですか。', 'example_sentence_id' => 'Selamat malam, apa kabar?'],
            ['japanese' => 'ありがとうございます', 'hiragana' => 'ありがとうございます', 'romaji' => 'arigatou gozaimasu', 'meaning_id' => 'Terima kasih banyak', 'category' => 'Salam', 'jlpt_level' => 'N5', 'example_sentence_jp' => '手伝ってくれてありがとうございます。', 'example_sentence_id' => 'Terima kasih telah membantu saya.'],
            ['japanese' => 'すみません', 'hiragana' => 'すみません', 'romaji' => 'sumimasen', 'meaning_id' => 'Permisi / Maaf', 'category' => 'Salam', 'jlpt_level' => 'N5', 'example_sentence_jp' => 'すみません、駅はどこですか。', 'example_sentence_id' => 'Permisi, di mana stasiunnya?'],
            // Angka
            ['japanese' => '一', 'hiragana' => 'いち', 'romaji' => 'ichi', 'meaning_id' => 'Satu (1)', 'category' => 'Angka', 'jlpt_level' => 'N5', 'example_sentence_jp' => 'りんごを一つください。', 'example_sentence_id' => 'Tolong beri satu buah apel.'],
            ['japanese' => '二', 'hiragana' => 'に', 'romaji' => 'ni', 'meaning_id' => 'Dua (2)', 'category' => 'Angka', 'jlpt_level' => 'N5', 'example_sentence_jp' => '二時です。', 'example_sentence_id' => 'Sekarang jam dua.'],
            ['japanese' => '三', 'hiragana' => 'さん', 'romaji' => 'san', 'meaning_id' => 'Tiga (3)', 'category' => 'Angka', 'jlpt_level' => 'N5', 'example_sentence_jp' => '三人で行きます。', 'example_sentence_id' => 'Pergi bertiga.'],
            ['japanese' => '四', 'hiragana' => 'よん / し', 'romaji' => 'yon / shi', 'meaning_id' => 'Empat (4)', 'category' => 'Angka', 'jlpt_level' => 'N5', 'example_sentence_jp' => '四月は春です。', 'example_sentence_id' => 'Bulan April adalah musim semi.'],
            ['japanese' => '五', 'hiragana' => 'ご', 'romaji' => 'go', 'meaning_id' => 'Lima (5)', 'category' => 'Angka', 'jlpt_level' => 'N5', 'example_sentence_jp' => '五分待ちます。', 'example_sentence_id' => 'Menunggu lima menit.'],
            // Tempat
            ['japanese' => '学校', 'hiragana' => 'がっこう', 'romaji' => 'gakkou', 'meaning_id' => 'Sekolah', 'category' => 'Tempat', 'jlpt_level' => 'N5', 'example_sentence_jp' => '毎日学校へ行きます。', 'example_sentence_id' => 'Setiap hari pergi ke sekolah.'],
            ['japanese' => '駅', 'hiragana' => 'えき', 'romaji' => 'eki', 'meaning_id' => 'Stasiun', 'category' => 'Tempat', 'jlpt_level' => 'N5', 'example_sentence_jp' => '駅で友達と会います。', 'example_sentence_id' => 'Bertemu teman di stasiun.'],
            ['japanese' => '病院', 'hiragana' => 'びょういん', 'romaji' => 'byouin', 'meaning_id' => 'Rumah sakit', 'category' => 'Tempat', 'jlpt_level' => 'N5', 'example_sentence_jp' => '病院で診察を受けます。', 'example_sentence_id' => 'Menerima pemeriksaan di rumah sakit.'],
            ['japanese' => '銀行', 'hiragana' => 'ぎんこう', 'romaji' => 'ginkou', 'meaning_id' => 'Bank', 'category' => 'Tempat', 'jlpt_level' => 'N5', 'example_sentence_jp' => '銀行でお金を下ろします。', 'example_sentence_id' => 'Mengambil uang di bank.'],
            // Makanan
            ['japanese' => 'ご飯', 'hiragana' => 'ごはん', 'romaji' => 'gohan', 'meaning_id' => 'Nasi / Makanan', 'category' => 'Makanan', 'jlpt_level' => 'N5', 'example_sentence_jp' => '朝ご飯を食べます。', 'example_sentence_id' => 'Makan sarapan pagi.'],
            ['japanese' => '水', 'hiragana' => 'みず', 'romaji' => 'mizu', 'meaning_id' => 'Air', 'category' => 'Makanan', 'jlpt_level' => 'N5', 'example_sentence_jp' => '水を一杯飲みます。', 'example_sentence_id' => 'Minum segelas air.'],
            ['japanese' => 'お茶', 'hiragana' => 'おちゃ', 'romaji' => 'ocha', 'meaning_id' => 'Teh hijau Jepang', 'category' => 'Makanan', 'jlpt_level' => 'N5', 'example_sentence_jp' => '温かいお茶が好きです。', 'example_sentence_id' => 'Saya suka teh hangat.'],
            // Kata Kerja
            ['japanese' => '食べます', 'hiragana' => 'たべます', 'romaji' => 'tabemasu', 'meaning_id' => 'Makan', 'category' => 'Kata Kerja', 'jlpt_level' => 'N5', 'example_sentence_jp' => 'ラーメンを食べます。', 'example_sentence_id' => 'Saya makan ramen.'],
            ['japanese' => '飲みます', 'hiragana' => 'のみます', 'romaji' => 'nomimasu', 'meaning_id' => 'Minum', 'category' => 'Kata Kerja', 'jlpt_level' => 'N5', 'example_sentence_jp' => 'ジュースを飲みます。', 'example_sentence_id' => 'Saya minum jus.'],
            ['japanese' => '行きます', 'hiragana' => 'いきます', 'romaji' => 'ikimasu', 'meaning_id' => 'Pergi', 'category' => 'Kata Kerja', 'jlpt_level' => 'N5', 'example_sentence_jp' => '日本へ行きます。', 'example_sentence_id' => 'Saya pergi ke Jepang.'],
            ['japanese' => '勉強します', 'hiragana' => 'べんきょうします', 'romaji' => 'benkyoushimasu', 'meaning_id' => 'Belajar', 'category' => 'Kata Kerja', 'jlpt_level' => 'N5', 'example_sentence_jp' => '日本語を勉強します。', 'example_sentence_id' => 'Saya belajar bahasa Jepang.'],
        ];

        foreach ($vocabs as $v) {
            Vocabulary::firstOrCreate(
                ['japanese' => $v['japanese']],
                array_merge($v, [
                    'subject_id' => $subjectN5->id,
                    'is_active'  => true,
                ])
            );
        }

        // 3. Pengumuman
        $announcements = [
            [
                'title'        => 'Jadwal Libur Nasional Hari Pahlawan 2026',
                'content'      => "Diberitahukan kepada seluruh siswa dan instruktur LPK Nihongo LC, kelas tatap muka maupun daring pada 10 November 2026 ditiadakan sehubungan dengan peringatan Hari Pahlawan Nasional.\n\nJadwal pengganti sesi materi akan dikoordinasikan secara langsung melalui pengajar masing-masing kelas. Mohon gunakan waktu luang untuk mempelajari kembali modul flashcard dan kosakata di portal siswa.",
                'type'         => 'pengumuman',
                'target'       => 'all',
                'is_published' => true,
                'published_at' => now()->subHours(2),
            ],
            [
                'title'        => 'Pembukaan Pendaftaran Ujian Resmi JLPT Periode Desember',
                'content'      => "Pendaftaran resmi JLPT untuk periode Desember telah dibuka! Siswa tingkat N5 hingga N3 yang berminat dapat mengumpulkan pas foto dan fotokopi KTP di kantor sekretariat sebelum tanggal 15 November 2026.\n\nLPK Nihongo LC menyediakan bimbingan khusus try-out gratis bagi siswa aktif di portal latihan.",
                'type'         => 'pengumuman',
                'target'       => 'students',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title'        => 'Tips Menghafal Kanji dengan Metode Radikal (Bushu)',
                'content'      => "Menghafal kanji tidak harus sulit jika Anda memahami bagian pembentuknya (Radikal atau Bushu). Misalnya, kanji yang berhubungan dengan air hampir selalu memiliki radikal sanzui (氵) seperti 海 (laut), 泳 (berenang), dan 池 (kolam).\n\nCek menu Modul Kanji di sidebar untuk melihat daftar lengkap dan contoh kalimat!",
                'type'         => 'artikel',
                'target'       => 'students',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::firstOrCreate(
                ['title' => $ann['title']],
                array_merge($ann, [
                    'created_by' => $adminId,
                    'slug'       => Str::slug($ann['title']) . '-' . Str::random(4),
                ])
            );
        }

        // 4. Simulasi Ujian JLPT N5
        $exam = Exam::firstOrCreate(
            ['title' => 'Simulasi Tes JLPT N5 — Kosakata & Tata Bahasa'],
            [
                'subject_id'              => $subjectN5->id,
                'created_by'              => $adminId,
                'description'             => 'Tes uji coba kemampuan bahasa Jepang tingkat dasar N5 yang mencakup kosakata, kanji, dan struktur tata bahasa dasar.',
                'type'                    => 'simulasi_jlpt',
                'jlpt_level'              => 'N5',
                'duration_minutes'        => 30,
                'total_questions'         => 5,
                'passing_score'           => 70.00,
                'randomize_questions'     => false,
                'randomize_options'       => false,
                'show_result_immediately' => true,
                'show_explanation'        => true,
                'is_published'            => true,
            ]
        );

        $questionsData = [
            [
                'text'        => '「がっこう」の意味はなんですか？ (Apa arti kata "gakkou"?)',
                'category'    => 'kosakata',
                'explanation' => 'がっこう (gakkou) artinya Sekolah. びょういん = Rumah sakit, かいしゃ = Perusahaan/kantor, えき = Stasiun.',
                'options'     => [
                    ['label' => 'A', 'text' => 'Rumah Sakit', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'Sekolah', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'Kantor', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Stasiun', 'is_correct' => false],
                ],
            ],
            [
                'text'        => 'きのう、スーパーへ（　　）ものを買いに行きました。 (Pilihlah bentuk kata yang tepat)',
                'category'    => 'tata_bahasa',
                'explanation' => 'Bentuk kata benda untuk makanan adalah たべもの (tabemono).',
                'options'     => [
                    ['label' => 'A', 'text' => 'たべ', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'たべる', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'たべた', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'たべます', 'is_correct' => false],
                ],
            ],
            [
                'text'        => '「水」の正しい読み方はどれですか？ (Cara baca kanji air)',
                'category'    => 'kanji',
                'explanation' => 'Kanji 水 cara bacanya みず (mizu) yang berarti air.',
                'options'     => [
                    ['label' => 'A', 'text' => 'き', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'みず', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'ひ', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'かね', 'is_correct' => false],
                ],
            ],
            [
                'text'        => 'わたしは 日本語（　　）べんきょうします。 (Pilihlah partikel yang tepat)',
                'category'    => 'tata_bahasa',
                'explanation' => 'Partikel を (wo dibaca o) menandai objek langsung dari kata kerja transitif べんきょうします.',
                'options'     => [
                    ['label' => 'A', 'text' => 'は', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'に', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'を', 'is_correct' => true],
                    ['label' => 'D', 'text' => 'で', 'is_correct' => false],
                ],
            ],
            [
                'text'        => '「ありがとう」と言われた時、何と返事しますか？ (Jawaban untuk ucapan terima kasih)',
                'category'    => 'kosakata',
                'explanation' => 'どういたしまして (douitashimashite) adalah balasan standar untuk "terima kasih" (sama-sama).',
                'options'     => [
                    ['label' => 'A', 'text' => 'いただきます', 'is_correct' => false],
                    ['label' => 'B', 'text' => 'どういたしまして', 'is_correct' => true],
                    ['label' => 'C', 'text' => 'ごちそうさまでした', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'おやすみなさい', 'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $idx => $qData) {
            $question = Question::firstOrCreate(
                ['question_text' => $qData['text']],
                [
                    'subject_id'  => $subjectN5->id,
                    'created_by'  => $adminId,
                    'type'        => 'pilihan_ganda',
                    'jlpt_level'  => 'N5',
                    'category'    => $qData['category'],
                    'explanation' => $qData['explanation'],
                    'points'      => 20,
                    'difficulty'  => 'mudah',
                    'is_active'   => true,
                ]
            );

            foreach ($qData['options'] as $opt) {
                QuestionOption::firstOrCreate(
                    [
                        'question_id'  => $question->id,
                        'option_label' => $opt['label'],
                    ],
                    [
                        'option_text' => $opt['text'],
                        'is_correct'  => $opt['is_correct'],
                    ]
                );
            }

            // Hubungkan ke Exam
            $exam->questions()->syncWithoutDetaching([
                $question->id => ['sort_order' => $idx + 1, 'points' => 20]
            ]);
        }
    }
}

