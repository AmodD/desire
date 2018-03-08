<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Algorithm;
use DB;

class AlgorithmsController extends Controller
{
    //
	public function index()
	{
		$algorithms = Algorithm::all();
		return $algorithms ;

	}
}
