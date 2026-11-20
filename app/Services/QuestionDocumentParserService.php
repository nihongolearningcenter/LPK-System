<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class QuestionDocumentParserService
{
    /**
     * Supported JLPT levels.
     */
    protected array $validLevels = ['N5', 'N4', 'N3', 'N2', 'N1', 'dasar'];

    /**
     * Supported categories with alias mappings.
     */
    protected array $categoryMap = [
        'kosakata'    => 'kosakata',
        'vocab'       => 'kosakata',
        'vocabulary'  => 'kosakata',
        'moji'        => 'kosakata',
        'goi'         => 'kosakata',
        'tata_bahasa' => 'tata_bahasa',
        'tatabahasa'  => 'tata_bahasa',
        'grammar'     => 'tata_bahasa',
        'bunpou'      => 'tata_bahasa',
        'kanji'       => 'kanji',
        'membaca'     => 'membaca',
        'reading'     => 'membaca',
        'dokkai'      => 'membaca',
        'menyimak'    => 'menyimak',
        'choukai'     => 'menyimak',
        'listening'   => 'menyimak',
    ];

    /**
     * Supported difficulties with alias mappings.
     */
    protected array $difficultyMap = [
        'mudah'  => 'mudah',
        'easy'   => 'mudah',
        'sedang' => 'sedang',
        'medium' => 'sedang',
        'sulit'  => 'sulit',
        'hard'   => 'sulit',
    ];

    /**
     * Parse an uploaded file into an array of question data.
     *
     * @param UploadedFile|string $file File instance or file path
     * @param string|null $extension Override extension if file is a string path
     * @return array Array of parsed questions
     */
    public function parseFile($file, ?string $extension = null): array
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $ext = strtolower($extension ?: ($file instanceof UploadedFile ? $file->getClientOriginalExtension() : pathinfo($path, PATHINFO_EXTENSION)));

        if ($ext === 'csv') {
            return $this->parseCsv($path);
        }

        if ($ext === 'docx') {
            $text = $this->extractTextFromDocx($path);
            return $this->parseText($text);
        }

        if (in_array($ext, ['txt', 'text', 'doc'])) {
            $content = file_get_contents($path);
            return $this->parseText($content);
        }

        throw new \InvalidArgumentException("Format berkas .{$ext} tidak didukung. Gunakan berkas .csv, .docx, atau .txt.");
    }

    /**
     * Parse CSV file or content.
     */
    public function parseCsv(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Berkas CSV tidak ditemukan: {$filePath}");
        }

        $rawContent = file_get_contents($filePath);
        if ($rawContent === false || trim($rawContent) === '') {
            return [];
        }

        // Remove UTF-8 BOM if present
        $bom = pack('H*', 'EFBBBF');
        $rawContent = preg_replace("/^$bom/", '', $rawContent);

        // Detect delimiter: comma, semicolon, or tab
        $firstLine = strtok($rawContent, "\r\n");
        $delimiter = ',';
        $semicolonCount = substr_count($firstLine, ';');
        $commaCount = substr_count($firstLine, ',');
        $tabCount = substr_count($firstLine, "\t");

        if ($semicolonCount > $commaCount && $semicolonCount > $tabCount) {
            $delimiter = ';';
        } elseif ($tabCount > $commaCount && $tabCount > $semicolonCount) {
            $delimiter = "\t";
        }

        // Open memory stream for fgetcsv
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $rawContent);
        rewind($handle);

        $header = null;
        $questions = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row, fn($val) => trim((string)$val) !== ''))) {
                continue;
            }

            if (!$header) {
                $header = array_map(fn($col) => strtolower(trim((string)$col)), $row);
                continue;
            }

            $rowData = [];
            foreach ($header as $index => $colName) {
                $rowData[$colName] = isset($row[$index]) ? trim((string)$row[$index]) : '';
            }

            $parsed = $this->mapCsvRowToQuestion($rowData);
            if ($parsed) {
                $questions[] = $parsed;
            }
        }

        fclose($handle);
        return $questions;
    }

    /**
     * Map a single CSV row to standard question structure.
     */
    protected function mapCsvRowToQuestion(array $row): ?array
    {
        // Find question text
        $questionText = $this->findValueByKeys($row, ['pertanyaan', 'soal', 'question', 'soal_pertanyaan', 'tanya']);
        if (!$questionText) {
            return null;
        }

        // Extract options
        $options = [];
        $optionKeys = [
            'A' => ['pilihan_a', 'opsi_a', 'option_a', 'a', 'pilihan a', 'opsi a'],
            'B' => ['pilihan_b', 'opsi_b', 'option_b', 'b', 'pilihan b', 'opsi b'],
            'C' => ['pilihan_c', 'opsi_c', 'option_c', 'c', 'pilihan c', 'opsi c'],
            'D' => ['pilihan_d', 'opsi_d', 'option_d', 'd', 'pilihan d', 'opsi d'],
            'E' => ['pilihan_e', 'opsi_e', 'option_e', 'e', 'pilihan e', 'opsi e'],
        ];

        $correctAnswer = strtoupper(trim((string)$this->findValueByKeys($row, [
            'kunci_jawaban', 'kunci', 'jawaban', 'correct', 'correct_answer', 'answer', 'key', 'kunci jawaban'
        ])));

        foreach ($optionKeys as $label => $keys) {
            $optText = $this->findValueByKeys($row, $keys);
            if ($optText !== null && $optText !== '') {
                $isCorrect = ($correctAnswer === $label) || (strcasecmp($correctAnswer, $optText) === 0);
                $options[] = [
                    'label'      => $label,
                    'text'       => $optText,
                    'is_correct' => $isCorrect,
                ];
            }
        }

        // If no correct answer matched by label, check if any option marked
        $hasCorrect = array_filter($options, fn($o) => $o['is_correct']);
        if (empty($hasCorrect) && !empty($options)) {
            // Default first option if missing or invalid
            $options[0]['is_correct'] = true;
        }

        // JLPT Level
        $rawLevel = strtoupper(trim((string)$this->findValueByKeys($row, ['level_jlpt', 'level', 'jlpt', 'jlpt_level', 'tingkat'])));
        $jlptLevel = in_array($rawLevel, $this->validLevels) ? $rawLevel : 'N5';

        // Category
        $rawCategory = strtolower(trim((string)$this->findValueByKeys($row, ['kategori', 'category', 'jenis'])));
        $category = $this->categoryMap[$rawCategory] ?? 'kosakata';

        // Difficulty
        $rawDiff = strtolower(trim((string)$this->findValueByKeys($row, ['kesulitan', 'difficulty', 'tingkat_kesulitan'])));
        $difficulty = $this->difficultyMap[$rawDiff] ?? 'sedang';

        // Points
        $rawPoints = $this->findValueByKeys($row, ['poin', 'points', 'nilai', 'skor']);
        $points = is_numeric($rawPoints) && (int)$rawPoints > 0 ? (int)$rawPoints : 1;

        // Explanation
        $explanation = $this->findValueByKeys($row, ['pembahasan', 'penjelasan', 'explanation', 'keterangan']);

        return [
            'question_text' => $questionText,
            'type'          => 'pilihan_ganda',
            'jlpt_level'    => $jlptLevel,
            'category'      => $category,
            'difficulty'    => $difficulty,
            'points'        => $points,
            'explanation'   => $explanation,
            'options'       => $options,
        ];
    }

    /**
     * Extract plain text from .docx file.
     */
    public function extractTextFromDocx(string $filePath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('Berkas Word (.docx) tidak dapat dibuka atau korup.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            throw new \RuntimeException('Isi berkas Word (word/document.xml) tidak ditemukan.');
        }

        // Replace paragraph and table row closings with newlines
        $xml = str_replace(['</w:p>', '</w:tr>'], "\n", $xml);
        // Replace tab elements with spaces
        $xml = str_replace('<w:tab/>', "\t", $xml);
        // Strip XML tags
        $text = strip_tags($xml);
        // Decode XML/HTML entities
        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * Parse structured text (Aiken format, Key-Value format, or standard exam layout).
     */
    public function parseText(string $content): array
    {
        $lines = preg_split("/\r\n|\n|\r/", $content);
        $questions = [];

        $currentQuestion = null;
        $currentOptions = [];
        $currentCorrect = null;
        $currentLevel = 'N5';
        $currentCategory = 'kosakata';
        $currentDifficulty = 'sedang';
        $currentPoints = 1;
        $currentExplanation = null;

        $saveCurrent = function () use (
            &$questions, &$currentQuestion, &$currentOptions, &$currentCorrect,
            &$currentLevel, &$currentCategory, &$currentDifficulty, &$currentPoints, &$currentExplanation
        ) {
            if ($currentQuestion && !empty($currentOptions)) {
                // Ensure at least one correct option
                $hasCorrect = false;
                foreach ($currentOptions as &$opt) {
                    if ($currentCorrect && strtoupper($opt['label']) === strtoupper($currentCorrect)) {
                        $opt['is_correct'] = true;
                        $hasCorrect = true;
                    }
                }
                if (!$hasCorrect && !empty($currentOptions)) {
                    $currentOptions[0]['is_correct'] = true;
                }

                $questions[] = [
                    'question_text' => trim($currentQuestion),
                    'type'          => 'pilihan_ganda',
                    'jlpt_level'    => $currentLevel,
                    'category'      => $currentCategory,
                    'difficulty'    => $currentDifficulty,
                    'points'        => $currentPoints,
                    'explanation'   => $currentExplanation,
                    'options'       => $currentOptions,
                ];
            }

            // Reset
            $currentQuestion = null;
            $currentOptions = [];
            $currentCorrect = null;
            $currentLevel = 'N5';
            $currentCategory = 'kosakata';
            $currentDifficulty = 'sedang';
            $currentPoints = 1;
            $currentExplanation = null;
        };

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || $trimmed === '---' || $trimmed === '***') {
                continue;
            }

            // Check for metadata tags
            if (preg_match('/^(?:kunci(?:\s*jawaban)?|jawaban|answer|key)\s*[:=]\s*([A-Ea-e])/i', $trimmed, $m)) {
                $currentCorrect = strtoupper($m[1]);
                continue;
            }

            if (preg_match('/^(?:level(?:\s*jlpt)?|jlpt)\s*[:=]\s*(N[1-5]|dasar)/i', $trimmed, $m)) {
                $lvl = strtoupper($m[1]);
                if (in_array($lvl, $this->validLevels)) {
                    $currentLevel = $lvl;
                }
                continue;
            }

            if (preg_match('/^(?:kategori|category)\s*[:=]\s*([a-zA-Z_]+)/i', $trimmed, $m)) {
                $cat = strtolower(trim($m[1]));
                $currentCategory = $this->categoryMap[$cat] ?? 'kosakata';
                continue;
            }

            if (preg_match('/^(?:kesulitan|difficulty)\s*[:=]\s*([a-zA-Z]+)/i', $trimmed, $m)) {
                $diff = strtolower(trim($m[1]));
                $currentDifficulty = $this->difficultyMap[$diff] ?? 'sedang';
                continue;
            }

            if (preg_match('/^(?:poin|points|nilai|skor)\s*[:=]\s*(\d+)/i', $trimmed, $m)) {
                $currentPoints = max(1, (int)$m[1]);
                continue;
            }

            if (preg_match('/^(?:pembahasan|penjelasan|explanation)\s*[:=]\s*(.*)$/i', $trimmed, $m)) {
                $currentExplanation = trim($m[1]);
                continue;
            }

            // Check for Option pattern: A. Text, A) Text, [A] Text, A: Text
            if (preg_match('/^(?:[\(\[]?([A-Ea-e])[\)\]\.|\:]|\*([A-Ea-e])[\.\)])\s*(.+)$/u', $trimmed, $m)) {
                $label = strtoupper(!empty($m[1]) ? $m[1] : $m[2]);
                $optText = trim($m[3]);
                $isMarkedCorrect = str_starts_with($trimmed, '*');

                if ($isMarkedCorrect) {
                    $currentCorrect = $label;
                }

                $currentOptions[] = [
                    'label'      => $label,
                    'text'       => $optText,
                    'is_correct' => $isMarkedCorrect,
                ];
                continue;
            }

            // Check for new Question start: "Soal: ...", "Pertanyaan: ...", "Q: ...", or numbered "1. ...", "2) ..."
            if (preg_match('/^(?:soal|pertanyaan|question|q)\s*(?:\d+)?\s*[:\.\-]?\s*(.+)$/iu', $trimmed, $m)) {
                $saveCurrent();
                $currentQuestion = trim($m[1]);
                continue;
            }

            if (preg_match('/^\d+[\.\)]\s*(.+)$/u', $trimmed, $m)) {
                $saveCurrent();
                $currentQuestion = trim($m[1]);
                continue;
            }

            // Continuation of question text or new question
            if ($currentQuestion === null) {
                // If question starts with 1. 2. strip it as well
                $currentQuestion = preg_replace('/^\d+[\.\)]\s*/u', '', $trimmed);
            } elseif (empty($currentOptions)) {
                // If options haven't started yet, append multi-line question text
                $currentQuestion .= "\n" . $trimmed;
            } else {
                // If options have already started and we hit an unknown text, might be next question
                if (count($currentOptions) >= 2 && $currentCorrect !== null) {
                    $saveCurrent();
                    $currentQuestion = preg_replace('/^\d+[\.\)]\s*/u', '', $trimmed);
                }
            }
        }

        $saveCurrent();

        return $questions;
    }

    /**
     * Persist parsed questions into the database.
     *
     * @param array $questions List of parsed question arrays
     * @param int|null $createdBy User ID of creator
     * @param int|null $subjectId Subject ID
     * @return array List of created Question IDs
     */
    public function saveQuestions(array $questions, ?int $createdBy = null, ?int $subjectId = null): array
    {
        if (empty($questions)) {
            return [];
        }

        $createdIds = [];

        DB::transaction(function () use ($questions, $createdBy, $subjectId, &$createdIds) {
            foreach ($questions as $qData) {
                $question = Question::create([
                    'subject_id'    => $subjectId,
                    'created_by'    => $createdBy,
                    'question_text' => $qData['question_text'],
                    'type'          => $qData['type'] ?? 'pilihan_ganda',
                    'jlpt_level'    => $qData['jlpt_level'] ?? 'N5',
                    'category'      => $qData['category'] ?? 'kosakata',
                    'explanation'   => $qData['explanation'] ?? null,
                    'points'        => $qData['points'] ?? 1,
                    'difficulty'    => $qData['difficulty'] ?? 'sedang',
                    'is_active'     => true,
                ]);

                foreach ($qData['options'] as $idx => $opt) {
                    QuestionOption::create([
                        'question_id'  => $question->id,
                        'option_label' => $opt['label'] ?? chr(65 + $idx),
                        'option_text'  => $opt['text'],
                        'is_correct'   => (bool)($opt['is_correct'] ?? false),
                    ]);
                }

                $createdIds[] = $question->id;
            }
        });

        return $createdIds;
    }

    /**
     * Generate template CSV content with UTF-8 BOM.
     */
    public function generateCsvTemplate(): string
    {
        $headers = [
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Kunci Jawaban',
            'Level JLPT',
            'Kategori',
            'Tingkat Kesulitan',
            'Poin',
            'Pembahasan',
        ];

        $samples = [
            [
                'Apa arti dari kata 「猫」(ねこ)?',
                'Anjing',
                'Kucing',
                'Burung',
                'Ikan',
                'B',
                'N5',
                'kosakata',
                'mudah',
                '2',
                '「猫」 dibaca neko yang berarti kucing.',
            ],
            [
                'わたし____田中です。Partikel yang tepat untuk mengisi kalimat tersebut adalah...',
                'は',
                'が',
                'に',
                'で',
                'A',
                'N5',
                'tata_bahasa',
                'mudah',
                '2',
                'Partikel は (wa) digunakan sebagai penanda topik atau subjek kalimat.',
            ],
            [
                'Kanji dari kata 「やま」 (gunung) adalah...',
                '川',
                '山',
                '木',
                '日',
                'B',
                'N5',
                'kanji',
                'mudah',
                '2',
                '山 (yama) berarti gunung, sedangkan 川 (kawa) sungai, 木 (ki) pohon.',
            ],
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        foreach ($samples as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);

        // Add UTF-8 BOM for Excel compatibility
        return "\xEF\xBB\xBF" . $csvData;
    }

    /**
     * Generate sample text template.
     */
    public function generateTextTemplate(): string
    {
        return <<<TXT
# ==========================================
# FORMAT DOKUMEN IMPORT SOAL (LPK NIHONGO)
# ==========================================
# Panduan Penulisan:
# - Soal: Tuliskan teks pertanyaan
# - A / B / C / D: Tuliskan pilihan jawaban
# - Kunci: Huruf pilihan benar (A / B / C / D)
# - Level: N5 / N4 / N3 / N2 / N1 / dasar
# - Kategori: kosakata / tata_bahasa / kanji / membaca / menyimak
# - Kesulitan: mudah / sedang / sulit
# - Poin: Jumlah poin (angka, contoh: 2)
# - Pembahasan: Penjelasan jawaban (opsional)
# ==========================================

Soal: Apa arti dari kata 「猫」(ねこ)?
A. Anjing
B. Kucing
C. Burung
D. Ikan
Kunci: B
Level: N5
Kategori: kosakata
Kesulitan: mudah
Poin: 2
Pembahasan: 「猫」 dibaca neko yang berarti kucing.

Soal: わたし____田中です。Partikel yang tepat untuk mengisi kalimat tersebut adalah...
A. は
B. が
C. に
D. で
Kunci: A
Level: N5
Kategori: tata_bahasa
Kesulitan: mudah
Poin: 2
Pembahasan: Partikel は (wa) digunakan sebagai penanda topik subjek kalimat.

Soal: Kanji dari kata 「やま」 (gunung) adalah...
A. 川
B. 山
C. 木
D. 日
Kunci: B
Level: N5
Kategori: kanji
Kesulitan: mudah
Poin: 2
Pembahasan: 山 (yama) berarti gunung.
TXT;
    }

    /**
     * Generate a minimal valid .docx Word document containing the template.
     */
    public function generateDocxTemplate(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
            . '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
            . '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // Paragraphs of sample text
        $paragraphs = [
            'FORMAT DOKUMEN IMPORT SOAL LPK NIHONGO',
            '',
            'Soal: Apa arti dari kata 「猫」(ねこ)?',
            'A. Anjing',
            'B. Kucing',
            'C. Burung',
            'D. Ikan',
            'Kunci: B',
            'Level: N5',
            'Kategori: kosakata',
            'Kesulitan: mudah',
            'Poin: 2',
            'Pembahasan: 「猫」 dibaca neko yang berarti kucing.',
            '',
            'Soal: わたし____田中です。Partikel yang tepat untuk mengisi kalimat tersebut adalah...',
            'A. は',
            'B. が',
            'C. に',
            'D. で',
            'Kunci: A',
            'Level: N5',
            'Kategori: tata_bahasa',
            'Kesulitan: mudah',
            'Poin: 2',
            'Pembahasan: Partikel は (wa) digunakan sebagai penanda topik subjek kalimat.',
            '',
            'Soal: Kanji dari kata 「やま」 (gunung) adalah...',
            'A. 川',
            'B. 山',
            'C. 木',
            'D. 日',
            'Kunci: B',
            'Level: N5',
            'Kategori: kanji',
            'Kesulitan: mudah',
            'Poin: 2',
            'Pembahasan: 山 (yama) berarti gunung.',
        ];

        $docBody = '';
        foreach ($paragraphs as $p) {
            $escaped = htmlspecialchars($p, ENT_XML1, 'UTF-8');
            $docBody .= "<w:p><w:r><w:t>{$escaped}</w:t></w:r></w:p>";
        }

        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            . '<w:body>'
            . $docBody
            . '</w:body>'
            . '</w:document>';
        $zip->addFromString('word/document.xml', $documentXml);

        $zip->close();
        $binary = file_get_contents($tempFile);
        @unlink($tempFile);

        return $binary;
    }

    /**
     * Find value in an array by trying multiple potential key names.
     */
    protected function findValueByKeys(array $data, array $keys): ?string
    {
        foreach ($keys as $k) {
            if (array_key_exists($k, $data) && $data[$k] !== null && trim((string)$data[$k]) !== '') {
                return trim((string)$data[$k]);
            }
        }
        return null;
    }
}
