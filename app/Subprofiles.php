<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subprofiles extends Model
{
    protected $table = 'sub_profile';

    protected $fillable = ['name', 'image'];


	public $timestamps = false;  
	
}
