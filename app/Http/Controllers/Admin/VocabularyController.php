<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vocabulary;
use App\Models\Subject;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    public function index(Request $request)
    {
        $query = Vocabulary::with('subject')->latest();

        if ($request->filled('jlpt_level')) {
            $query->where('jlpt_level', $request->jlpt_level);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('japanese', 'like', '%' . $request->search . '%')
                  ->orWhere('hiragana', 'like', '%' . $request->search . '%')
                  ->orWhere('romaji', 'like', '%' . $request->search . '%')
                  ->orWhere('meaning_id', 'like', '%' . $request->search . '%');
            });
        }

        $vocabularies = $query->paginate(20)->withQueryString();
        $categories = ['Salam', 'Angka', 'Waktu', 'Warna', 'Makanan', 'Keluarga', 'Tempat', 'Pekerjaan', 'Sifat', 'Kata Kerja'];

        return view('admin.kosakata.index', compact('vocabularies', 'categories'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        $categories = ['Salam', 'Angka', 'Waktu', 'Warna', 'Makanan', 'Keluarga', 'Tempat', 'Pekerjaan', 'Sifat', 'Kata Kerja'];

        return view('admin.kosakata.create', compact('subjects', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'          => 'nullable|exists:subjects,id',
            'japanese'            => 'required|string|max:255',
            'hiragana'            => 'nullable|string|max:255',
            'romaji'              => 'nullable|string|max:255',
            'meaning_id'          => 'required|string|max:255',
            'part_of_speech'      => 'nullable|string|max:100',
            'jlpt_level'          => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'category'            => 'nullable|string|max:100',
            'example_sentence_jp' => 'nullable|string',
            'example_sentence_id' => 'nullable|string',
        ]);

        $validated['is_active'] = true;

        Vocabulary::create($validated);

        return redirect()->route('admin.kosakata.index')->with('success', 'Kosakata baru berhasil ditambahkan!');
    }

    public function edit(Vocabulary $kosakatum)
    {
        $subjects = Subject::where('is_active', true)->get();
        $categories = ['Salam', 'Angka', 'Waktu', 'Warna', 'Makanan', 'Keluarga', 'Tempat', 'Pekerjaan', 'Sifat', 'Kata Kerja'];

        return view('admin.kosakata.edit', [
            'vocab'      => $kosakatum,
            'subjects'   => $subjects,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Vocabulary $kosakatum)
    {
        $validated = $request->validate([
            'subject_id'          => 'nullable|exists:subjects,id',
            'japanese'            => 'required|string|max:255',
            'hiragana'            => 'nullable|string|max:255',
            'romaji'              => 'nullable|string|max:255',
            'meaning_id'          => 'required|string|max:255',
            'part_of_speech'      => 'nullable|string|max:100',
            'jlpt_level'          => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'category'            => 'nullable|string|max:100',
            'example_sentence_jp' => 'nullable|string',
            'example_sentence_id' => 'nullable|string',
        ]);

        $kosakatum->update($validated);

        return redirect()->route('admin.kosakata.index')->with('success', 'Kosakata berhasil diperbarui!');
    }

    public function destroy(Vocabulary $kosakatum)
    {
        $kosakatum->delete();
        return redirect()->route('admin.kosakata.index')->with('success', 'Kosakata berhasil dihapus.');
    }
}
