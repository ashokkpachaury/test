<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    protected $table = 'movie_videos';
    protected $fillable = ['video_title', 'video_image','mediaId'];
    protected $appends = ['genres','posterUrl', 'videoUrl', 'videoUrl480', 'videoUrl720', 'videoUrl1080','mediaId','videoImageThumb'];
    protected $with = ['language'];
    protected $hidden = [
        'video_access',
        'movie_lang_id',
        'video_title',
        'release_date',
        'duration',
        'video_description',
        'video_slug',
        'video_image',
        'video_type',
        'video_quality',
        'video_url',
        'video_url_480',
        'video_url_720',
        'video_url_1080',
        'download_enable',
        'download_url',
        'subtitle_on_off',
        'subtitle_language1',
        'subtitle_url1',
        'subtitle_language2',
        'subtitle_url2',
        'subtitle_language3',
        'subtitle_url3',
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
        'movie_genre_id',
        'video_image_thumb',
    ];


    public $timestamps = false;


    protected static function booted()
    {
        
        static::addGlobalScope('reselect', function ($query) {
            $asset_url = asset("upload/source")."/";
            return $query->select(
                '*',
                'video_access as videoAccess',
                'movie_lang_id as langId',
                'video_title as title',
                'release_date as releaseDate',
                'duration as duration',
                'video_description as description',
                'video_slug as slug',
                'video_image as image',
                'video_type as type',
                'video_quality as quality',
                'download_enable as downloadEnable',
                'download_url as downloadUrl',
                'subtitle_on_off as subtitleOnOff',
                'subtitle_language1 as subtitleLanguage1',
                'subtitle_url1 as subtitleUrl1',
                'subtitle_language2 as subtitleLanguage2',
                'subtitle_url2 as subtitleUrl2',
                'subtitle_language3 as subtitleLanguage3',
                'subtitle_url3 as subtitleUrl3',
                'imdb_id as imdbId',
                'imdb_rating as imdbRating',
                'imdb_votes as imdbVotes',
                'seo_title as seoTitle',
                'seo_description as seoDescription',
                'seo_keyword as seoKeyword',
                'status as status',
                'moderator_id as moderatorId',
                'created_at as createdAt',
                'updated_at as updatedAt',
//                'movie_genre_id as genreIds',
                'video_image_thumb as imageThumb',
                'thumbUrl as thumbUrl',
            );
        });
    }




    public function language()
    {
        return $this->belongsTo('App\Language', 'movie_lang_id', 'id');
    }


    public function getGenresAttribute()
    {
        $genreIds = $this->attributes['movie_genre_id'];
        $genreIds = explode(',', $genreIds);
        $genres = Genres::whereIn('id', $genreIds)->get();
        return $genres;
    }

    public static function getMoviesInfo($id, $field_name)
    {
        $movie_info = Movies::where('status', '1')->where('id', $id)->first();

        if ($movie_info) {
            return $movie_info->$field_name;
        } else {
            return '';
        }

    }

    public function getPosterUrlAttribute()
    {
        if (!$this->thumbUrl) {
            if(strpos($this->image,'http') || strpos($this->image,'www'))
            {
                return $this->image;
            }
            else{
                return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->image));
            }
        }
        if(strpos($this->thumbUrl,'http') || strpos($this->thumbUrl,'www'))
        {
            return $this->thumbUrl;
        }
        else{
            return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->thumbUrl));
        }
    }

    public function getVideoImageThumbAttribute()
    {
        if (!$this->thumbUrl) {
            if(strpos($this->imageThumb,'http') || strpos($this->imageThumb,'www'))
            {
                return $this->imageThumb;
            }
            else{
                return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->imageThumb));
            }
        }
        if(strpos($this->thumbUrl,'http') || strpos($this->thumbUrl,'www'))
        {
            return $this->thumbUrl;
        }
        else{
            return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->thumbUrl));
        }
    }

    //getVideoUrlAttribute
    public function getVideoUrlAttribute()
    {
        $url = $this->attributes['video_url'];
        if ($this->video_type == "Local") {
            $video_url = $url ?  asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url))  : "";
        } else {
            $video_url = $url ?  $url :  "";
        }
        return $video_url;
    }

    public function getVideoUrl480Attribute()
    {
        $url = $this->attributes['video_url_480'];
        if ($this->video_type == "Local") {
            $video_url = $url ?  asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url))  : "";
        } else {
            $video_url = $url ?  $url :  "";
        }
        return $video_url;
    }
    public function getVideoUrl720Attribute()
    {
        $url = $this->attributes['video_url_720'];
        if ($this->video_type == "Local") {
            $video_url = $url ?  asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url))  : "";
        } else {
            $video_url = $url ?  $url :  "";
        }
        return $video_url;
    }
    public function getVideoUrl1080Attribute()
    {
        $url = $this->attributes['video_url_1080'];
        if ($this->video_type == "Local") {
            $video_url = $url ?  asset('upload/source/' . str_replace(asset('upload/source/').'/','',$url))  : "";
        } else {
            $video_url = $url ?  $url :  "";
        }
        return $video_url;
    }

    public function getRelatedAttribute()
    {
        //updating related movies prioritising based on genre first and content language further if genre based movies are not sufficient
        $genre_movies = Movies::where('status', 1)->where('id', '!=', $this->id)->where('movie_genre_id', 'LIKE' , $this->movie_genre_id)->orderBy('id', 'DESC')->limit(25)->get();
        if($genre_movies->count()==25){
            return $genre_movies;
        }
        else
        {
            $lang_movies = Movies::where('status', 1)->where('id', '!=', $this->id)->where('movie_lang_id', $this->movie_lang_id)->where('movie_genre_id', 'NOT LIKE' , $this->movie_genre_id)->orderBy('id', 'DESC')->limit((25-$genre_movies->count()))->get();
            return $genre_movies->merge($lang_movies);
        }
    }

    public function getmediaIdAttribute($value)
    {
        return   $this->attributes['mediaId'];
    }
}
