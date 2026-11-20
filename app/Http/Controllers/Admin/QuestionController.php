<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Services\QuestionDocumentParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'options'])->latest();

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('jlpt_level')) {
            $query->where('jlpt_level', $request->jlpt_level);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->paginate(15)->withQueryString();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.bank-soal.index', compact('questions', 'subjects'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.bank-soal.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'     => 'nullable|exists:subjects,id',
            'question_text'  => 'required|string',
            'type'           => 'required|in:pilihan_ganda,esai,benar_salah',
            'jlpt_level'     => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'category'       => 'nullable|in:kosakata,tata_bahasa,membaca,menyimak,kanji',
            'explanation'    => 'nullable|string',
            'points'         => 'required|integer|min:1',
            'difficulty'     => 'required|in:mudah,sedang,sulit',
            'options'        => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct_option' => 'required',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $question = Question::create([
                'subject_id'    => $validated['subject_id'],
                'created_by'    => auth()->id(),
                'question_text' => $validated['question_text'],
                'type'          => $validated['type'],
                'jlpt_level'    => $validated['jlpt_level'],
                'category'      => $validated['category'],
                'explanation'   => $validated['explanation'],
                'points'        => $validated['points'],
                'difficulty'    => $validated['difficulty'],
                'is_active'     => true,
            ]);

            $labels = ['A', 'B', 'C', 'D', 'E'];
            foreach ($validated['options'] as $idx => $opt) {
                QuestionOption::create([
                    'question_id'  => $question->id,
                    'option_label' => $labels[$idx] ?? 'X',
                    'option_text'  => $opt['text'],
                    'is_correct'   => ((string)$idx === (string)$request->correct_option),
                ]);
            }
        });

        return redirect()->route('admin.bank-soal.index')->with('success', 'Soal baru berhasil ditambahkan ke Bank Soal!');
    }

    public function edit(Question $bank_soal)
    {
        $bank_soal->load('options');
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.bank-soal.edit', [
            'question' => $bank_soal,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, Question $bank_soal)
    {
        $validated = $request->validate([
            'subject_id'     => 'nullable|exists:subjects,id',
            'question_text'  => 'required|string',
            'type'           => 'required|in:pilihan_ganda,esai,benar_salah',
            'jlpt_level'     => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'category'       => 'nullable|in:kosakata,tata_bahasa,membaca,menyimak,kanji',
            'explanation'    => 'nullable|string',
            'points'         => 'required|integer|min:1',
            'difficulty'     => 'required|in:mudah,sedang,sulit',
            'options'        => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct_option' => 'required',
        ]);

        DB::transaction(function () use ($validated, $request, $bank_soal) {
            $bank_soal->update([
                'subject_id'    => $validated['subject_id'],
                'question_text' => $validated['question_text'],
                'type'          => $validated['type'],
                'jlpt_level'    => $validated['jlpt_level'],
                'category'      => $validated['category'],
                'explanation'   => $validated['explanation'],
                'points'        => $validated['points'],
                'difficulty'    => $validated['difficulty'],
            ]);

            $bank_soal->options()->delete();

            $labels = ['A', 'B', 'C', 'D', 'E'];
            foreach ($validated['options'] as $idx => $opt) {
                QuestionOption::create([
                    'question_id'  => $bank_soal->id,
                    'option_label' => $labels[$idx] ?? 'X',
                    'option_text'  => $opt['text'],
                    'is_correct'   => ((string)$idx === (string)$request->correct_option),
                ]);
            }
        });

        return redirect()->route('admin.bank-soal.index')->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Question $bank_soal)
    {
        $bank_soal->delete();
        return redirect()->route('admin.bank-soal.index')->with('success', 'Soal berhasil dihapus.');
    }

    public function import(Request $request, QuestionDocumentParserService $parser)
    {
        $request->validate([
            'document'   => 'required|file|max:10240',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        $file = $request->file('document');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt', 'docx'])) {
            return redirect()->back()->with('error', 'Format berkas tidak didukung. Mohon gunakan berkas .csv, .docx, atau .txt.');
        }

        try {
            $parsedQuestions = $parser->parseFile($file);
            if (empty($parsedQuestions)) {
                return redirect()->back()->with('error', 'Tidak ada butir soal yang berhasil dibaca dari dokumen. Pastikan format dokumen sesuai panduan/template.');
            }

            $ids = $parser->saveQuestions($parsedQuestions, auth()->id(), $request->subject_id);

            return redirect()->route('admin.bank-soal.index')
                             ->with('success', 'Berhasil mengimpor ' . count($ids) . ' butir soal dari dokumen "' . $file->getClientOriginalName() . '" ke Bank Soal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor dokumen: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(string $format, QuestionDocumentParserService $parser)
    {
        $format = strtolower($format);
        if ($format === 'csv') {
            $content = $parser->generateCsvTemplate();
            return response($content, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="template_soal_lpk.csv"',
            ]);
        }

        if ($format === 'docx') {
            $binary = $parser->generateDocxTemplate();
            return response($binary, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'Content-Disposition' => 'attachment; filename="template_soal_lpk.docx"',
            ]);
        }

        $content = $parser->generateTextTemplate();
        return response($content, 200, [
            'Content-Type'        => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_soal_lpk.txt"',
        ]);
    }
}
