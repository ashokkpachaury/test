<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvCategory extends Model
{
    protected $table = 'channel_category';

    protected $fillable = ['category_name', 'category_slug'];
    protected $hidden = [
        'category_name',
        'category_slug',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'category_name as name',
                'category_slug as slug',

            );
        });
    }


    public static function getTvCategoryInfo($id, $field_name)
    {
        $cat_info = TvCategory::where('status', '1')->where('id', $id)->first();

        if ($cat_info) {
            return $cat_info->$field_name;
        } else {
            return '';
        }
    }


    //getLiveTvAttribute
    public function getLiveTvsAttribute($value)
    {
        return LiveTV::where('status', '1')->where('channel_cat_id', $this->id)->orderBy('id', 'DESC')->limit(10)->get();
    }
    public function getTotalItemsAttribute($value)
    {
        return LiveTV::where('status', '1')->where('channel_cat_id', $this->id)->orderBy('id', 'DESC')->count();
    }



}
