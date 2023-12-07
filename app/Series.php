<?php

namespace App;

use App\Season;
use Illuminate\Database\Eloquent\Model;
use App\Episodes;

class Series extends Model
{
    protected $table = 'series';

    protected $fillable = ['series_name', 'series_poster'];

    protected $hidden = [
        'series_poster',
        'series_name',
        'series_info',
        'imdb_id',
        'imdb_rating',
        'imdb_votes',
        'seo_title',
        'seo_description',
        'seo_keyword',
        'status',
        'moderator_id',
        'created_at',
        'updated_at',
        'series_lang_id',
        'series_genres',
        'series_slug',
    ];


    protected $appends = ['posterImageUrl', 'genres','mediaId'];
    protected $with = ['language'];
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'series_lang_id as langId',
                'series_genres as genreIds',
                'series_name as name',
                'series_slug as slug',
                'series_info as info',
                'imdb_id as imdbId',
                'imdb_rating as imdbRating',
                'imdb_votes as imdbVotes',
                'seo_title as seoTitle',
                'seo_description as seoDescription',
                'seo_keyword as seoKeyword',
                'moderator_id as moderatorId',
            );
        });
    }


    public static function getSeriesInfo($id, $field_name)
    {
        $series_info = Series::where('status', '1')->where('id', $id)->first();

        if ($series_info) {
            return $series_info->$field_name;
        } else {
            return '';
        }
    }

    public static function getSeriesTotalSeason($id)
    {
        $total_season = Season::where('series_id', $id)->count();

        return $total_season;
    }

    public static function getSeriesTotalEpisodes($id)
    {
        $total_episode = Episodes::where('episode_series_id', $id)->count();

        return $total_episode;
    }

    public function getPosterImageUrlAttribute()
    {
        $data = $this->getAttributes();        
        if(isset($data['series_poster']) && $data['series_poster']!='')
        {
            if(strpos($data['series_poster'],'http') || strpos($data['series_poster'],'www')){
                return $data['series_poster'];
            }
            else{
                return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$data['series_poster']));
            }
        }
        else{
            if(isset($this->posterUrl) && $this->posterUrl!='')
            {
                if(strpos($this->posterUrl,'http') || strpos($this->posterUrl,'www')){
                    return $this->posterUrl;
                }
                else{
                    return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->posterUrl));
                }
            }
            else{
                return $this->getOriginal('posterUrl');
            } 
        }  

    }


    public function getSeriesPosterAttribute()
    {
        $data = $this->getAttributes();        
        if(isset($data['series_poster']) && $data['series_poster']!='')
        {
            if(strpos($data['series_poster'],'http') || strpos($data['series_poster'],'www')){
                return $data['series_poster'];
            }
            else{
                return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$data['series_poster']));
            }
        }
        else{
            if(isset($this->posterUrl) && $this->posterUrl!='')
            {
                if(strpos($this->posterUrl,'http') || strpos($this->posterUrl,'www')){
                    return $this->posterUrl;
                }
                else{
                    return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->posterUrl));
                }
            }
            else{
                return $this->getOriginal('posterUrl');
            } 
        } 
    }
    
    // language
    public function language()
    {
        return $this->belongsTo('App\Language', 'series_lang_id');
    }

    // getGenresAttribute by comma separated
    public function getGenresAttribute()
    {
        $ids = explode(',', $this->series_genres);
        return Genres::whereIn('id', $ids)->get();
    }

    // Season relation
    public function seasons()
    {
        return $this->hasMany('App\Season', 'series_id');
    }

    public function getRelatedAttribute()
    {
        //updating related shows prioritising based on genre first and content language further if genre based shows are not sufficient
        
        /*return  Series::where('status', '1')
            ->where('id', "!=", $this->id)
            ->where('series_lang_id', $this->series_lang_id)
            ->orderBy('id', 'DESC')->take(25)->get();*/

        $genre_series = Series::where('status', 1)->where('id', '!=', $this->id)->where('series_genres', 'LIKE' , $this->series_genres)->orderBy('id', 'DESC')->limit(25)->get();
        if($genre_series->count()==25){
            return $genre_series;
        }
        else
        {
            $lang_series = Series::where('status', 1)->where('id', '!=', $this->id)->where('series_lang_id', $this->series_lang_id)->where('series_genres', 'NOT LIKE' , $this->series_genres)->orderBy('id', 'DESC')->limit((25-$genre_series->count()))->get();
            return $genre_series->merge($lang_series);
        }
    }


    // Define the many-to-many relationship with Genres
    public function genres()
    {
        return $this->belongsToMany('App\Genres', 'series_genres', 'series_id', 'genre_id');
    }

    public function getmediaIdAttribute($value)
    {
        return   $this->attributes['mediaId'];
    }
}
