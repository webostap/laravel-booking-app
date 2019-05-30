<?php

namespace App\Http\Controllers\Show;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ShowAdmin extends Controller
{

    public function __invoke()
    {
    	return view('admin', [
			'reserves' => \App\Reserve::orderBy('date', 'ASC')->orderBy('stamp_beg', 'ASC')->get()
		]);

    }
}
