<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Website Publik ─────────────────────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
Route::get('/tentang', [\App\Http\Controllers\Public\HomeController::class, 'tentang'])->name('tentang');
Route::get('/program', [\App\Http\Controllers\Public\ProgramController::class, 'index'])->name('program.index');
Route::get('/program/{slug}', [\App\Http\Controllers\Public\ProgramController::class, 'show'])->name('program.show');
Route::get('/pengajar', [\App\Http\Controllers\Public\HomeController::class, 'pengajar'])->name('pengajar');
Route::get('/artikel', [\App\Http\Controllers\Public\ArtikelController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [\App\Http\Controllers\Public\ArtikelController::class, 'show'])->name('artikel.show');
Route::get('/faq', [\App\Http\Controllers\Public\HomeController::class, 'faq'])->name('faq');
Route::get('/kontak', [\App\Http\Controllers\Public\HomeController::class, 'kontak'])->name('kontak');

// Pendaftaran Online
Route::get('/daftar', [\App\Http\Controllers\Public\EnrollmentController::class, 'create'])->name('enrollment.create');
Route::post('/daftar', [\App\Http\Controllers\Public\EnrollmentController::class, 'store'])->name('enrollment.store');
Route::get('/daftar/sukses/{registration_number}', [\App\Http\Controllers\Public\EnrollmentController::class, 'success'])->name('enrollment.success');
Route::post('/daftar/sukses/{registration_number}/upload-bukti', [\App\Http\Controllers\Public\EnrollmentController::class, 'uploadPaymentProof'])->name('enrollment.uploadProof');

// ─── Autentikasi (Laravel Breeze) ───────────────────────────────────────────
require __DIR__.'/auth.php';

// Route Dashboard universal (redirect sesuai role pengguna)
Route::get('/dashboard', function () {
    return match(auth()->user()?->role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'instructor' => redirect()->route('instructor.dashboard'),
        'student'    => redirect()->route('student.dashboard'),
        default      => redirect('/'),
    };
})->middleware('auth')->name('dashboard');

// ─── Dashboard Admin ─────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Data Pengguna
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('pengajar', \App\Http\Controllers\Admin\InstructorController::class);
    Route::resource('siswa', \App\Http\Controllers\Admin\StudentController::class);

    // Program & Kelas
    Route::resource('program', \App\Http\Controllers\Admin\ProgramController::class);
    Route::resource('kelas', \App\Http\Controllers\Admin\ClassController::class)->parameters(['kelas' => 'kelas']);
    Route::post('kelas/{kelas}/siswa', [\App\Http\Controllers\Admin\ClassController::class, 'addStudent'])->name('kelas.addStudent');
    Route::delete('kelas/{kelas}/siswa/{student}', [\App\Http\Controllers\Admin\ClassController::class, 'removeStudent'])->name('kelas.removeStudent');
    Route::post('kelas/{kelas}/jadwal', [\App\Http\Controllers\Admin\ClassController::class, 'addSchedule'])->name('kelas.addSchedule');
    Route::delete('kelas/{kelas}/jadwal/{schedule}', [\App\Http\Controllers\Admin\ClassController::class, 'removeSchedule'])->name('kelas.removeSchedule');

    // Pendaftaran
    Route::resource('pendaftaran', \App\Http\Controllers\Admin\EnrollmentController::class)->only(['index', 'show', 'update']);
    Route::patch('pendaftaran/{pendaftaran}/terima', [\App\Http\Controllers\Admin\EnrollmentController::class, 'accept'])->name('pendaftaran.accept');
    Route::patch('pendaftaran/{pendaftaran}/tolak', [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])->name('pendaftaran.reject');

    // Materi & Bank Soal
    Route::resource('mata-pelajaran', \App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('materi', \App\Http\Controllers\Admin\LessonController::class);
    Route::resource('kosakata', \App\Http\Controllers\Admin\VocabularyController::class);
    Route::get('bank-soal/template/{format}', [\App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('bank-soal.template');
    Route::post('bank-soal/import', [\App\Http\Controllers\Admin\QuestionController::class, 'import'])->name('bank-soal.import');
    Route::resource('bank-soal', \App\Http\Controllers\Admin\QuestionController::class);

    // Ujian Online
    Route::resource('ujian', \App\Http\Controllers\Admin\ExamController::class);
    Route::post('ujian/{ujian}/soal', [\App\Http\Controllers\Admin\ExamController::class, 'addQuestion'])->name('ujian.addQuestion');
    Route::delete('ujian/{ujian}/soal/{question}', [\App\Http\Controllers\Admin\ExamController::class, 'removeQuestion'])->name('ujian.removeQuestion');
    Route::post('ujian/{ujian}/import-soal', [\App\Http\Controllers\Admin\ExamController::class, 'importQuestions'])->name('ujian.importQuestions');

    // Tugas
    Route::resource('tugas', \App\Http\Controllers\Admin\AssignmentController::class);
    Route::post('tugas/{tugas}/nilai/{submission}', [\App\Http\Controllers\Admin\AssignmentController::class, 'gradeSubmission'])->name('tugas.gradeSubmission');

    // Absensi
    Route::get('absensi', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('absensi', [\App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('absensi.store');

    // Penilaian
    Route::resource('nilai', \App\Http\Controllers\Admin\GradeController::class);

    // Pembayaran
    Route::resource('pembayaran', \App\Http\Controllers\Admin\PaymentController::class);

    // Sertifikat
    Route::resource('sertifikat', \App\Http\Controllers\Admin\CertificateController::class);

    // Artikel & Pengumuman
    Route::resource('pengumuman', \App\Http\Controllers\Admin\AnnouncementController::class);

    // Laporan
    Route::get('laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('laporan.index');
    Route::get('laporan/siswa', [\App\Http\Controllers\Admin\ReportController::class, 'students'])->name('laporan.students');
    Route::get('laporan/keuangan', [\App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('laporan.financial');
    Route::get('laporan/akademik', [\App\Http\Controllers\Admin\ReportController::class, 'academic'])->name('laporan.academic');

    // Pengaturan
    Route::get('pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('pengaturan.update');
});

// ─── Dashboard Pengajar ───────────────────────────────────────────────────────
Route::prefix('pengajar')->name('instructor.')->middleware(['auth', 'instructor'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [\App\Http\Controllers\Instructor\DashboardController::class, 'jadwal'])->name('jadwal');
    Route::get('/kelas', [\App\Http\Controllers\Instructor\ClassController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/{kelas}', [\App\Http\Controllers\Instructor\ClassController::class, 'show'])->name('kelas.show');
    Route::get('/kelas/{kelas}/siswa', [\App\Http\Controllers\Instructor\ClassController::class, 'students'])->name('kelas.students');

    // Absensi
    Route::get('/absensi/{kelas}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/{kelas}', [\App\Http\Controllers\Instructor\AttendanceController::class, 'store'])->name('absensi.store');

    // Materi
    Route::resource('materi', \App\Http\Controllers\Instructor\LessonController::class);

    // Tugas
    Route::resource('tugas', \App\Http\Controllers\Instructor\AssignmentController::class);
    Route::get('tugas/{tugas}/pengumpulan', [\App\Http\Controllers\Instructor\AssignmentController::class, 'submissions'])->name('tugas.submissions');
    Route::post('tugas/{tugas}/nilai/{submission}', [\App\Http\Controllers\Instructor\AssignmentController::class, 'grade'])->name('tugas.grade');

    // Latihan Soal
    Route::get('soal/template/{format}', [\App\Http\Controllers\Instructor\QuestionController::class, 'downloadTemplate'])->name('soal.template');
    Route::post('soal/import', [\App\Http\Controllers\Instructor\QuestionController::class, 'import'])->name('soal.import');
    Route::resource('soal', \App\Http\Controllers\Instructor\QuestionController::class);

    // Ujian
    Route::resource('ujian', \App\Http\Controllers\Instructor\ExamController::class);
    Route::post('ujian/{ujian}/soal', [\App\Http\Controllers\Instructor\ExamController::class, 'addQuestion'])->name('ujian.addQuestion');
    Route::delete('ujian/{ujian}/soal/{question}', [\App\Http\Controllers\Instructor\ExamController::class, 'removeQuestion'])->name('ujian.removeQuestion');
    Route::post('ujian/{ujian}/import-soal', [\App\Http\Controllers\Instructor\ExamController::class, 'importQuestions'])->name('ujian.importQuestions');

    // Nilai
    Route::get('nilai', function () {
        $firstClass = \App\Models\ClassRoom::where('instructor_id', auth()->id())->first();
        return $firstClass
            ? redirect()->route('instructor.nilai.index', $firstClass)
            : redirect()->route('instructor.kelas.index')->with('error', 'Anda belum memiliki kelas yang ditugaskan.');
    })->name('nilai');
    Route::get('nilai/{kelas}', [\App\Http\Controllers\Instructor\GradeController::class, 'index'])->name('nilai.index');
    Route::post('nilai/{kelas}', [\App\Http\Controllers\Instructor\GradeController::class, 'store'])->name('nilai.store');

    // Progres Siswa
    Route::get('progres/{kelas}', [\App\Http\Controllers\Instructor\ProgressController::class, 'index'])->name('progres.index');
    Route::get('progres/{kelas}/siswa/{student}', [\App\Http\Controllers\Instructor\ProgressController::class, 'show'])->name('progres.show');
});

// ─── Dashboard Siswa ──────────────────────────────────────────────────────────
Route::prefix('siswa')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('profil.edit');
    Route::post('/profil', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profil.update');

    // Program Saya
    Route::get('/program-saya', [\App\Http\Controllers\Student\DashboardController::class, 'program'])->name('program');

    // ─── Fitur Pembelajaran (Wajib Aktif Program & Lunas Pembayaran) ─────────
    Route::middleware('student.active')->group(function () {
        // Jadwal
        Route::get('/jadwal', [\App\Http\Controllers\Student\DashboardController::class, 'jadwal'])->name('jadwal');

        // Materi Pembelajaran
        Route::get('/materi', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('materi.index');
        Route::get('/materi/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('materi.show');

        // Modul Bahasa Jepang
        Route::get('/hiragana-katakana', [\App\Http\Controllers\Student\JapaneseController::class, 'hiraganaKatakana'])->name('hiragana-katakana');
        Route::get('/kanji', [\App\Http\Controllers\Student\JapaneseController::class, 'kanji'])->name('kanji');
        Route::get('/kosakata', [\App\Http\Controllers\Student\VocabularyController::class, 'index'])->name('kosakata.index');
        Route::get('/kosakata/flashcard', [\App\Http\Controllers\Student\VocabularyController::class, 'flashcard'])->name('kosakata.flashcard');

        // Latihan JLPT
        Route::get('/latihan-jlpt', [\App\Http\Controllers\Student\ExamController::class, 'jlptIndex'])->name('jlpt.index');
        Route::get('/latihan-jlpt/{exam}', [\App\Http\Controllers\Student\ExamController::class, 'jlptShow'])->name('jlpt.show');

        // Ujian Online
        Route::get('/ujian', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('ujian.index');
        Route::get('/ujian/{exam}/mulai', [\App\Http\Controllers\Student\ExamController::class, 'start'])->name('ujian.start');
        Route::post('/ujian/{exam}/simpan-jawaban', [\App\Http\Controllers\Student\ExamController::class, 'saveAnswer'])->name('ujian.saveAnswer');
        Route::post('/ujian/{attempt}/selesai', [\App\Http\Controllers\Student\ExamController::class, 'finish'])->name('ujian.finish');
        Route::get('/ujian/{attempt}/hasil', [\App\Http\Controllers\Student\ExamController::class, 'result'])->name('ujian.result');

        // Tugas
        Route::get('/tugas', [\App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{assignment}', [\App\Http\Controllers\Student\AssignmentController::class, 'show'])->name('tugas.show');
        Route::post('/tugas/{assignment}/kumpul', [\App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('tugas.submit');

        // Hasil & Progres
        Route::get('/hasil-belajar', [\App\Http\Controllers\Student\ProgressController::class, 'index'])->name('hasil.index');
        Route::get('/progres', [\App\Http\Controllers\Student\ProgressController::class, 'progress'])->name('progres');
    });

    // Pembayaran
    Route::get('/pembayaran', [\App\Http\Controllers\Student\PaymentController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{payment}', [\App\Http\Controllers\Student\PaymentController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{payment}/konfirmasi', [\App\Http\Controllers\Student\PaymentController::class, 'confirm'])->name('pembayaran.confirm');

    // Pengumuman
    Route::get('/pengumuman', [\App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('pengumuman.index');

    // Sertifikat
    Route::get('/sertifikat', [\App\Http\Controllers\Student\CertificateController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{certificate}/unduh', [\App\Http\Controllers\Student\CertificateController::class, 'download'])->name('sertifikat.download');
});

// ─── Profil Akun (semua role) ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Storage Fallback (XAMPP / Windows symlink safety) ───────────────────────
Route::get('storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.fallback');
