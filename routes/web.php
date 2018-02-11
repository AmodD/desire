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
use Kaperys\Financial\Financial;
use Kaperys\Financial\Cache\CacheManager;
use Kaperys\Financial\Message\Schema\ISO8583;
use Kaperys\Financial\Message\Schema\SchemaManager;


Route::get('/', function () {
    return view('home');
});
Route::get('/create', function () {
    return view('home');
})->name('create');
Route::get('/auto', function () {
    return view('home');
})->name('auto');
Route::get('/demo', function () {
    return view('home');
})->name('demo');

Route::get('/mldemo', 'TransactionsController@mldemo');

Route::get('/test', function() {

	$var = "";
	echo	$var  ? "Hi" : "Goodbye"  ;

	//	return ['shrihan', 'soham', 'baby' , 'vivaan'];
});

//Route::get('/pack', 'TransactionsController@pack');
//Route::get('/unpack', 'TransactionsController@unpack');

Route::get('/analyze', 'TransactionsController@analyze');
Route::get('/generate', 'TransactionsController@generate');
