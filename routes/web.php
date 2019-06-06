<?php

	/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;


Route::get('/', 'Show\ShowIndex');
Route::get('date', 'AjaxController@date');
Route::get('time', 'AjaxController@time');
Route::post('submit', 'AjaxController@submit');


Route::get('admin', 'Show\ShowAdmin')->middleware('auth');
Route::get('admin/edit', 'Show\ShowEdit')->middleware('auth');

Route::resource('admin/edit/reserves', 'Edit\EditReservesController', ['only' => [
    'update', 'destroy'
]]);
Route::resource('admin/edit/weekday', 'Edit\EditWeekDayController', ['only' => 
	'update'
]);
Route::resource('admin/edit/specialday', 'Edit\EditSpecialDayController', ['only' => [
    'store', 'update', 'destroy'
]]);
Route::resource('admin/edit/tables', 'Edit\EditTablesController', ['only' => [
    'store', 'destroy'
]]);





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
