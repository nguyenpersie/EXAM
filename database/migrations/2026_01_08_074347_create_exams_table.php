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
            $table->integer('duration_minutes')->comment('Thời gian làm bài');
            $table->float('total_score')->comment('Tổng điểm');
            $table->float('passing_score')->comment('Điểm đạt');
            $table->string('category')->nullable()->after('title'); // Danh mục/Hạng mục
            $table->text('description')->nullable()->after('category'); // Mô tả
            $table->boolean('is_active')->default(true)->after('passing_score'); // Trạng thái
            $table->index('category'); // Index cho tìm kiếm nhanh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('exams');
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['category', 'description', 'is_active']);
        });
    }
};
