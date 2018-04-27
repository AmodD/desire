<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
	public function situations()
	{
		return $this->belongsToMany(Situation::class);
	}
	
	public function fields()
	{
		return $this->belongsToMany(Field::class)->withPivot('value');
	}
}
