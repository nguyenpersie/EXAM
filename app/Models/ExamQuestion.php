<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{

    protected $primaryKey = 'exam_question_id';
    use HasFactory;

    protected $table = 'exam_question';

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
