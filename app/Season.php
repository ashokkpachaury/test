<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = 'season';

    protected $fillable = ['season_name', 'season_poster'];
    public $timestamps = false;

    protected $hidden = [
        'season_name',
        'season_slug',
        'season_poster',
        'seo_title',
        'seo_description',
        'seo_keyword',
        'moderator_id',
        'series_id'
    ];

    protected $appends = ['posterUrl'];


    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'season_name as name',
                'season_slug as slug',
                'seo_title as seoTitle',
                'seo_description as seoDescription',
                'seo_keyword as seoKeyword',
                'moderator_id as moderatorId',
                'season_poster as season_poster'
            );
        });
    }

    //getPosterUrlAttribute
    public function getPosterUrlAttribute($value)
    {
        //return asset('upload/source/' . $this->season_poster);
        $url = $this->season_poster;
        if(strpos($this->season_poster,'http') || strpos($this->season_poster,'www'))
        {
            $url = $this->season_poster;
        }
        else{
            $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->season_poster));
        } 
        return $url;
    }

    public function getSeasonPosterAttribute($value)
    {
        $data = $this->getAttributes();

        $url = $data['season_poster'];
        if($url!='')
        {
            if(strpos($url,'http') || strpos($url,'www')){
                $url = $url;
            }
            else{                
                $url = asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url));
            }
        }       
        return $url;
    }


    public static function getSeasonInfo($id, $field_name)
    {
        $season_info = Season::where('status', '1')->where('id', $id)->first();

        if ($season_info) {
            return $season_info->$field_name;
        } else {
            return '';
        }
    }
    public function series() {
        return $this->belongsTo('App\Series','series_id');
    }
}
