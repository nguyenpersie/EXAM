<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function test($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);
        return view('pages.test', compact('exam'));
    }
}
