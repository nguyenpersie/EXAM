<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $primaryKey = 'option_id';
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'question_id',
        'content',
        'is_correct',
    ];

    // Quan hệ ngược lại với câu hỏi
   public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
