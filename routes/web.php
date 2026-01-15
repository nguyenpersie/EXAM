<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('pages.home');
Route::resource('questions', QuestionController::class)->names('admin.questions');
Route::get('/exams-{id}/test', [ExamController::class, 'test'])->name('exams.test');

