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
//use kaperys\financial\src\Cache ;

Route::get('/', function () {
    return view('home');
});
Route::get('/test', function() {
	return ['shrihan', 'soham', 'baby' , 'vivaan'];
	//
	//
});

Route::get('/pack', 'TransactionsController@pack');
Route::get('/unpack', 'TransactionsController@unpack');

Route::get('/analyze', 'TransactionsController@analyze');
Route::get('/generate', 'TransactionsController@generate');
