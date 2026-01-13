<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $primaryKey = 'question_id';
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'content',       // Nội dung câu hỏi (hỗ trợ HTML)
        'section',       // Phần I, II, III
        'level',         // Độ khó
        'subject_id',    // Nếu có foreign key subjects (môn học)
    ];

    // Quan hệ với đáp án
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }

    // Quan hệ với đề thi (many-to-many)
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
                    ->withPivot('point');
    }

    // Nếu có bảng subjects
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
