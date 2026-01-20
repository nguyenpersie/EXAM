<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OptionController extends Controller
{
    public function index(): View
    {
        $options = $this->optionService->getPaginatedOptions();
        return view('admin.questions.option', compact('options'));
    }
}
