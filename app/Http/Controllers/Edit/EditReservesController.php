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
    	if ($confrimed) return redirect()->back();
        else return redirect()->back()->withErrors('В указанное время нет доступных столов данного размера');
    }
    public function destroy ($id) {
		$deleted = Reserve::destroy($id);
        if ($deleted) return redirect()->back();
        else return redirect()->back()->withErrors('Заказ не удалён! Обратитесь к администратору');
    }
}
