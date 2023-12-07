<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $table = 'coupons';

    protected $fillable = ['title','promo_code','amount_type','amount','expiry_date','users_limit','per_users_limit','description','status'];


	public $timestamps = false;  
}
