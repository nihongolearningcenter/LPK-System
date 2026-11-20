<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Vocabulary;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    public function index(Request $request)
    {
        $categories = ['Semua', 'Salam', 'Angka', 'Waktu', 'Warna', 'Makanan', 'Keluarga', 'Tempat', 'Pekerjaan', 'Sifat', 'Kata Kerja'];
        $selectedCategory = $request->get('category', 'Semua');
        $selectedLevel = $request->get('level', 'all');

        $query = Vocabulary::active();

        if ($selectedCategory !== 'Semua') {
            $query->where('category', $selectedCategory);
        }

        if ($selectedLevel !== 'all') {
            $query->where('jlpt_level', $selectedLevel);
        }

        $vocabularies = $query->paginate(24)->withQueryString();

        return view('student.kosakata.index', compact('vocabularies', 'categories', 'selectedCategory', 'selectedLevel'));
    }

    public function flashcard(Request $request)
    {
        $level = $request->get('level', 'N5');

        $dbVocabs = Vocabulary::active()
            ->when($level !== 'all', fn($q) => $q->where('jlpt_level', $level))
            ->inRandomOrder()
            ->limit(30)
            ->get();

        if ($dbVocabs->count() > 0) {
            $cards = $dbVocabs->map(function ($v) {
                return [
                    'jp'    => $v->japanese,
                    'kana'  => $v->hiragana ?? $v->romaji ?? '',
                    'id'    => $v->meaning_id,
                    'ex'    => $v->example_sentence_jp ?? '例文なし',
                    'ex_id' => $v->example_sentence_id ?? '',
                ];
            })->toArray();
        } else {
            $cards = [
                ['jp'=>'ありがとう','kana'=>'arigatou','id'=>'Terima kasih','ex'=>'ありがとうございます。','ex_id'=>'Terima kasih banyak.'],
                ['jp'=>'おはようございます','kana'=>'ohayou gozaimasu','id'=>'Selamat pagi (sopan)','ex'=>'おはようございます！','ex_id'=>'Selamat pagi!'],
                ['jp'=>'すみません','kana'=>'sumimasen','id'=>'Permisi / Maaf','ex'=>'すみません、トイレはどこですか？','ex_id'=>'Permisi, di mana toiletnya?'],
                ['jp'=>'がっこう','kana'=>'gakkou','id'=>'Sekolah','ex'=>'がっこうへいきます。','ex_id'=>'Saya pergi ke sekolah.'],
                ['jp'=>'たべます','kana'=>'tabemasu','id'=>'Makan','ex'=>'ごはんをたべます。','ex_id'=>'Saya makan nasi.'],
                ['jp'=>'のみます','kana'=>'nomimasu','id'=>'Minum','ex'=>'おちゃをのみます。','ex_id'=>'Saya minum teh.'],
                ['jp'=>'せんせい','kana'=>'sensei','id'=>'Guru / Dokter','ex'=>'せんせい、しつもんがあります。','ex_id'=>'Guru, saya punya pertanyaan.'],
                ['jp'=>'ともだち','kana'=>'tomodachi','id'=>'Teman','ex'=>'ともだちにあいます。','ex_id'=>'Saya bertemu teman.'],
            ];
        }

        return view('student.kosakata.flashcard', compact('cards', 'level'));
    }
}
