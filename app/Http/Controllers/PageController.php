<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
	public function index()
	{
	    return 'coming soon';
        return view('startpage');
    }
}
