<?php

namespace App\Http\Controllers\API;

use App\Genres;
use App\Http\Controllers\Controller;
use App\Movies;
use App\Series;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function movies(Request $request)
    {
        $genres = Genres::query()->where('status', 1)->orderby('id')
            ->select('id', 'genre_name as name', 'genre_slug as slug', 'status', 'genres_image')
            ->get();
//            ->paginate(config('data.per_page'));

        $genres->map(function ($genres) {
            $items = Movies::where('status', 1)
                ->select('id', 'movie_genre_id', 'video_title as title', 'release_date as releaseDate', 'duration', 'video_image_thumb', 'video_access as videoAccess')
                ->whereRaw("find_in_set('$genres->id',movie_genre_id)")
                ->orderBy('id', 'ASC');
            $count = $items->count();
            if (!$count) {
                $genres->movies = null;
            } else {
                $genres->movies = [
                    'totalItems' => $count,
                    'items' => $items->limit(10)->get(),
                ];
            }
        });
        $data = [];
        foreach ($genres as $genre) {
            if (isset($genre->movies) && !empty($genre->movies)) {
                $data[] = $genre;
            }
        }
        return response()->json([
            'status' => true,
            'data' => $data,
//            'data' => $genres->items(),
//            'currentPage' => $genres->currentPage(),
//            'last_page' => $genres->lastPage(),
//            'total' => $genres->total(),
            'msg' => ''
        ]);
    }

    public function series(Request $request)
    {
        $genres = Genres::query()
            ->select('id', 'genre_name as name', 'genre_slug as slug', 'status', 'genres_image')
            ->where('status', 1)->orderby('id')
            ->get();
//            ->paginate(config('data.per_page'));

        $genres->map(function ($genres) {
            $items = Series::where('status', 1)
                ->select(
                    'id', 'series_name as name', 'series_slug as slug', 'series_poster as series_poster', 'status'
//                    ,'movie_genre_id', 'video_title', 'release_date', 'duration', 'video_image_thumb', 'video_access'
                )
                ->whereRaw("find_in_set('$genres->id',series_genres)")
                ->orderBy('id', 'ASC');
            $count = $items->count();
            if (!$count) {
                $genres->series = null;
            } else {
                $genres->series = [
                    'totalItems' => $count,
                    'items' => $items->limit(10)->get(),
                ];
            }

        });
        $data = [];
        foreach ($genres as $genre) {
            if (isset($genre->series) && !empty($genre->series)) {
                $data[] = $genre;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $data,
//            'data' => $genres->items(),
//            'currentPage' => $genres->currentPage(),
//            'last_page' => $genres->lastPage(),
//            'total' => $genres->total(),
            'msg' => ''
        ]);
    }


}
