<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function intro(): View
    {
        return view('pages.intro');
    }
}
