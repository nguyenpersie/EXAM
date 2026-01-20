<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Import\QuestionImportController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [UserController::class, 'ViewLogin'])->name('admin.login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('questions', [QuestionController::class, 'index'])->name('admin.questions');
Route::get('options', [OptionController::class, 'index'])->name('admin.options');

//Route::get('/exams/{exam}/questions/import', [QuestionImportController::class, 'importForm'])->name('admin.questions.importForm');
//Route::post('/exams/{exam}/questions/import', [QuestionImportController::class, 'import'])->name('admin.questions.import');

Route::get('/exams-{id}/test', [ExamController::class, 'test'])->name('exams.test');

