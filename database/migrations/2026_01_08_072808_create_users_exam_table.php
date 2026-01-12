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
        Schema::create('users_exam', function (Blueprint $table) {
            $table->id();
            $table->string('student_code')->nullable();
            $table->string('full_name');
            $table->string('password');
            $table->string('category')->nullable();
            $table->tinyInteger('role')->default(1); // 0=admin, 1=student
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_exam');
    }
};
