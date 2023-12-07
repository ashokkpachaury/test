<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    protected $fillable = ['title', 'type', 'movie_ids', 'series_ids', 'status', 'is_highlight', 'order_index', 'livetv_ids', 'order', 'genre_id','slug'];

    protected $appends = ['items', 'totalItems'];
    protected $casts = [
        'movie_ids' => 'array',
        'series_ids' => 'array',
        'livetv_ids' => 'array',
        'order' => 'array',
        'slug' => 'array',


        'is_highlight' => 'boolean'
    ];
    protected $hidden = [
        'movie_ids',
        'series_ids',
        'order',
        'livetv_ids',
        'is_highlight',
        'created_at',
        'updated_at',

    ];


    protected static function booted()
    {
        static::addGlobalScope('reselect', function ($query) {
            // add these fields to the came case
            return $query->select(
                '*',
                'movie_ids as movieIds',
                'series_ids as serieIds ',
                'is_highlight as isHighlight',
                'created_at as createdAt',
                'updated_at as updatedAt'
            );
        });
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getMovieIdsAttribute($value)
    {
        return json_decode($value) ?? [];
    }



    public function getSeriesIdsAttribute($value)
    {
        return json_decode($value) ?? [];
    }

    public function getLivetvIdsAttribute($value)
    {
        return json_decode($value) ?? [];
    }

    // public function getMoviesAttribute($value)
    // {
    //     $items = Movies::query()->whereIn('id', $this->movie_ids);
    //     $itemsArray = [];
    //     if (request()->page) {
    //         $itemsArray = $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         $itemsArray = $items->get();
    //     }

    //     foreach ($itemsArray as &$item) {
    //         $item['itemType'] = 'movie';
    //     }

    //     return $itemsArray;
    // }

    // public function getSeriesAttribute($value)
    // {
    //     $items = Series::query()->whereIn('id', $this->series_ids);
    //     $itemsArray = [];
    //     if (request()->page) {
    //         $itemsArray = $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         $itemsArray = $items->get();
    //     }

    //     foreach ($itemsArray as &$item) {
    //         $item['itemType'] = 'series';
    //     }

    //     return $itemsArray;
    // }

    // public function getMovieGenresAttribute($value)
    // {
    //     $items = Movies::query()->whereIn('movie_genre_id', $this->genres_movie_ids);
    //     if (request()->page) {
    //         return $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         return $items->get();
    //     }
    // }

    // public function getSeriesGenresAttribute($value)
    // {
    //     $items = Movies::query()->whereIn('movie_genre_id', $this->genres_series_ids);
    //     if (request()->page) {
    //         return $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         return $items->get();
    //     }
    // }




    // public function getLiveTvAttribute($value)
    // {
    //     $liveTvIds = $this->livetv_ids;
    //     $items = LiveTV::query()->select(
    //         'channel_name as name',
    //         'id',
    //         'channel_access as channelAccess',
    //         'channel_cat_id as categoryId',
    //         'channel_slug  as slug',
    //         'channel_description  as description',
    //         'thumbUrl as thumbUrl',
    //         'channel_url_type  as urlType',
    //         'channel_url  as url',
    //         'channel_url2  as url2',
    //         'channel_url3  as url3',
    //         'seo_title as  seoTitle',
    //         'seo_description  as seoDescription',
    //         'seo_keyword as  seoKeyword',
    //         'status as status',
    //         'moderator_id as moderatorId',
    //     )->whereIn('id', $liveTvIds);

    //     if (request()->page) {
    //         return $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         return $items->get();
    //     }
    // }

    // public function getLiveTvAttribute($value)
    // {
    //     $liveTvIds = $this->livetv_ids;
    //     $items = LiveTV::query()->select(
    //         'channel_name as name',
    //         'id',
    //         'channel_access as channelAccess',
    //         'channel_cat_id as categoryId',
    //         'channel_slug  as slug',
    //         'channel_description  as description',
    //         'thumbUrl as thumbUrl',
    //         'channel_url_type  as urlType',
    //         'channel_url  as url',
    //         'channel_url2  as url2',
    //         'channel_url3  as url3',
    //         'seo_title as  seoTitle',
    //         'seo_description  as seoDescription',
    //         'seo_keyword as  seoKeyword',
    //         'status as status',
    //         'moderator_id as moderatorId',
    //     )->whereIn('id', $liveTvIds);
    //     $itemsArray = [];

    //     if (request()->page) {
    //         $itemsArray = $items->paginate(config('data.per_page'))->items();
    //     } else {
    //         $itemsArray = $items->get();
    //     }

    //     foreach ($itemsArray as &$item) {
    //         $item['itemType'] = 'livetv';
    //     }

    //     return $itemsArray;
    // }



    public function getItemsAttribute()
    {
        $movies = Movies::whereIn('id', $this->movie_ids)->get();
        $series = Series::whereIn('id', $this->series_ids)->get();
        $liveTv = LiveTV::select(
            'channel_name as name',
            'id',
            'channel_access as channelAccess',
            'channel_cat_id as categoryId',
            'channel_slug  as slug',
            'channel_description  as description',
            'thumbUrl as thumbUrl',
            'channel_url_type  as urlType',
            'channel_url  as url',
            'channel_url2  as url2',
            'channel_url3  as url3',
            'seo_title as  seoTitle',
            'seo_description  as seoDescription',
            'seo_keyword as  seoKeyword',
            'status as status',
            'moderator_id as moderatorId'
        )->whereIn('id', $this->livetv_ids)->get();

        $itemsArray = [];

        foreach ($movies as $movie) {
            $movie['itemType'] = 'movie';
            $itemsArray[] = $movie;
        }

        foreach ($series as $serie) {
            $serie['itemType'] = 'series';
            $itemsArray[] = $serie;
        }

        foreach ($liveTv as $tv) {
            $tv['itemType'] = 'livetv';
            $itemsArray[] = $tv;
        }

        $order = $this->order;

        $itemsById = [];
        foreach ($itemsArray as $item) {
            $itemsById[$item['id']] = $item;
        }

        // Initialize the final rearranged array
        $rearrangedItems = [];

        // Rearrange the data based on the desired order
        foreach ($order as $id) {
            if (isset($itemsById[$id])) {
                $rearrangedItems[] = $itemsById[$id];
            }
        }
        return $rearrangedItems;
    }



    public function getTotalItemsAttribute($value)
    {
        $totalMovies = Movies::whereIn('id', $this->movie_ids)->count();
        $totalSeries = Series::whereIn('id', $this->series_ids)->count();
        $totalLiveTv = LiveTV::whereIn('id', $this->livetv_ids)->count();

        return $totalMovies + $totalSeries + $totalLiveTv;
    }
}
