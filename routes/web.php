<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Import\QuestionImportController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Login Route
Route::get('/login', [UserController::class, 'ViewLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('options', [OptionController::class, 'index'])->name('admin.options');

// Trang chủ - Danh sách đề thi (Yêu cầu đăng nhập)
Route::get('/', [ExamController::class, 'index'])->name('home')->middleware('auth');

// Quản lý đề thi (Exams)
Route::prefix('exams')->name('exams.')->group(function () {
    // Danh sách đề thi
    Route::get('/', [ExamController::class, 'index'])->name('index');

    // Danh sách theo danh mục
    Route::get('/category/{category?}', [ExamController::class, 'category'])->name('category');

    // Tạo đề thi mới
    Route::get('/create', [ExamController::class, 'create'])->name('create');
    Route::post('/store', [ExamController::class, 'store'])->name('store');

    // Chi tiết đề thi
    Route::get('/{id}', [ExamController::class, 'show'])->name('show');

    // Chỉnh sửa đề thi
    Route::get('/{id}/edit', [ExamController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ExamController::class, 'update'])->name('update');

    // Xóa đề thi
    Route::delete('/{id}', [ExamController::class, 'destroy'])->name('destroy');

    // Làm bài thi
    Route::get('/{id}/test', [ExamController::class, 'test'])->name('test');

    // API: Lấy đề thi đã trộn ngẫu nhiên
    Route::get('/{id}/randomized', [ExamController::class, 'getRandomizedExam'])->name('randomized');
});

// Quản lý câu hỏi (Questions)
Route::prefix('questions')->name('questions.')->group(function () {
    // Danh sách câu hỏi của 1 đề
    Route::get('/exam/{examId}', [QuestionController::class, 'index'])->name('index');

    // Tạo câu hỏi mới
    Route::get('/exam/{examId}/create', [QuestionController::class, 'create'])->name('create');
    Route::post('/exam/{examId}', [QuestionController::class, 'store'])->name('store');

    // CRUD câu hỏi đơn lẻ
    Route::get('/{id}/edit', [QuestionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [QuestionController::class, 'update'])->name('update');
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->name('destroy');

    // Xóa tất cả câu hỏi của 1 đề
    Route::delete('/exam/{examId}/destroy-all', [QuestionController::class, 'destroyAll'])->name('destroyAll');

    // Import câu hỏi từ Word
    Route::post('/exam/{examId}/import', [QuestionController::class, 'import'])->name('import');
});

// Quản lý người dùng (Admin only)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);
});

// Temporary route to fix admin password
Route::get('/fix-admin-password', function () {
    $user = \App\Models\UserExam::where('student_code', 'admin')->first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->save();
        return 'Password for "admin" has been updated to "123456" (hashed). You can now login.';
    }
    return 'User "admin" not found.';
});

