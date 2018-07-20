<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    //

	public function scenarios()
	{
		return $this->belongsToMany(Scenario::class);
	}
	
	public function transactions()
	{
		return $this->hasMany(Transaction::class);
	}
}
