<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
	public function index(): View
	{
        return view('startpage');
    }
}
