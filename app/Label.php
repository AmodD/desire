<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
	//
	public function relationship()
	{
		return $this->belongsTo(Relationship::class); 	
	}
}
