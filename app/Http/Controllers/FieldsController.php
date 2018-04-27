<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Field;
use App\Relationship;

class FieldsController extends Controller
{
	//
	public function getall()
	{
//		$fields = Field::whereIn('id',[2,3,4,18,19,22,25,49])->get();;	
		$fields = Field::where('id','!=',1)->get();
		return $fields;
	}

	public function getfields(Request $request)
	{
		return Relationship::find($request->relationship)->fields;
	}

}
