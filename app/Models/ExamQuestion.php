<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = 'exam_questions';

    protected $fillable = [
        'exam_id',
        'question_id',
        'point',     // Điểm cho câu hỏi này trong đề
    ];

    // Quan hệ ngược lại
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
