<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Certificate;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LpkSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $instructor;
    protected User $student;
    protected Program $program;
    protected ClassRoom $classRoom;
    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic users
        $this->admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $this->instructor = User::factory()->create([
            'role'      => 'instructor',
            'is_active' => true,
        ]);

        $this->student = User::factory()->create([
            'role'      => 'student',
            'is_active' => true,
        ]);

        // Program & Subject & Class
        $this->program = Program::create([
            'name'        => 'Program Bahasa Jepang Reguler N5-N4',
            'slug'        => 'reguler-n5-n4',
            'type'        => 'jlpt_n5',
            'description' => 'Program intensif persiapan kerja dan magang ke Jepang',
            'price'       => 3500000,
            'is_active'   => true,
        ]);

        $this->subject = Subject::create([
            'program_id' => $this->program->id,
            'name'       => 'Tata Bahasa Jepang N5',
            'code'       => 'BUNPO-N5',
            'is_active'  => true,
        ]);

        $this->classRoom = ClassRoom::create([
            'program_id'    => $this->program->id,
            'instructor_id' => $this->instructor->id,
            'name'          => 'Kelas Sakura 2026',
            'code'          => 'SAKURA-01',
            'status'        => 'ongoing',
            'start_date'    => now()->toDateString(),
        ]);

        // Attach student to class
        $this->classRoom->students()->attach($this->student->id, [
            'status'    => 'active',
            'joined_at' => now(),
        ]);

        // Create paid payment for test student
        \App\Models\Payment::create([
            'invoice_number' => 'INV-TEST-0001',
            'student_id'     => $this->student->id,
            'class_id'       => $this->classRoom->id,
            'description'    => 'Biaya Uji Sistem',
            'amount'         => 1500000,
            'amount_paid'    => 1500000,
            'status'         => 'paid',
            'paid_at'        => now(),
        ]);
    }

    public function test_admin_can_access_grading_and_store_grades(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.nilai.index', [
            'class_id'   => $this->classRoom->id,
            'subject_id' => $this->subject->id,
        ]));

        $response->assertStatus(200);

        // Store grade
        $postResponse = $this->actingAs($this->admin)->post(route('admin.nilai.store'), [
            'class_id'   => $this->classRoom->id,
            'subject_id' => $this->subject->id,
            'grades'     => [
                [
                    'student_id'        => $this->student->id,
                    'score_attendance'  => 95,
                    'score_assignments' => 90,
                    'score_midterm'     => 85,
                    'score_final'       => 90,
                    'notes'             => 'Siswa sangat rajin',
                ],
            ],
        ]);

        $postResponse->assertRedirect();

        $this->assertDatabaseHas('grades', [
            'class_id'    => $this->classRoom->id,
            'subject_id'  => $this->subject->id,
            'student_id'  => $this->student->id,
            'grade_letter'=> 'A',
        ]);
    }

    public function test_admin_can_create_class(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.kelas.store'), [
            'program_id'    => $this->program->id,
            'instructor_id' => $this->instructor->id,
            'name'          => 'Kelas N5 Malam B',
            'code'          => 'N5-MLM-02',
            'status'        => 'active',
            'max_students'  => 25,
            'start_date'    => now()->toDateString(),
            'meeting_link'  => 'https://meet.google.com/abc-defg-hij',
            'notes'         => 'Kelas hybrid dengan tatap muka virtual via Meet',
        ]);

        $response->assertRedirect(route('admin.kelas.index'));

        $this->assertDatabaseHas('classes', [
            'name'         => 'Kelas N5 Malam B',
            'code'         => 'N5-MLM-02',
            'status'       => 'active',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ]);
    }

    public function test_admin_can_add_and_remove_student_from_class(): void
    {
        $newStudent = User::factory()->create([
            'role'      => 'student',
            'is_active' => true,
        ]);

        $addResponse = $this->actingAs($this->admin)->post(route('admin.kelas.addStudent', $this->classRoom), [
            'student_id' => $newStudent->id,
        ]);

        $addResponse->assertRedirect();

        $this->assertDatabaseHas('class_students', [
            'class_id'   => $this->classRoom->id,
            'student_id' => $newStudent->id,
            'status'     => 'active',
        ]);

        $removeResponse = $this->actingAs($this->admin)->delete(route('admin.kelas.removeStudent', [$this->classRoom, $newStudent]));
        $removeResponse->assertRedirect();

        $this->assertDatabaseMissing('class_students', [
            'class_id'   => $this->classRoom->id,
            'student_id' => $newStudent->id,
        ]);
    }

    public function test_admin_can_add_and_remove_class_schedule(): void
    {
        $addResponse = $this->actingAs($this->admin)->post(route('admin.kelas.addSchedule', $this->classRoom), [
            'day_of_week' => 'Senin',
            'start_time'  => '08:30',
            'end_time'    => '11:30',
            'room'        => 'Ruang Sakura (Lt. 2)',
            'notes'       => 'Materi Bunpou N5',
        ]);

        $addResponse->assertRedirect();

        $this->assertDatabaseHas('schedules', [
            'class_id'    => $this->classRoom->id,
            'day_of_week' => 'Senin',
            'room'        => 'Ruang Sakura (Lt. 2)',
        ]);

        $schedule = \App\Models\Schedule::where('class_id', $this->classRoom->id)->first();

        // Verify it shows up in Instructor Jadwal page
        $instructorJadwalResponse = $this->actingAs($this->instructor)->get(route('instructor.jadwal'));
        $instructorJadwalResponse->assertStatus(200);
        $instructorJadwalResponse->assertSee('Ruang Sakura (Lt. 2)');

        $removeResponse = $this->actingAs($this->admin)->delete(route('admin.kelas.removeSchedule', [$this->classRoom, $schedule]));
        $removeResponse->assertRedirect();

        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }

    public function test_admin_can_issue_certificate(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.sertifikat.store'), [
            'student_id'         => $this->student->id,
            'certificate_number' => 'CERT/LPK/2026/0001',
            'certificate_name'   => 'Sertifikat Kelulusan Program Bahasa Jepang',
            'class_id'           => $this->classRoom->id,
            'program_id'         => $this->program->id,
            'issue_date'         => now()->toDateString(),
            'final_grade'        => 90.5,
            'grade_letter'       => 'A',
        ]);

        $response->assertRedirect(route('admin.sertifikat.index'));

        $this->assertDatabaseHas('certificates', [
            'certificate_number' => 'CERT/LPK/2026/0001',
            'student_id'         => $this->student->id,
        ]);
    }

    public function test_admin_can_create_assignment(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.tugas.store'), [
            'class_id'              => $this->classRoom->id,
            'subject_id'            => $this->subject->id,
            'title'                 => 'Tugas Percakapan Bab 1',
            'description'           => 'Buat video percakapan salam sapaan',
            'max_score'             => 100,
            'allow_late_submission' => 1,
            'is_published'          => 1,
        ]);

        $response->assertRedirect(route('admin.tugas.index'));

        $this->assertDatabaseHas('assignments', [
            'title'    => 'Tugas Percakapan Bab 1',
            'class_id' => $this->classRoom->id,
        ]);
    }

    public function test_instructor_can_access_dashboard_and_classes(): void
    {
        $activeClass = ClassRoom::create([
            'program_id'    => $this->program->id,
            'instructor_id' => $this->instructor->id,
            'name'          => 'Kelas Aktif Sensei',
            'code'          => 'AKTIF-01',
            'status'        => 'active',
            'start_date'    => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->instructor)->get(route('instructor.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Kelas Aktif Sensei');

        $classResponse = $this->actingAs($this->instructor)->get(route('instructor.kelas.show', $this->classRoom));
        $classResponse->assertStatus(200);
    }

    public function test_instructor_can_manage_lessons(): void
    {
        $response = $this->actingAs($this->instructor)->get(route('instructor.materi.index'));
        $response->assertStatus(200);

        $createResponse = $this->actingAs($this->instructor)->post(route('instructor.materi.store'), [
            'subject_id'       => $this->subject->id,
            'class_id'         => $this->classRoom->id,
            'title'            => 'Pelajaran Partikel Wa dan Ga',
            'description'      => 'Penjelasan perbedaan pemakaian partikel wa dan ga',
            'content'          => 'Partikel WA digunakan sebagai penanda topik, sedangkan GA sebagai subjek fokus.',
            'type'             => 'text',
            'duration_minutes' => 45,
            'is_published'     => 1,
        ]);

        $createResponse->assertRedirect(route('instructor.materi.index'));

        $this->assertDatabaseHas('lessons', [
            'title'      => 'Pelajaran Partikel Wa dan Ga',
            'created_by' => $this->instructor->id,
            'class_id'   => $this->classRoom->id,
        ]);
    }

    public function test_instructor_can_create_exam_with_questions_and_manage_questions(): void
    {
        $question1 = \App\Models\Question::create([
            'question_text' => 'Soal Tes 1: Apa arti Ohayou?',
            'category'      => 'kosakata',
            'jlpt_level'    => 'N5',
            'points'        => 5,
            'is_active'     => true,
            'created_by'    => $this->instructor->id,
        ]);

        $question2 = \App\Models\Question::create([
            'question_text' => 'Soal Tes 2: Apa arti Konnichiwa?',
            'category'      => 'kosakata',
            'jlpt_level'    => 'N5',
            'points'        => 5,
            'is_active'     => true,
            'created_by'    => $this->instructor->id,
        ]);

        // 1. Instructor can render create page and see questions
        $createPageResponse = $this->actingAs($this->instructor)->get(route('instructor.ujian.create'));
        $createPageResponse->assertStatus(200);
        $createPageResponse->assertSee('Soal Tes 1: Apa arti Ohayou?');

        // 2. Instructor can create exam with selected question1
        $storeResponse = $this->actingAs($this->instructor)->post(route('instructor.ujian.store'), [
            'title'            => 'Ujian Tengah Semester N5',
            'type'             => 'ujian_tengah',
            'jlpt_level'       => 'N5',
            'class_id'         => $this->classRoom->id,
            'subject_id'       => $this->subject->id,
            'duration_minutes' => 60,
            'passing_score'    => 70,
            'is_published'     => 1,
            'question_ids'     => [$question1->id],
        ]);

        $storeResponse->assertRedirect();

        $exam = \App\Models\Exam::where('title', 'Ujian Tengah Semester N5')->first();
        $this->assertNotNull($exam);
        $this->assertEquals(1, $exam->questions()->count());

        // 3. Instructor can attach question2 via show page modal
        $addQResponse = $this->actingAs($this->instructor)->post(route('instructor.ujian.addQuestion', $exam), [
            'question_id' => $question2->id,
        ]);
        $addQResponse->assertRedirect();
        $this->assertEquals(2, $exam->fresh()->questions()->count());

        // 4. Instructor can remove question1
        $removeQResponse = $this->actingAs($this->instructor)->delete(route('instructor.ujian.removeQuestion', [$exam, $question1]));
        $removeQResponse->assertRedirect();
        $this->assertEquals(1, $exam->fresh()->questions()->count());
    }

    public function test_instructor_can_download_question_templates(): void
    {
        $csvResponse = $this->actingAs($this->instructor)->get(route('instructor.soal.template', 'csv'));
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('Content-Disposition', 'attachment; filename="template_soal_lpk.csv"');

        $docxResponse = $this->actingAs($this->instructor)->get(route('instructor.soal.template', 'docx'));
        $docxResponse->assertStatus(200);
        $docxResponse->assertHeader('Content-Disposition', 'attachment; filename="template_soal_lpk.docx"');

        $txtResponse = $this->actingAs($this->instructor)->get(route('instructor.soal.template', 'txt'));
        $txtResponse->assertStatus(200);
        $txtResponse->assertHeader('Content-Disposition', 'attachment; filename="template_soal_lpk.txt"');
    }

    public function test_instructor_can_import_questions_from_csv_document(): void
    {
        $parser = new \App\Services\QuestionDocumentParserService();
        $csvData = $parser->generateCsvTemplate();

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('soal_n5.csv', $csvData);

        $response = $this->actingAs($this->instructor)->post(route('instructor.soal.import'), [
            'document'   => $file,
            'subject_id' => $this->subject->id,
        ]);

        $response->assertRedirect(route('instructor.soal.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'question_text' => 'Apa arti dari kata 「猫」(ねこ)?',
            'created_by'    => $this->instructor->id,
            'subject_id'    => $this->subject->id,
        ]);
    }

    public function test_instructor_can_import_questions_from_docx_document(): void
    {
        $parser = new \App\Services\QuestionDocumentParserService();
        $docxBinary = $parser->generateDocxTemplate();

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('soal_n5.docx', $docxBinary);

        $response = $this->actingAs($this->instructor)->post(route('instructor.soal.import'), [
            'document' => $file,
        ]);

        $response->assertRedirect(route('instructor.soal.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('questions', [
            'question_text' => 'Apa arti dari kata 「猫」(ねこ)?',
            'created_by'    => $this->instructor->id,
        ]);
    }

    public function test_instructor_can_import_questions_directly_into_exam(): void
    {
        $exam = \App\Models\Exam::create([
            'title'            => 'Ujian Kosakata N5',
            'type'             => 'latihan',
            'jlpt_level'       => 'N5',
            'duration_minutes' => 45,
            'passing_score'    => 70,
            'created_by'       => $this->instructor->id,
        ]);

        $parser = new \App\Services\QuestionDocumentParserService();
        $csvData = $parser->generateCsvTemplate();
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('soal_exam.csv', $csvData);

        $response = $this->actingAs($this->instructor)->post(route('instructor.ujian.importQuestions', $exam), [
            'document' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(3, $exam->fresh()->questions()->count());
    }

    public function test_instructor_can_create_exam_with_document_upload(): void
    {
        $parser = new \App\Services\QuestionDocumentParserService();
        $txtData = $parser->generateTextTemplate();
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('soal_create.txt', $txtData);

        $response = $this->actingAs($this->instructor)->post(route('instructor.ujian.store'), [
            'title'            => 'Ujian Bersama File Dokumen',
            'type'             => 'latihan',
            'jlpt_level'       => 'N5',
            'duration_minutes' => 60,
            'passing_score'    => 70,
            'document'         => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $exam = \App\Models\Exam::where('title', 'Ujian Bersama File Dokumen')->first();
        $this->assertNotNull($exam);
        $this->assertEquals(3, $exam->questions()->count());
    }

    public function test_instructor_can_record_attendance(): void
    {
        $response = $this->actingAs($this->instructor)->post(route('instructor.absensi.store', $this->classRoom), [
            'date'       => now()->toDateString(),
            'attendance' => [
                $this->student->id => 'present',
            ],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance', [
            'class_id'   => $this->classRoom->id,
            'student_id' => $this->student->id,
            'status'     => 'hadir',
        ]);
    }

    public function test_student_can_view_japanese_modules(): void
    {
        $hiraganaResponse = $this->actingAs($this->student)->get(route('student.hiragana-katakana'));
        $hiraganaResponse->assertStatus(200);

        $kanjiResponse = $this->actingAs($this->student)->get(route('student.kanji'));
        $kanjiResponse->assertStatus(200);

        $kosakataResponse = $this->actingAs($this->student)->get(route('student.kosakata.index'));
        $kosakataResponse->assertStatus(200);
    }

    public function test_admin_can_create_and_manage_payments(): void
    {
        // 1. Can view index and create form
        $this->actingAs($this->admin)->get(route('admin.pembayaran.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.pembayaran.create'))->assertStatus(200);

        // 2. Can store payment
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.pembayaran.store'), [
            'student_id'     => $this->student->id,
            'class_id'       => $this->classRoom->id,
            'description'    => 'Biaya Program N5 Intensif',
            'amount'         => 2500000,
            'due_date'       => now()->addWeeks(2)->toDateString(),
            'payment_method' => 'transfer',
            'status'         => 'unpaid',
        ]);

        $storeResponse->assertRedirect(route('admin.pembayaran.index'));
        $storeResponse->assertSessionHas('success');

        $payment = \App\Models\Payment::where('student_id', $this->student->id)
            ->where('description', 'Biaya Program N5 Intensif')
            ->first();

        $this->assertNotNull($payment);
        $this->assertNotEmpty($payment->invoice_number);
        $this->assertStringStartsWith('INV-', $payment->invoice_number);
        $this->assertEquals(2500000, $payment->amount);
        $this->assertEquals('unpaid', $payment->status);

        // 3. Can view detail
        $this->actingAs($this->admin)->get(route('admin.pembayaran.show', $payment))->assertStatus(200);

        // 4. Can update status to paid
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.pembayaran.update', $payment), [
            'status' => 'paid',
            'notes'  => 'Pelunasan transfer via BCA',
        ]);

        $updateResponse->assertRedirect();
        $this->assertEquals('paid', $payment->fresh()->status);
        $this->assertEquals(2500000, $payment->fresh()->amount_paid);
    }

    public function test_storage_link_and_fallback_route_serves_uploaded_files(): void
    {
        // Create a dummy file in storage/app/public/payments/test.jpg
        $testDir = storage_path('app/public/payments');
        if (!file_exists($testDir)) {
            mkdir($testDir, 0755, true);
        }
        $testFile = $testDir . '/test_sample.jpg';
        file_put_contents($testFile, 'fake-image-content');

        // Test GET via storage fallback route
        $this->withoutExceptionHandling();
        try {
            $response = $this->get('/storage/payments/test_sample.jpg');
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
        $response->assertStatus(200);

        // Clean up
        @unlink($testFile);
    }

    public function test_student_without_program_or_payment_cannot_see_learning_menu(): void
    {
        $newStudent = User::factory()->create([
            'role'      => 'student',
            'is_active' => true,
        ]);

        $this->assertFalse($newStudent->hasEnrolledProgram());
        $this->assertFalse($newStudent->hasPaidProgram());
        $this->assertFalse($newStudent->canAccessLearningFeatures());

        // 1. Dashboard does not show learning menus in sidebar
        $response = $this->actingAs($newStudent)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertDontSee(route('student.jadwal'));
        $response->assertDontSee(route('student.materi.index'));
        $response->assertDontSee(route('student.progres'));
        $response->assertDontSee(route('student.ujian.index'));
        $response->assertSee('Akses Pembelajaran Belum Aktif');

        // 2. Direct route access is redirected to dashboard
        $materiResponse = $this->actingAs($newStudent)->get(route('student.materi.index'));
        $materiResponse->assertRedirect(route('student.dashboard'));
        $materiResponse->assertSessionHas('error');
    }

    public function test_student_with_unpaid_payment_cannot_access_learning_features(): void
    {
        $unpaidStudent = User::factory()->create([
            'role'      => 'student',
            'is_active' => true,
        ]);
        $this->classRoom->students()->attach($unpaidStudent->id, [
            'status'    => 'active',
            'joined_at' => now(),
        ]);
        \App\Models\Payment::create([
            'invoice_number' => 'INV-UNPAID-001',
            'student_id'     => $unpaidStudent->id,
            'class_id'       => $this->classRoom->id,
            'description'    => 'Biaya Program Belum Lunas',
            'amount'         => 2000000,
            'amount_paid'    => 0,
            'status'         => 'unpaid',
        ]);

        $this->assertTrue($unpaidStudent->hasEnrolledProgram());
        $this->assertFalse($unpaidStudent->hasPaidProgram());
        $this->assertFalse($unpaidStudent->canAccessLearningFeatures());

        // Sidebar hides learning menu
        $response = $this->actingAs($unpaidStudent)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertDontSee(route('student.jadwal'));
        $response->assertDontSee(route('student.materi.index'));
        $response->assertDontSee(route('student.progres'));
        $response->assertSee('Tagihan Menunggu Pembayaran:');

        // Direct route access is blocked
        $jadwalResponse = $this->actingAs($unpaidStudent)->get(route('student.jadwal'));
        $jadwalResponse->assertRedirect(route('student.dashboard'));
        $jadwalResponse->assertSessionHas('error');
    }

    public function test_student_with_active_class_and_paid_payment_can_access_learning_features(): void
    {
        $this->assertTrue($this->student->hasEnrolledProgram());
        $this->assertTrue($this->student->hasPaidProgram());
        $this->assertTrue($this->student->canAccessLearningFeatures());

        $response = $this->actingAs($this->student)->get(route('student.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Jadwal Belajar');
        $response->assertSee('Materi');
        $response->assertSee('Progres');
        $response->assertSee('Belajar Sekarang');

        // Direct access is allowed
        $this->actingAs($this->student)->get(route('student.materi.index'))->assertStatus(200);
        $this->actingAs($this->student)->get(route('student.jadwal'))->assertStatus(200);
        $this->actingAs($this->student)->get(route('student.progres'))->assertStatus(200);
    }

    public function test_online_enrollment_creates_enrollment_and_payment_bill(): void
    {
        $response = $this->post(route('enrollment.store'), [
            'full_name'           => 'Budi Santoso',
            'email'               => 'budi.santoso@example.com',
            'phone'               => '081234567890',
            'birth_date'          => '2000-05-15',
            'gender'              => 'L',
            'education_level'     => 'SMA/SMK',
            'program_id'          => $this->program->id,
            'class_id'            => $this->classRoom->id,
            'japanese_experience' => 'Belum pernah belajar',
            'schedule_preference' => 'Pagi (07.00-12.00)',
            'notes'               => 'Saya ingin belajar intensif.',
        ]);

        $enrollment = \App\Models\Enrollment::where('email', 'budi.santoso@example.com')->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals($this->program->id, $enrollment->program_id);
        $this->assertEquals($this->classRoom->id, $enrollment->class_id);

        $response->assertRedirect(route('enrollment.success', $enrollment->registration_number));

        // User account created with is_active = false (waiting admin verification)
        $user = \App\Models\User::where('email', 'budi.santoso@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('student', $user->role);
        $this->assertFalse($user->is_active); // Must NOT be active before admin verification
        $this->assertEquals($user->id, $enrollment->user_id);
        $this->assertGuest(); // Must NOT auto login

        // Payment invoice created corresponding to class & program price
        $payment = \App\Models\Payment::where('enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals($this->classRoom->id, $payment->class_id);
        $this->assertEquals($this->program->price, $payment->amount);
        $this->assertEquals('unpaid', $payment->status);
        $this->assertStringStartsWith('INV-', $payment->invoice_number);

        // Success page renders registration details and payment invoice without credentials
        $successResponse = $this->get(route('enrollment.success', $enrollment->registration_number));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($enrollment->registration_number);
        $successResponse->assertSee($payment->invoice_number);
        $successResponse->assertSee('Tagihan Pembayaran');
        $successResponse->assertSee('Menunggu Verifikasi Admin');
        $successResponse->assertDontSee('Akun Siswa Baru Telah Dibuat');
        $successResponse->assertSee(number_format($payment->amount, 0, ',', '.'));
        $successResponse->assertSee('Bukti Transfer / Pembayaran');

        // Calon siswa uploads payment proof directly from success page
        \Illuminate\Support\Facades\Storage::fake('public');
        $fakeFile = \Illuminate\Http\UploadedFile::fake()->create('bukti_transfer.jpg', 150, 'image/jpeg');

        $uploadResponse = $this->post(route('enrollment.uploadProof', $enrollment->registration_number), [
            'payment_proof'  => $fakeFile,
            'payment_method' => 'transfer',
            'sender_name'    => 'Budi Santoso',
            'notes'          => 'Transfer via BCA',
        ]);

        $uploadResponse->assertRedirect(route('enrollment.success', $enrollment->registration_number));
        $uploadResponse->assertSessionHas('success');

        $payment->refresh();
        $this->assertNotNull($payment->payment_proof);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($payment->payment_proof);

        // Success page shows uploaded proof
        $updatedSuccess = $this->get(route('enrollment.success', $enrollment->registration_number));
        $updatedSuccess->assertSee('Bukti Pembayaran Terlampir');
        $updatedSuccess->assertSee('Sudah Diunggah');

        // Admin verifies & accepts enrollment -> activates user account
        $acceptResponse = $this->actingAs($this->admin)->patch(route('admin.pendaftaran.accept', $enrollment));
        $acceptResponse->assertRedirect();
        $this->assertTrue($user->fresh()->is_active); // Account is now active after verification!
    }
}
