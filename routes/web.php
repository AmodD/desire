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

use App\Repositories\NeuralNetwork;
use App\Repositories\JAK8583;

use App\Annmodel;
use App\Transaction;

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
Route::get('/nn', function () {
    return view('home');
})->name('nn');

Route::get('/bp', 'TransactionsController@demo')->name('bp');

Route::get('/mldemo', 'TransactionsController@mldemo');
Route::get('/score', 'TransactionsController@score');
Route::post('/savemodel', 'TransactionsController@saveModel');
Route::get('/loadmodelstats', 'TransactionsController@loadModelStats');
Route::get('/getmodels', 'TransactionsController@getModels');

Route::get('/testdata', 'TransactionsController@testdata');

Route::get('/test', function() {

		$model = new Annmodel();
		$models = $model->where('id','>',1)->pluck('id','name');

		dd($models);

	$transactions = Transaction::with('data')->where('annmodel_id',0)->take(10)->orderby('id','desc')->get();

	foreach($transactions as $txn)
	{
		dd($txn->data,$txn->data->where('field_id',3),$txn->data->get(3)->value);
	}

	
	$vector = collect([]);
	$vector->push(1);
	$vector->push(0);
	$vector->push(1);
	$vector->push(1);

	$vectorStr = '110010';
	
	dd(array_map('intval', str_split($vectorStr)),$vector->toArray());



	$transactions = Transaction::with('data')->take(10)->orderby('id','desc')->get();

	foreach($transactions as $transaction)
	{
		dd($transaction->data->get(1)->value);
	}


	$vector = '11000011010010011001000101';
return 	array_map('intval', str_split($vector));
//	return str_split($vector);
	dd(str_split($vector));

//	$var = "";
//	echo	$var  ? "Hi" : "Goodbye"  ;

	//	return ['shrihan', 'soham', 'baby' , 'vivaan'];
});

//Route::get('/pack', 'TransactionsController@pack');
//Route::get('/unpack', 'TransactionsController@unpack');

Route::get('/analyze', 'TransactionsController@analyze');
Route::get('/generate', 'TransactionsController@generate');
