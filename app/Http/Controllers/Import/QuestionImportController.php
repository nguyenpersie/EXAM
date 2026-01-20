<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\QuestionService;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionImportController extends Controller
{
    public function import(Request $request, $id)
    {
        $request->validate([
            'word_file' => 'required|file|mimes:docx|max:5120', // Max 5MB
        ]);

        $exam = Exam::findOrFail($id);
        $file = $request->file('word_file');
        $phpWord = IOFactory::load($file->getPathname());

        $paragraphs = [];
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $text = '';
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $textElement->getText();
                        } elseif ($textElement instanceof \PhpOffice\PhpWord\Element\Image) {
                            // Xử lý hình ảnh
                            $imageContent = $textElement->getMediaId();
                            $imagePath = $textElement->getPath();
                            $filename = uniqid() . '.png';
                            $path = 'questions/' . $filename;
                            Storage::disk('public')->put($path, file_get_contents($imagePath));
                            $text .= '<img src="' . asset('storage/' . $path) . '" alt="Hình minh họa" style="max-width: 100%;">';
                        }
                    }
                    $text = trim($text);
                    if (!empty($text)) {
                        $paragraphs[] = $text;
                    }
                }
            }
        }

        $questions = [];
        $currentQuestion = null;
        $options = [];

        foreach ($paragraphs as $line) {
            $line = trim($line);

            if (preg_match('/^Câu\s*\d+\./iu', $line)) { // Câu hỏi bắt đầu bằng "Câu 31."
                if ($currentQuestion) {
                    $questions[] = [
                        'content' => $currentQuestion,
                        'options' => $options,
                    ];
                }
                $currentQuestion = $line;
                $options = [];
            } elseif (preg_match('/^([a-d])\./iu', $line, $matches)) { // Đáp án a., b., c., d.
                $letter = strtoupper($matches[1]);
                $isCorrect = str_starts_with($line, '*');
                $content = trim(str_replace('*', '', $line));

                $options[] = [
                    'letter' => $letter,
                    'content' => $content,
                    'is_correct' => $isCorrect ? 1 : 0,
                ];
            }
        }

        // Lưu câu hỏi cuối
        if ($currentQuestion) {
            $questions[] = [
                'content' => $currentQuestion,
                'options' => $options,
            ];
        }

        // Insert vào DB
        $inserted = 0;
        foreach ($questions as $qData) {
            if (count($qData['options']) < 4) continue; // Bỏ nếu không đủ 4 đáp án

            $question = Question::create([
                'exam_id' => $exam->id,
                'content' => $qData['content'],
                'section' => 'I', // Có thể parse từ Word nếu có
                'level' => 3,
            ]);

            foreach ($qData['options'] as $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'content' => $opt['letter'] . '. ' . $opt['content'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }

            $inserted++;
        }

        return back()->with('success', "Đã import {$inserted} câu hỏi thành công!");
    }
}
