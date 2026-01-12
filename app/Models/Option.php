<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'content',       // Nội dung đáp án
        'is_correct',    // Đáp án đúng hay không
    ];

    // Quan hệ ngược lại với câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
