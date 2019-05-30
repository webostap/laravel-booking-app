<?php

namespace App\Http\Controllers\Edit;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use \App\Reserve;

class EditReservesController extends Controller
{
	public function __construct()
	{
	    $this->middleware('auth');
	}
    public function update ($id) {
    	$confrimed = Reserve::Confrim($id);
    	if ($confrimed) return redirect('admin');
        else return redirect()->back()->withErrors('В указанное время нет доступных столов данного размера');
    }
    public function destroy ($id) {
		Reserve::destroy($id);
		return redirect()->back();
    }
}
