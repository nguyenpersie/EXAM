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
       Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->text('content')->comment('Nội dung câu hỏi');
            $table->string('section')->nullable()->comment('Phần/Chương');
            $table->tinyInteger('level')->default(3)->comment('Độ khó 1-5');
            $table->timestamps();

            // Indexes
            $table->index('exam_id');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
