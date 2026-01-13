<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    // Quan hệ với câu hỏi
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
