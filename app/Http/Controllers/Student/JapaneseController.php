<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JapaneseController extends Controller
{
    public function hiraganaKatakana()
    {
        return view('student.hiragana-katakana');
    }

    public function kanji(Request $request)
    {
        $level = $request->get('level', 'N5');

        $kanjiN5 = [
            ['kanji' => '日', 'onyomi' => 'ニチ, ジツ', 'kunyomi' => 'ひ, -び, -か', 'meaning' => 'Hari, Matahari', 'strokes' => 4, 'examples' => ['日曜日 (nichiyoubi) - Hari Minggu', '日本 (nihon) - Jepang']],
            ['kanji' => '月', 'onyomi' => 'ゲツ, ガツ', 'kunyomi' => 'つき', 'meaning' => 'Bulan', 'strokes' => 4, 'examples' => ['月曜日 (getsuyoubi) - Hari Senin', '一月 (ichigatsu) - Januari']],
            ['kanji' => '火', 'onyomi' => 'カ', 'kunyomi' => 'ひ, ほ-', 'meaning' => 'Api', 'strokes' => 4, 'examples' => ['火曜日 (kayoubi) - Hari Selasa', '火事 (kaji) - Kebakaran']],
            ['kanji' => '水', 'onyomi' => 'スイ', 'kunyomi' => 'みず', 'meaning' => 'Air', 'strokes' => 4, 'examples' => ['水曜日 (suiyoubi) - Hari Rabu', '水 (mizu) - Air']],
            ['kanji' => '木', 'onyomi' => 'ボク, モク', 'kunyomi' => 'き, こ-', 'meaning' => 'Pohon, Kayu', 'strokes' => 4, 'examples' => ['木曜日 (mokuyoubi) - Hari Kamis', '大木 (taiboku) - Pohon besar']],
            ['kanji' => '金', 'onyomi' => 'キン, コン', 'kunyomi' => 'かね, かな-', 'meaning' => 'Uang, Emas, Logam', 'strokes' => 8, 'examples' => ['金曜日 (kin\'youbi) - Hari Jumat', 'お金 (okane) - Uang']],
            ['kanji' => '土', 'onyomi' => 'ド, ト', 'kunyomi' => 'つち', 'meaning' => 'Tanah', 'strokes' => 3, 'examples' => ['土曜日 (doyoubi) - Hari Sabtu', '土地 (tochi) - Lahan/Tanah']],
            ['kanji' => '山', 'onyomi' => 'サン, セン', 'kunyomi' => 'やま', 'meaning' => 'Gunung', 'strokes' => 3, 'examples' => ['富士山 (fujisan) - Gunung Fuji', '山登り (yamanobori) - Mendaki gunung']],
            ['kanji' => '川', 'onyomi' => 'セン', 'kunyomi' => 'かわ', 'meaning' => 'Sungai', 'strokes' => 3, 'examples' => ['小川 (ogawa) - Sungai kecil', 'ナイル川 (nairugawa) - Sungai Nil']],
            ['kanji' => '田', 'onyomi' => 'デン', 'kunyomi' => 'た', 'meaning' => 'Sawah', 'strokes' => 5, 'examples' => ['田んぼ (tanbo) - Sawah', '水田 (suiden) - Sawah berair']],
            ['kanji' => '人', 'onyomi' => 'ジン, ニン', 'kunyomi' => 'ひと', 'meaning' => 'Orang', 'strokes' => 2, 'examples' => ['日本人 (nihonjin) - Orang Jepang', '三人 (sannin) - Tiga orang']],
            ['kanji' => '子', 'onyomi' => 'シ, ス', 'kunyomi' => 'こ', 'meaning' => 'Anak', 'strokes' => 3, 'examples' => ['子供 (kodomo) - Anak-anak', '女の子 (onnanoko) - Anak perempuan']],
            ['kanji' => '女', 'onyomi' => 'ジョ, ニョ', 'kunyomi' => 'おんな, め', 'meaning' => 'Wanita, Perempuan', 'strokes' => 3, 'examples' => ['女性 (josei) - Wanita', '彼女 (kanojo) - Dia (wanita)/Pacar']],
            ['kanji' => '男', 'onyomi' => 'ダン, ナン', 'kunyomi' => 'おとこ', 'meaning' => 'Pria, Laki-laki', 'strokes' => 7, 'examples' => ['男性 (dansei) - Pria', '男の子 (otokonoko) - Anak laki-laki']],
            ['kanji' => '学', 'onyomi' => 'ガク', 'kunyomi' => 'まな-ぶ', 'meaning' => 'Belajar, Ilmu', 'strokes' => 8, 'examples' => ['学校 (gakkou) - Sekolah', '学生 (gakusei) - Murid/Siswa']],
            ['kanji' => '校', 'onyomi' => 'コウ', 'kunyomi' => '-', 'meaning' => 'Sekolah', 'strokes' => 10, 'examples' => ['中学校 (chuugakkou) - SMP', '高校 (koukou) - SMA']],
            ['kanji' => '先', 'onyomi' => 'セン', 'kunyomi' => 'さき, ま-ず', 'meaning' => 'Sebelumnya, Terdahulu', 'strokes' => 6, 'examples' => ['先生 (sensei) - Guru/Pengajar', '先週 (senshuu) - Minggu lalu']],
            ['kanji' => '生', 'onyomi' => 'セイ, ショウ', 'kunyomi' => 'い-きる, う-まれる', 'meaning' => 'Hidup, Lahir', 'strokes' => 5, 'examples' => ['誕生日 (tanjoubi) - Hari ulang tahun', '生きる (ikiru) - Hidup']],
            ['kanji' => '本', 'onyomi' => 'ホン', 'kunyomi' => 'もと', 'meaning' => 'Buku, Asal', 'strokes' => 5, 'examples' => ['本屋 (hon\'ya) - Toko buku', '山本 (yamamoto) - Nama keluarga']],
            ['kanji' => '何', 'onyomi' => 'カ', 'kunyomi' => 'なに, なん', 'meaning' => 'Apa', 'strokes' => 7, 'examples' => ['何時 (nanji) - Jam berapa', '何人 (nannin) - Berapa orang']],
        ];

        $kanjiN4 = [
            ['kanji' => '会', 'onyomi' => 'カイ, エ', 'kunyomi' => 'あ-う', 'meaning' => 'Bertemu, Perkumpulan', 'strokes' => 6, 'examples' => ['会社 (kaisha) - Perusahaan', '会議 (kaigi) - Rapat']],
            ['kanji' => '社', 'onyomi' => 'シャ', 'kunyomi' => 'やしろ', 'meaning' => 'Perusahaan, Kuil', 'strokes' => 7, 'examples' => ['社長 (shachou) - Direktur', '社会 (shakai) - Masyarakat']],
            ['kanji' => '話', 'onyomi' => 'ワ', 'kunyomi' => 'はな-す, はなし', 'meaning' => 'Berbicara, Cerita', 'strokes' => 13, 'examples' => ['電話 (denwa) - Telepon', '会話 (kaiwa) - Percakapan']],
            ['kanji' => '買', 'onyomi' => 'バイ', 'kunyomi' => 'か-う', 'meaning' => 'Membeli', 'strokes' => 12, 'examples' => ['買い物 (kaimono) - Belanja', '売買 (baibai) - Jual beli']],
            ['kanji' => '言', 'onyomi' => 'ゲン, ゴン', 'kunyomi' => 'い-う, こと', 'meaning' => 'Berkata, Perkataan', 'strokes' => 7, 'examples' => ['言語 (gengo) - Bahasa', '方言 (hougen) - Dialek']],
            ['kanji' => '聞', 'onyomi' => 'ブン, モン', 'kunyomi' => 'き-く, き-こえる', 'meaning' => 'Mendengar, Bertanya', 'strokes' => 14, 'examples' => ['新聞 (shinbun) - Koran', '見聞 (kenbun) - Pengalaman']],
            ['kanji' => '書', 'onyomi' => 'ショ', 'kunyomi' => 'か-く', 'meaning' => 'Menulis, Buku/Dokumen', 'strokes' => 10, 'examples' => ['辞書 (jisho) - Kamus', '書類 (shorui) - Berkas']],
            ['kanji' => '読', 'onyomi' => 'ドク, トク', 'kunyomi' => 'よ-む', 'meaning' => 'Membaca', 'strokes' => 14, 'examples' => ['読書 (dokusho) - Membaca buku', '句読点 (kutouten) - Tanda baca']],
        ];

        $kanjis = $level === 'N4' ? $kanjiN4 : $kanjiN5;

        return view('student.kanji', compact('kanjis', 'level'));
    }
}
