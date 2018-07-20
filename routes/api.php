<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function (Request $request) {
    return "YO API !";
});
Route::get('/fields', function (Request $request) {
    return (new \App\Field)->where('id','>',2)->get();
});
Route::get('/mcc', function (Request $request) {
    return (new \App\Scenario)->where('question_id',7)->get();
});
Route::get('/transactions/{transaction}', 'TransactionsController@show');
Route::get('/getmcc', 'TransactionsController@getmcc');
Route::post('/relationship', 'RelationshipsController@store');
