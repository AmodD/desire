<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    //
	public function fields()
	{
		return $this->belongsToMany(Field::class);
	}

	public function mlmodels()
	{
		return $this->hasMany(Mlmodel::class);
	}

	public function labels()
	{
		return $this->hasMany(Label::class);
	}
	
	public function transactions()
	{
		return $this->belongsToMany(Transaction::class)->withPivot('label_id')->with('data')->orderBy('id','desc')->using(RelationshipTransaction::class);
	}

	public function data()
	{
		return $this->hasManyThrough(Data::class,
					     RelationshipTransaction::class,
				     	     'relationship_id',
					     'transaction_id',
					     'id','transaction_id')->whereNotIn('data.field_id',[1]);
	}
}
