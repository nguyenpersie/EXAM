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
     * Lấy câu hỏi ngẫu nhiên (Tối ưu hóa tránh inRandomOrder)
     */
    public function getRandomQuestionsByCategory($limit = 30, $categories = [])
    {
        $query = $this->questions();

        if (!empty($categories)) {
            $query->whereIn('category', $categories);
        }

        // Lấy danh sách ID
        $ids = $query->pluck('id')->toArray();

        if (empty($ids))
            return collect();

        // Xáo trộn mảng ID trong PHP
        shuffle($ids);

        // Cắt mảng lấy số lượng câu hỏi cần thiết
        $selectedIds = array_slice($ids, 0, $limit);

        // Truy vấn lại chính xác các câu được chọn và load options
        return \App\Models\Question::with('options')
            ->whereIn('id', $selectedIds)
            ->get();
    }

    /**
     * Lấy danh sách các phần (sections) có trong đề thi
     */
    public function getSections()
    {
        return $this->questions()
            ->selectRaw('section, COUNT(*) as count')
            ->groupBy('section')
            ->orderBy('section')
            ->get()
            ->map(function ($item) {
                return [
                    'section' => $item->section,
                    'count' => $item->count
                ];
            });
    }

    /**
     * Lấy tất cả câu hỏi theo section (không random, theo thứ tự)
     */
    public function getQuestionsBySection($section)
    {
        return $this->questions()
            ->with('options')
            ->where('section', $section)
            ->orderBy('id')
            ->get();
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
