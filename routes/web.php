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
use App\RelationshipTransaction;

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
Route::get('/relationship', function () {
    return view('home');
})->name('relationship');
Route::get('/traindata', function () {
    return view('home');
})->name('traindata');
Route::get('/simulator', function () {
    return view('home');
})->name('simulator');
Route::get('/transactions', function () {
    return view('home');
})->name('transactions');

Route::get('/bp', 'TransactionsController@demo')->name('bp');

Route::get('/mldemo', 'TransactionsController@mldemo');
Route::get('/score', 'TransactionsController@score');
Route::post('/savemodel', 'TransactionsController@saveModel');
Route::post('/createmodel', 'TransactionsController@createModel');
Route::get('/testcreatemodel', 'TransactionsController@createModel');
Route::get('/loadmodelstats', 'TransactionsController@loadModelStats');
Route::get('/getmodels', 'TransactionsController@getModels');

Route::get('/testdata', 'TransactionsController@testdata');

Route::get('/test', function() {

		$situation = \App\Situation::find(2);

		$mti = '';
		$procode = '';
		$poscapture = '';
		$posem = '';
	dd($situation->scenarios);	
		foreach( $situation->scenarios  as $scenario)
		{
			foreach($scenario->fields as $field)
			{
				// DE 0
				if($field->pivot->field_id == '0')
				{
					$mti =  $field->pivot->value ;
				}

				// DE 3
				if($field->pivot->field_id == '3')
				{
					$procode =  $field->pivot->value ;
				}

				// DE 26
				if($field->pivot->field_id == '26')
				{
					$poscapture = $field->pivot->value ;			
				}
				// DE 22
				if($field->pivot->field_id == '22')
				{
					if($scenario->question_id == '3') $posem = $field->pivot->value ;			

					
					if($field->pivot->scenario_id == '38') $posem = $posem.'0';
					else if(($poscapture == '8') && ($scenario->question_id == '3')) $posem = $posem.'1';
					else if(($poscapture == '4') && ($scenario->question_id == '6')) $posem = $posem.$field->pivot->value;
				}
			}
		}

	dd($mti,$procode,$poscapture,$posem);	

	$situation = \App\Situation::find(2);

	foreach( $situation->scenarios  as $scenario)
	{
			if($scenario->question_id == 6)
			{
				dd($scenario->fields);	
			}
		foreach($scenario->fields as $field)
		{
		}
	}


	dd(str_pad(base_convert('01200121', 2, 16),8,'0', STR_PAD_LEFT));
dd(unpack("H*" , '76'));
dd(hex2bin('76'));


	$reltxn =  RelationshipTransaction::all();
	dd($reltxn);

	$dataElement = collect([]);

	$dataElement->put("a",1);
	$dataElement->put("b",2);
	$dataElement->put("c",3);
	$dataElement->put("d",4);
	$dataElement->put("e",5);

	dd($dataElement);

	dd(decbin("759"),decbin(99),decbin(9));

		$validposcc = ["00","01","02","03","05","07","08","52","59"];

dd(in_array(4,$validposcc));

dd(decbin(100),decbin(100000));

$byte_array = unpack('C*', 'The quick fox jumped over the lazy brown dog');
//var_dump($byte_array); 
dd($byte_array);
	Transaction::with('data')->where('annmodel_id',1)->chunk(200, function ($flights) {
		dd($flights);
    foreach ($flights as $flight) {
        //
    }
});
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

Route::get('/pack', 'TransactionsController@pack');
Route::get('/unpack', 'TransactionsController@unpack');

Route::get('/analyze', 'TransactionsController@analyze');
Route::get('/generate', 'TransactionsController@generate');
Route::get('/lasttxns', 'TransactionsController@lasttxns');

Route::post('/relationships', 'RelationshipsController@store');
Route::get('/relationships', 'RelationshipsController@index');

Route::get('/algorithms', 'AlgorithmsController@index');

Route::delete('/transactions/{transaction}','TransactionsController@destroy');

Route::post('/labels', 'LabelsController@store');
Route::get('/labels', 'LabelsController@getlabels');

Route::get('/getfields', 'FieldsController@getfields');
Route::get('/allfields', 'FieldsController@getall');

Route::get('/getquestions','ScenarioController@getquestions');

Route::get('/situations', 'SituationController@index');
Route::post('/situations', 'SituationController@store');
Route::get('/txncount/{situation}', 'SituationController@txncount');
