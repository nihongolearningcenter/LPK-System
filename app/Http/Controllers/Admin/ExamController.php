<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Question;
use App\Services\QuestionDocumentParserService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['classRoom', 'subject', 'creator'])
            ->withCount(['questions', 'attempts'])
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('jlpt_level')) {
            $query->where('jlpt_level', $request->jlpt_level);
        }

        $exams = $query->paginate(12)->withQueryString();

        return view('admin.ujian.index', compact('exams'));
    }

    public function create()
    {
        $classes = ClassRoom::where('status', 'active')->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.ujian.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:latihan,ujian_harian,ujian_tengah,ujian_akhir,simulasi_jlpt',
            'jlpt_level'       => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'class_id'         => 'nullable|exists:classes,id',
            'subject_id'       => 'nullable|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'passing_score'    => 'required|numeric|min:0|max:100',
            'description'      => 'nullable|string',
            'is_published'     => 'nullable|boolean',
        ]);

        $validated['created_by']   = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        $exam = Exam::create($validated);

        return redirect()->route('admin.ujian.show', $exam)->with('success', 'Ujian berhasil dibuat! Silakan tambahkan butir soal dari Bank Soal.');
    }

    public function show(Exam $ujian)
    {
        $ujian->load(['questions.options', 'classRoom', 'subject', 'attempts.student']);

        $attachedIds = $ujian->questions->pluck('id')->toArray();
        $availableQuestions = Question::whereNotIn('id', $attachedIds)
            ->when($ujian->jlpt_level, fn($q) => $q->where('jlpt_level', $ujian->jlpt_level))
            ->get();

        return view('admin.ujian.show', [
            'exam'               => $ujian,
            'availableQuestions' => $availableQuestions,
        ]);
    }

    public function edit(Exam $ujian)
    {
        $classes = ClassRoom::where('status', 'active')->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('admin.ujian.edit', [
            'exam'     => $ujian,
            'classes'  => $classes,
            'subjects' => $subjects,
        ]);
    }

    public function update(Request $request, Exam $ujian)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:latihan,ujian_harian,ujian_tengah,ujian_akhir,simulasi_jlpt',
            'jlpt_level'       => 'nullable|in:N5,N4,N3,N2,N1,dasar',
            'class_id'         => 'nullable|exists:classes,id',
            'subject_id'       => 'nullable|exists:subjects,id',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'passing_score'    => 'required|numeric|min:0|max:100',
            'description'      => 'nullable|string',
            'is_published'     => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $ujian->update($validated);

        return redirect()->route('admin.ujian.show', $ujian)->with('success', 'Pengaturan ujian berhasil diperbarui!');
    }

    public function destroy(Exam $ujian)
    {
        $ujian->delete();
        return redirect()->route('admin.ujian.index')->with('success', 'Ujian berhasil dihapus.');
    }

    public function addQuestion(Request $request, Exam $ujian)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
        ]);

        $question = Question::findOrFail($request->question_id);
        $ujian->questions()->syncWithoutDetaching([
            $question->id => [
                'sort_order' => $ujian->questions()->count() + 1,
                'points'     => $question->points ?? 1,
            ]
        ]);

        $ujian->update(['total_questions' => $ujian->questions()->count()]);

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan ke ujian!');
    }

    public function removeQuestion(Exam $ujian, Question $question)
    {
        $ujian->questions()->detach($question->id);
        $ujian->update(['total_questions' => $ujian->questions()->count()]);

        return redirect()->back()->with('success', 'Soal berhasil dilepas dari ujian.');
    }

    public function importQuestions(Request $request, Exam $ujian, QuestionDocumentParserService $parser)
    {
        $request->validate([
            'document' => 'required|file|max:10240',
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

            // Save to Question Bank
            $ids = $parser->saveQuestions($parsedQuestions, auth()->id(), $ujian->subject_id);

            // Directly attach to exam
            $currentCount = $ujian->questions()->count();
            $syncData = [];
            foreach ($ids as $idx => $id) {
                $q = Question::find($id);
                $syncData[$id] = [
                    'sort_order' => $currentCount + $idx + 1,
                    'points'     => $q?->points ?? 1,
                ];
            }
            $ujian->questions()->syncWithoutDetaching($syncData);
            $ujian->update(['total_questions' => $ujian->questions()->count()]);

            return redirect()->back()->with('success', 'Berhasil mengimpor ' . count($ids) . ' butir soal dari dokumen "' . $file->getClientOriginalName() . '" dan langsung ditambahkan ke ujian ini!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor dokumen: ' . $e->getMessage());
        }
    }
}
