<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $primaryKey = 'id';
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'code',
        'title',
        'category', // Thêm category để phân loại
        'duration_minutes',
        'total_score',
        'passing_score',
        'description', // Thêm mô tả
        'is_active', // Trạng thái đề thi
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'total_score' => 'decimal:2',
        'passing_score' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Quan hệ với câu hỏi (many-to-many)
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Lấy câu hỏi ngẫu nhiên
     */
    public function getRandomQuestions($limit = null)
    {
        $query = $this->questions()->with('options');

        if ($limit) {
            return $query->inRandomOrder()->limit($limit)->get();
        }

        return $query->inRandomOrder()->get();
    }

    /**
     * Scope: Chỉ lấy đề thi đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Lọc theo category
     */
    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Accessor: Thời gian làm bài (formatted)
     */
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours} giờ {$minutes} phút";
        }
        return "{$minutes} phút";
    }

    /**
     * Accessor: Tổng số câu hỏi
     */
    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }
}
