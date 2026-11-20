<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use App\Services\QuestionDocumentParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class QuestionDocumentParserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected QuestionDocumentParserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QuestionDocumentParserService();
    }

    public function test_can_generate_and_parse_csv_template(): void
    {
        $csvContent = $this->service->generateCsvTemplate();
        $this->assertNotEmpty($csvContent);

        $temp = tempnam(sys_get_temp_dir(), 'csv_test_');
        file_put_contents($temp, $csvContent);

        $parsed = $this->service->parseCsv($temp);
        @unlink($temp);

        $this->assertCount(3, $parsed);
        $this->assertEquals('Apa arti dari kata 「猫」(ねこ)?', $parsed[0]['question_text']);
        $this->assertEquals('N5', $parsed[0]['jlpt_level']);
        $this->assertEquals('kosakata', $parsed[0]['category']);
        $this->assertCount(4, $parsed[0]['options']);
        $this->assertTrue($parsed[0]['options'][1]['is_correct']); // B is Kucing
        $this->assertEquals('Kucing', $parsed[0]['options'][1]['text']);
    }

    public function test_can_parse_text_format(): void
    {
        $text = <<<TXT
Soal: Terjemahan kata "Watashi" adalah...
A. Saya
B. Kamu
C. Dia
D. Mereka
Kunci: A
Level: N5
Kategori: kosakata
Poin: 3
Pembahasan: Watashi artinya saya.

1. Sensei artinya...
A. Murid
B. Dokter
C. Guru
D. Pilot
ANSWER: C
TXT;

        $parsed = $this->service->parseText($text);
        $this->assertCount(2, $parsed);

        $this->assertEquals('Terjemahan kata "Watashi" adalah...', $parsed[0]['question_text']);
        $this->assertEquals('A', $parsed[0]['options'][0]['label']);
        $this->assertTrue($parsed[0]['options'][0]['is_correct']);
        $this->assertEquals(3, $parsed[0]['points']);
        $this->assertEquals('Watashi artinya saya.', $parsed[0]['explanation']);

        $this->assertEquals('Sensei artinya...', $parsed[1]['question_text']);
        $this->assertTrue($parsed[1]['options'][2]['is_correct']); // C is correct
    }

    public function test_can_parse_japanese_kanji_and_hiragana(): void
    {
        $text = <<<TXT
Soal: 「食べる」の読み方は何ですか？
A. のむ
B. たべる
C. みる
D. きく
Kunci: B
Level: N5
Kategori: kanji
Poin: 2
Pembahasan: 「食べる」 dibaca taberu artinya makan.
TXT;

        $parsed = $this->service->parseText($text);
        $this->assertCount(1, $parsed);
        $this->assertEquals('「食べる」の読み方は何ですか？', $parsed[0]['question_text']);
        $this->assertEquals('たべる', $parsed[0]['options'][1]['text']);
        $this->assertTrue($parsed[0]['options'][1]['is_correct']);
        $this->assertEquals('kanji', $parsed[0]['category']);
    }

    public function test_can_generate_and_parse_docx_template(): void
    {
        $docxBinary = $this->service->generateDocxTemplate();
        $this->assertNotEmpty($docxBinary);

        $temp = tempnam(sys_get_temp_dir(), 'docx_test_') . '.docx';
        file_put_contents($temp, $docxBinary);

        $parsed = $this->service->parseFile($temp, 'docx');
        @unlink($temp);

        $this->assertCount(3, $parsed);
        $this->assertEquals('Apa arti dari kata 「猫」(ねこ)?', $parsed[0]['question_text']);
        $this->assertTrue($parsed[0]['options'][1]['is_correct']); // B is correct
    }

    public function test_can_save_parsed_questions_to_database(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);

        $parsed = [
            [
                'question_text' => 'Apa lawan kata dari "Samui" (dingin)?',
                'type'          => 'pilihan_ganda',
                'jlpt_level'    => 'N5',
                'category'      => 'kosakata',
                'difficulty'    => 'mudah',
                'points'        => 2,
                'explanation'   => 'Atsui artinya panas.',
                'options'       => [
                    ['label' => 'A', 'text' => 'Atsui', 'is_correct' => true],
                    ['label' => 'B', 'text' => 'Takai', 'is_correct' => false],
                    ['label' => 'C', 'text' => 'Yasui', 'is_correct' => false],
                    ['label' => 'D', 'text' => 'Nagai', 'is_correct' => false],
                ],
            ]
        ];

        $ids = $this->service->saveQuestions($parsed, $user->id, null);
        $this->assertCount(1, $ids);

        $question = Question::find($ids[0]);
        $this->assertNotNull($question);
        $this->assertEquals('Apa lawan kata dari "Samui" (dingin)?', $question->question_text);
        $this->assertEquals($user->id, $question->created_by);
        $this->assertCount(4, $question->options);
        $this->assertTrue($question->options()->where('option_label', 'A')->first()->is_correct);
    }
}
