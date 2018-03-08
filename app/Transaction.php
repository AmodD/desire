<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	//
	public function data()
	{
		return $this->hasMany(Data::class);
	}
	
	public function mlmodel()
	{
		return $this->belongsTo(Mlmodel::class);
	}
	
	public function relationships()
	{
		return $this->belongsToMany(Relationship::class)->withPivot('label_id')->using(RelationshipTransaction::class);
	}
}
