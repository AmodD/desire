<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
	//
	public function field()
	{
		return $this->belongsTo(Field::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
