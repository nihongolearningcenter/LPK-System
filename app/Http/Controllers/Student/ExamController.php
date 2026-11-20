<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function jlptIndex(Request $request)
    {
        $level = $request->get('level', 'N5');

        $jlptExams = Exam::where('type', 'simulasi_jlpt')
            ->where('is_published', true)
            ->when($level, fn($q) => $q->where('jlpt_level', $level))
            ->get();

        $sampleQuestions = [
            [
                'q'       => '「がっこう」の意味はなんですか？',
                'q_sub'   => 'Apa arti dari kata 「がっこう」?',
                'options' => ['Rumah sakit', 'Sekolah', 'Kantor', 'Stasiun'],
                'correct' => 1,
                'exp'     => 'がっこう (gakkou) artinya Sekolah. Rumah sakit = びょういん, Kantor = かいしゃ, Stasiun = えき.',
            ],
            [
                'q'       => 'きのう、スーパーへ（　　）ものを買いに行きました。',
                'q_sub'   => 'Pilihlah partikel atau kata yang tepat untuk melengkapi kalimat.',
                'options' => ['たべ', 'たべる', 'たべた', 'たべます'],
                'correct' => 0,
                'exp'     => 'Bentuk pola kalimat tujuan adalah Kata Kerja Stem (Masu-kei tanpa masu) + もの, jadi たべもの (makanan).',
            ],
            [
                'q'       => '「水」の読み方はどれですか？',
                'q_sub'   => 'Bagaimana cara membaca Kanji 「水」?',
                'options' => ['き', 'みず', 'ひ', 'かね'],
                'correct' => 1,
                'exp'     => 'Kanji 水 dibaca みず (mizu) yang berarti air. き (木) = pohon, ひ (火) = api, かね (金) = emas/uang.',
            ],
        ];

        return view('student.jlpt.index', compact('jlptExams', 'sampleQuestions', 'level'));
    }

    public function jlptShow(Exam $exam)
    {
        $exam->load(['questions.options']);
        return view('student.jlpt.show', compact('exam'));
    }

    public function index()
    {
        $user = Auth::user();

        // Ujian yang tersedia untuk siswa
        $classIds = $user->classes()->pluck('classes.id');

        $upcomingExams = Exam::where('is_published', true)
            ->where(function ($q) use ($classIds) {
                $q->whereIn('class_id', $classIds)
                  ->orWhereNull('class_id'); // Ujian umum/terbuka
            })
            ->latest()
            ->get();

        // Riwayat pengerjaan ujian siswa
        $pastAttempts = ExamAttempt::with('exam')
            ->where('student_id', $user->id)
            ->latest('submitted_at')
            ->get();

        return view('student.ujian.index', compact('upcomingExams', 'pastAttempts'));
    }

    public function start(Exam $exam)
    {
        $user = Auth::user();

        // Cari atau buat attempt baru
        $attempt = ExamAttempt::firstOrCreate(
            [
                'exam_id'    => $exam->id,
                'student_id' => $user->id,
                'status'     => 'in_progress',
            ],
            [
                'attempt_number'     => ExamAttempt::where('exam_id', $exam->id)->where('student_id', $user->id)->count() + 1,
                'started_at'         => now(),
                'max_score'          => 100,
                'unanswered_count'   => $exam->questions()->count(),
            ]
        );

        $questions = $exam->questions()->with('options')->get();

        // Ambil jawaban yang sudah disimpan sebelumnya (jika reload)
        $existingAnswers = ExamAnswer::where('attempt_id', $attempt->id)
            ->pluck('selected_option_id', 'question_id')
            ->toArray();

        return view('student.ujian.start', compact('exam', 'attempt', 'questions', 'existingAnswers'));
    }

    public function saveAnswer(Request $request, Exam $exam)
    {
        $request->validate([
            'attempt_id'  => 'required|exists:exam_attempts,id',
            'question_id' => 'required|exists:questions,id',
            'option_id'   => 'required|exists:question_options,id',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', Auth::id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        $option = QuestionOption::findOrFail($request->option_id);

        ExamAnswer::updateOrCreate(
            [
                'attempt_id'  => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option_id' => $option->id,
                'is_correct'         => $option->is_correct,
                'points_earned'      => $option->is_correct ? 1 : 0,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function finish(Request $request, ExamAttempt $attempt)
    {
        if ($attempt->student_id !== Auth::id()) {
            abort(403);
        }

        if ($attempt->status === 'submitted') {
            return redirect()->route('student.ujian.result', $attempt);
        }

        $exam = $attempt->exam;
        $totalQuestions = $exam->questions()->count();

        $answers = ExamAnswer::where('attempt_id', $attempt->id)->get();
        $correctCount = $answers->where('is_correct', true)->count();
        $wrongCount = $answers->where('is_correct', false)->count();
        $unansweredCount = max(0, $totalQuestions - $answers->count());

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        $isPassed = $score >= $exam->passing_score;

        $timeSpent = now()->diffInSeconds($attempt->started_at ?? now());

        $attempt->update([
            'score'              => $score,
            'max_score'          => 100,
            'correct_count'      => $correctCount,
            'wrong_count'        => $wrongCount,
            'unanswered_count'   => $unansweredCount,
            'is_passed'          => $isPassed,
            'status'             => 'submitted',
            'submitted_at'       => now(),
            'time_spent_seconds' => $timeSpent,
        ]);

        return redirect()->route('student.ujian.result', $attempt)->with('success', 'Ujian berhasil diselesaikan!');
    }

    public function result(ExamAttempt $attempt)
    {
        if ($attempt->student_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $attempt->load(['exam.questions.options', 'answers.selectedOption']);

        return view('student.ujian.result', compact('attempt'));
    }
}
