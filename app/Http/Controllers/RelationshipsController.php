<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relationship;
use DB;

class RelationshipsController extends Controller
{
	//
	public function index()
	{
		$relationships = Relationship::all();
		return $relationships ;

	}


	public function store(Request $request)
	{
		$relationship = new Relationship();
		$relationship->name = $request->name ;
		$relationship->save();

	 	foreach($request->fields as $field){

	    		DB::table('field_relationship')->insert([
			    [ 'field_id' => $field, 'relationship_id' => $relationship->id]
		         ]);

		}

//		return "reached here";		

	}
}
