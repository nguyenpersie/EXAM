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
       'exam_id',
        'content',
        'section',
        'level',
    ];

    // Quan hệ với đáp án
   public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Quan hệ với đáp án (một câu hỏi có nhiều đáp án)
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
