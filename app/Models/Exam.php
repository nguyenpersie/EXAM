<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $primaryKey = 'exam_id';
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'code',                  // Mã hạng (LPT, TM, TT, T4...)
        'title',                 // Tên đề thi
        'duration_minutes',      // Thời gian làm bài
        'total_score',           // Tổng điểm
        'passing_score',         // Điểm đạt
        'section_i_count',       // Số câu phần I
        'section_ii_count',      // Số câu phần II
        'section_iii_count',     // Số câu phần III
    ];

    // Quan hệ với câu hỏi (many-to-many)
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
                    ->withPivot('point');
    }
}
