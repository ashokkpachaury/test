<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $table = 'push_notification';

    protected $fillable = ['name', 'message'];

	public $timestamps = false;
 	
}
