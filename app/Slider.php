<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';
    protected $fillable = ['slider_title', 'slider_image'];
    protected $appends = ['imageUrl'];


    public $timestamps = false;
    protected $hidden = [
        'slider_title',
        'slider_image',
        'slider_type',
        'slider_post_id',
        'slider_url',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'slider_title as title',
                'slider_image as image',
                'slider_type as type',
                'slider_post_id as postId',
                'slider_url as url',
                'status'
            );
        });
    }

    public function getImageUrlAttribute($value)
    {
        return asset('upload/source/' . $this->slider_image);
    }
}
