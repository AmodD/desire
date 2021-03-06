<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mlmodel extends Model
{
    //
	public function algorithm()
	{
		return $this->belongsTo(Algorithm::class);
	}
	
	public function relationship()
	{
		return $this->belongsTo(Relationship::class);
	}
}
