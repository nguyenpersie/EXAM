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
      'code',
        'title',
        'duration_minutes',
        'total_score',
        'passing_score',
    ];

    // Quan hệ với câu hỏi (many-to-many)
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
