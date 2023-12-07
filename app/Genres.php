<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    protected $table = 'genres';

    protected $fillable = ['genre_name', 'genre_image'];
    protected $appends = ['imageUrl'];
    protected $hidden = [
        'genres_image',
        'genre_name',
        'genre_slug',

    ];


    public $timestamps = false;


    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            return $query->select(
                '*',
                'genre_name as name',
                'genre_slug as slug',
            );
        });
    }

    public function getImageUrlAttribute($value)
    {
        return asset('upload/source/' . $this->genres_image);
    }

    public function movies()
    {
        return $this->hasMany(Movies::class, 'movie_genre_id');
    }

    public function series()
    {
        return $this->hasMany(Series::class, 'series_genres');
    }


    public static function getGenresInfo($id, $field_name)
    {
        $genres_info = Genres::where('status', '1')->where('id', $id)->first();
        if ($genres_info) {
            return $genres_info->$field_name;
        } else {
            return '';
        }
    }

    public static function getGenresID($field_name)
    {
        $genres_info = Genres::where('genre_name', $field_name)->first();

        if ($genres_info) {
            return $genres_info->id;
        } else {
            return '';
        }
    }
}
