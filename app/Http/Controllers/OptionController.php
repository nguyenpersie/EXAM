<?php

namespace App\Http\Controllers;

use App\Http\Services\OptionService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OptionController extends Controller
{

 protected OptionService $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    public function index(): View
    {
        $options = $this->optionService->getPaginatedOptions();
        return view('admin.questions.option', compact('options'));
    }
}
