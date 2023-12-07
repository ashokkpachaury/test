<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episodes extends Model
{
    protected $table = 'episodes';


    protected $fillable = ['video_title', 'episode_number','video_image'];

    protected $appends = ['videoImage'];


    public $timestamps = false;


    protected $hidden = [
        'video_access',
        'episode_series_id',
        'episode_season_id',
        'episode_genre_id',
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
    ];

    protected static function booted()
    {

        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'video_access as videoAccess',
                'episode_series_id as seriesId',
                'episode_genre_id as genreId',
                'episode_number as episodeNumber',
                'video_title as videoTitle',
                'release_date as releaseDate',
                'duration as duration',
                'video_description as videoDescription',
                'video_slug as videoSlug',
                'video_type as videoType',
                'video_quality as videoQuality',
                'video_url as videoUrl',
                'video_url_480 as videoUrl480',
                'video_url_720 as videoUrl720',
                'video_url_1080 as videoUrl1080',
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

            );
        });
    }


    public static function getEpisodesInfo($id, $field_name)
    {
        $episodes_info = Episodes::where('status', '1')->where('id', $id)->first();

        if ($episodes_info) {
            return $episodes_info->$field_name;
        } else {
            return '';
        }
    }

    public function getVideoImageAttribute()
    {
        if ($this->attributes['video_image'])
        {
            if(strpos($this->attributes['video_image'],'http') || strpos($this->attributes['video_image'],'www'))
            {
                return $this->attributes['video_image'];
            }
            else{
                return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->attributes['video_image']));
            }
        }
        if(strpos($this->imageUrl,'http') || strpos($this->imageUrl,'www'))
        {
            return $this->imageUrl;
        }
        else{
            return asset('upload/source/' . str_replace(asset('upload/source/').'/','',$this->imageUrl));
        }
    }


    public function genre()
    {
        return $this->hasOne('App\Genres', 'id', 'episode_genre_id');
    }

    public function series()
    {
        return $this->hasOne('App\Series', 'id', 'episode_series_id');
    }

    public function season()
    {
        return $this->hasOne('App\Season', 'id', 'episode_season_id');
    }


}
