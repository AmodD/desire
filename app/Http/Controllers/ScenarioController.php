<?php

namespace App\Http\Controllers;

use App\Scenario;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
	public function getquestions(Request $request)
	{
		$scenario = new Scenario();
		return  $scenario->all();

	}
	
	public function store(Request $request)
        {

	}
}
