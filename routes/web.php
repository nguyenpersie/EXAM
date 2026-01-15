<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [UserController::class, 'login'])->name('login');
Route::resource('questions', QuestionController::class)->names('admin.questions');
Route::get('/exams-{id}/test', [ExamController::class, 'test'])->name('exams.test');

