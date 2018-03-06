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

	public function labels()
	{
		return $this->hasMany(Label::class);
	}
	
	public function transactions()
	{
		return $this->belongsToMany(Transaction::class)->withPivot('label_id')->with('data')->orderBy('id','desc');
	}
}
