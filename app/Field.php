<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
	//
	public function data()
	{
		return $this->hasMany(Data::class);
	}
	
	public function relationships()
	{
		return $this->belongsToMany(Relationship::class);
	}
	
	public function scenarios()
	{
		return $this->belongsToMany(Scenario::class);
	}
	
}
