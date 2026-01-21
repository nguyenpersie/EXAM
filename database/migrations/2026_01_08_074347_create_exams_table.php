<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('Mã hạng: LPT, TM, TT...');
            $table->string('title', 255)->comment('Tên đề thi');
            $table->string('category')->nullable()->comment('Danh mục/Hạng mục');
            $table->text('description')->nullable()->comment('Mô tả đề thi');
            $table->integer('duration_minutes')->comment('Thời gian làm bài (phút)');
            $table->decimal('total_score', 8, 2)->comment('Tổng điểm');
            $table->decimal('passing_score', 8, 2)->comment('Điểm đạt');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
