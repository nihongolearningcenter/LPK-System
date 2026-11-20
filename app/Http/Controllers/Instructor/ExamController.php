<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Services\QuestionDocumentParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $classIds = ClassRoom::where('instructor_id', $user->id)->pluck('id');

        $query = Exam::with(['classRoom', 'subject', 'creator'])
            ->withCount(['questions', 'attempts'])
            ->where(function ($q) use ($user, $classIds) {
                $q->where('created_by', $user->id)
                  ->orWhereIn('class_id', $classIds);
            })
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('jlpt_level')) {
            $query->where('jlpt_level', $request->jlpt_level);
        }

        $exams = $query->paginate(12)->withQueryString();

        return view('instructor.ujian.index', compact('exams'));
    }

    public function create()
    {
        $user = Auth::user();
        $classes  = ClassRoom::where('instructor_id', $user->id)->get();
        $subjects = Subject::where('is_active', true)->get();
        $questions = Question::with('options')->where('is_active', true)->latest()->get();

        return view('instructor.ujian.create', compact('classes', 'subjects', 'questions'));
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
            'question_ids'     => 'nullable|array',
            'question_ids.*'   => 'exists:questions,id',
            'document'         => 'nullable|file|max:10240',
        ]);

        $validated['created_by']   = Auth::id();
        $validated['is_published'] = $request->boolean('is_published');

        $exam = Exam::create($validated);

        $questionIds = $validated['question_ids'] ?? [];

        if ($request->hasFile('document')) {
            $parser = app(QuestionDocumentParserService::class);
            $parsedQuestions = $parser->parseFile($request->file('document'));
            if (!empty($parsedQuestions)) {
                $importedIds = $parser->saveQuestions($parsedQuestions, Auth::id(), $exam->subject_id);
                $questionIds = array_unique(array_merge($questionIds, $importedIds));
            }
        }

        if (!empty($questionIds)) {
            $syncData = [];
            foreach ($questionIds as $idx => $qId) {
                $qModel = Question::find($qId);
                $syncData[$qId] = [
                    'sort_order' => $idx + 1,
                    'points'     => $qModel?->points ?? 1,
                ];
            }
            $exam->questions()->sync($syncData);
            $exam->update(['total_questions' => count($syncData)]);
        }

        return redirect()->route('instructor.ujian.show', $exam)
                         ->with('success', 'Paket ujian berhasil dibuat beserta ' . count($questionIds) . ' butir soal.');
    }

    public function show(Exam $ujian)
    {
        $ujian->load(['classRoom', 'subject', 'creator', 'questions.options', 'attempts.student']);

        $existingQuestionIds = $ujian->questions->pluck('id');
        $availableQuestions = Question::with('options')
            ->whereNotIn('id', $existingQuestionIds)
            ->where('is_active', true)
            ->when($ujian->jlpt_level, fn($q) => $q->where('jlpt_level', $ujian->jlpt_level))
            ->latest()
            ->get();

        return view('instructor.ujian.show', compact('ujian', 'availableQuestions'));
    }

    public function edit(Exam $ujian)
    {
        $user = Auth::user();
        $classes  = ClassRoom::where('instructor_id', $user->id)->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('instructor.ujian.edit', compact('ujian', 'classes', 'subjects'));
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

        $validated['is_published'] = $request->boolean('is_published');
        $ujian->update($validated);

        return redirect()->route('instructor.ujian.show', $ujian)
                         ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $ujian)
    {
        $ujian->questions()->detach();
        $ujian->delete();

        return redirect()->route('instructor.ujian.index')
                         ->with('success', 'Ujian berhasil dihapus.');
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
            $ids = $parser->saveQuestions($parsedQuestions, Auth::id(), $ujian->subject_id);

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
