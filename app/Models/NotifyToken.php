<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifyToken extends Model
{
    protected $guarded =  [];

    static public function getAllToArray() {
        return static::query()->get()->pluck('token');
    }
}
