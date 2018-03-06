<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Label;

class LabelsController extends Controller
{
    //
	public function getlabels(Request $request)
	{
		$labels =  Label::where('relationship_id' , $request->relid)->get();
		return $labels ;

	}
	
	public function store(Request $request)
	{
		$label = new Label();
		$label->value = $request->label;
		$label->relationship_id = $request->relationship;

		$label->save();	

	}


}
