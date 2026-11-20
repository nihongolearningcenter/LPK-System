<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class ArtikelController extends Controller
{
    protected array $articles = [
        'panduan-belajar-hiragana' => [
            'slug'     => 'panduan-belajar-hiragana',
            'title'    => 'Panduan Lengkap Belajar Hiragana dalam 1 Minggu',
            'category' => 'Tips Belajar',
            'emoji'    => 'あ',
            'date'     => '5 Oktober 2026',
            'readTime' => '8 menit',
            'author'   => 'Tim Nihongo LC',
            'excerpt'  => 'Hiragana adalah alfabet dasar bahasa Jepang yang wajib dikuasai sebelum melanjutkan ke level berikutnya. Dengan teknik yang tepat, Anda bisa menguasainya dalam 7 hari!',
            'content'  => "Hiragana (平仮名) adalah sistem penulisan suku kata fonetik pertama yang wajib dipelajari setiap orang yang mulai belajar bahasa Jepang. Berisi 46 huruf dasar, Hiragana digunakan untuk menuliskan kata-kata asli Jepang, partikel tata bahasa, dan furigana (cara baca kanji).

### Hari 1-2: Vokal Dasar dan Baris K (A, I, U, E, O - KA, KI, KU, KE, KO)
Mulailah dari 5 vokal utama:
- あ (a)
- い (i)
- う (u)
- え (e)
- お (o)

Lanjutkan dengan baris K: か (ka), き (ki), く (ku), け (ke), こ (ko). Gunakan asosiasi visual untuk mengingat bentuk goresan.

### Hari 3-4: Baris S, T, dan N (SA, TA, NA)
Perhatikan perubahan fonetik khusus:
- Pada baris S: さ (sa), **し (shi)**, す (su), せ (se), そ (so)
- Pada baris T: た (ta), **ち (chi)**, **つ (tsu)**, て (te), と (to)
- Baris N: な (na), に (ni), ぬ (nu), ね (ne), の (no)

### Hari 5: Baris H, M, Y (HA, MA, YA)
- Baris H: は (ha), ひ (hi), **ふ (fu)**, へ (he), ほ (ho)
- Baris M: ま (ma), み (mi), む (mu), め (me), も (mo)
- Baris Y: や (ya), ゆ (yu), よ (yo)

### Hari 6: Baris R, W, dan N (RA, WA, N)
- Baris R: ら (ra), り (ri), る (ru), れ (re), ろ (ro)
- Baris W: わ (wa), を (wo/o)
- Huruf konsonan tunggal: ん (n)

### Hari 7: Dakuten (Ten-ten) dan Handakuten (Maru)
Tanda petik dua (゛) mengubah bunyi konsonan tak bersuara menjadi bersuara:
- K → G (が, ぎ, ぐ, げ, ご)
- S → Z (ざ, じ, ず, ぜ, ぞ)
- T → D (だ, ぢ, づ, で, ど)
- H → B (ば, び, ぶ, べ, ぼ)
- Tanda lingkaran kecil (゜) mengubah H → P (ぱ, ぴ, ぷ, ぺ, ぽ)

Dengan konsisten berlatih menulis 20 menit per hari dan menggunakan fitur Flashcard di portal siswa Nihongo LC, Anda akan menguasai Hiragana dengan lancar!",
        ],
        'strategi-lulus-jlpt-n5' => [
            'slug'     => 'strategi-lulus-jlpt-n5',
            'title'    => 'Strategi Lulus JLPT N5 dalam 3 Bulan',
            'category' => 'JLPT',
            'emoji'    => '📝',
            'date'     => '2 Oktober 2026',
            'readTime' => '6 menit',
            'author'   => 'Sensei Tanaka',
            'excerpt'  => 'JLPT N5 adalah level awal dalam ujian kemahiran bahasa Jepang resmi. Berikut strategi terstruktur untuk lulus dengan skor memuaskan.',
            'content'  => "Ujian Kemampuan Bahasa Jepang (Japanese-Language Proficiency Test / JLPT) N5 adalah tolok ukur fundamental untuk kemampuan bahasa Jepang dasar. Untuk lulus JLPT N5, peserta diuji dalam tiga aspek utama:
1. Pengetahuan Bahasa (Kosakata & Kanji)
2. Pengetahuan Bahasa (Tata Bahasa) & Membaca
3. Mendengarkan (Choukai)

### Kebutuhan Materi N5:
- Sekitar 100 karakter Kanji dasar.
- Sekitar 800 kosakata sehari-hari.
- Pola tata bahasa dasar (Buku Minna no Nihongo Bab 1-25).
- Pemahaman percakapan pendek di kelas dan kehidupan harian.

### Rencana Belajar 12 Minggu:
- **Bulan 1:** Kuasai Hiragana & Katakana serta 50 kanji pertama. Selesaikan Minna no Nihongo Bab 1-10.
- **Bulan 2:** Lanjutkan Minna no Nihongo Bab 11-20. Latih pendengaran setiap hari 15 menit menggunakan materi audio LPK Nihongo LC.
- **Bulan 3:** Selesaikan Bab 21-25, perbanyak latihan butir soal di Bank Soal dan ikuti Simulasi JLPT Online yang tersedia di portal siswa.",
        ],
        '80-kanji-jlpt-n5' => [
            'slug'     => '80-kanji-jlpt-n5',
            'title'    => '80 Kanji JLPT N5 yang Wajib Dikuasai',
            'category' => 'Kanji',
            'emoji'    => '🈲',
            'date'     => '28 September 2026',
            'readTime' => '12 menit',
            'author'   => 'Tim Nihongo LC',
            'excerpt'  => 'Daftar lengkap kanji dasar untuk JLPT N5 beserta cara baca Onyomi, Kunyomi, dan artinya dalam bahasa Indonesia.',
            'content'  => "Kanji (漢字) merupakan salah satu tantangan terbesar pembelajar bahasa Jepang. Namun, kanji pada level N5 adalah piktograf yang sangat logis dan menggambarkan bentuk alam serta angka.

### Kategori Angka (数字):
- 一 (ichi / satu)
- 二 (ni / dua)
- 三 (san / tiga)
- 四 (shi, yon / empat)
- 五 (go / lima)
- 六 (roku / enam)
- 七 (shichi, nana / tujuh)
- 八 (hachi / delapan)
- 九 (kyuu, ku / sembilan)
- 十 (juu / sepuluh)
- 百 (hyaku / seratus)
- 千 (sen / seribu)
- 万 (man / sepuluh ribu)

### Kategori Waktu & Alam:
- 日 (hi, nichi / matahari, hari)
- 月 (tsuki, getsu / bulan)
- 火 (hi, ka / api)
- 水 (mizu, sui / air)
- 木 (ki, moku / pohon, kayu)
- 金 (kane, kin / emas, uang)
- 土 (tsuchi, do / tanah)
- 年 (toshi, nen / tahun)
- 時 (toki, ji / waktu, jam)

Gunakan modul Kamus Kanji N5 interaktif pada portal siswa Nihongo LC untuk melihat stroke order dan contoh kata turunan secara lengkap!",
        ],
        'memahami-partikel-wa-dan-ga' => [
            'slug'     => 'memahami-partikel-wa-dan-ga',
            'title'    => 'Memahami Perbedaan Partikel は (wa) dan が (ga)',
            'category' => 'Tata Bahasa',
            'emoji'    => '📚',
            'date'     => '15 September 2026',
            'readTime' => '7 menit',
            'author'   => 'Tim Nihongo LC',
            'excerpt'  => 'Partikel は dan が sering membingungkan pembelajar pemula. Pelajari rahasia membedakannya dengan mudah.',
            'content'  => "Banyak pembelajar bahasa Jepang pemula bertanya: kapan kita memakai は (wa) dan kapan memakai が (ga)?

Secara ringkas:
- **は (wa)** adalah penanda **TOPIK** pembicaraan ('Mengenai hal ini...', 'Berbicara tentang...').
- **が (ga)** adalah penanda **SUBJEK GRAMATIKAL** atau pemberi informasi baru.

### Contoh Perbedaan Nuansa:
1. **私は田中です (Watashi wa Tanaka desu)**
   - Topik: 'Mengenai saya, saya adalah Tanaka.'
2. **私が田中です (Watashi ga Tanaka desu)**
   - Menekankan SUBJEK: 'SAYA-lah yang bernama Tanaka (bukan orang lain).'

Gunakan partikel が saat memperkenalkan informasi baru atau ketika kata tanya (siapa, apa) berada di posisi subjek:
- だれが来ましたか (Dare ga kimashita ka? / Siapa yang datang?)
- 田中さんが来ました (Tanaka-san ga kimashita / Tanaka-san yang datang).",
        ],
        'etika-budaya-kerja-jepang' => [
            'slug'     => 'etika-budaya-kerja-jepang',
            'title'    => 'Etika dan Budaya Kerja di Perusahaan Jepang (Hou-Ren-So)',
            'category' => 'Budaya',
            'emoji'    => '🎌',
            'date'     => '10 September 2026',
            'readTime' => '10 menit',
            'author'   => 'Sensei Kobayashi',
            'excerpt'  => 'Sebelum bekerja atau magang ke Jepang, pahami konsep Hou-Ren-So, kedisiplinan waktu, dan salam Aisatsu di tempat kerja.',
            'content'  => "Bekerja di Jepang (Tokutei Ginou maupun Program Magang Kenshuusei) menuntut pemahaman budaya kerja yang kuat selain kemahiran bahasa.

### Konsep Kunci: HOU-REN-SO (報・連・相)
1. **HOU (Houkoku / 報告)** - Melaporkan: Selalu laporkan progres tugas Anda kepada atasan secara berkala, baik saat tugas selesai maupun saat terjadi kendala.
2. **REN (Renraku / 連絡)** - Menginformasikan: Berbagi informasi yang relevan kepada rekan kerja tanpa menunda.
3. **SO (Soudan / 相談)** - Berkonsultasi: Jangan mengambil keputusan penting sendirian jika ragu; diskusikan dengan atasan atau senior (senpai).

### Kedisiplinan Waktu (5 Menit Sebelumnya):
Di Jepang, pepatah '5分前行動' (5-fun mae koudou) berarti tiba 5 menit sebelum waktu yang ditentukan. Terlambat 1 menit tanpa kabar dianggap pelanggaran etika serius.",
        ],
    ];

    public function index()
    {
        $articles = $this->articles;
        return view('public.artikel.index', compact('articles'));
    }

    public function show(string $slug)
    {
        if (!isset($this->articles[$slug])) {
            // Fallback to first article if slug doesn't match
            $article = reset($this->articles);
        } else {
            $article = $this->articles[$slug];
        }

        $otherArticles = array_filter($this->articles, fn($k) => $k !== $article['slug'], ARRAY_FILTER_USE_KEY);

        return view('public.artikel.show', compact('article', 'otherArticles'));
    }
}
