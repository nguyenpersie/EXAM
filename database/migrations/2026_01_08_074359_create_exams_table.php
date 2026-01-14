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
            $table->timestamps();
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
