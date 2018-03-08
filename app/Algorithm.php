<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    //
	public function Mlmodels()
	{
		return $this->hasMany(Mlmodel::class);
	}
}
