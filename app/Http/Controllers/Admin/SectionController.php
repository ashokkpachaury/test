<?php

namespace App\Http\Controllers\Admin;

use App\Genres;
use App\LiveTV;
use App\Section;
use App\Slider;
use App\Sports;
use Auth;
use App\User;
use App\HomeSection;
use App\Language;
use App\Movies;
use App\Series;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redirect;

class SectionController extends MainAdminController

{
    public function index()
    {
        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title = trans('words.home_section');
        $items = Section::orderByRaw('IF(ISNULL(order_index), 1, 0), order_index')->paginate(10);

        return view('admin.home-section.index', compact('page_title', 'items'));
    }

    public function GetGenreData(Request $request)
    {

        if ($request->GenreType == "Series") {
            $data = Series::orderBy('id', 'DESC')->select('id', 'name')->where('series_genres', $request->GenreId)->get();
            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->series_slug,
                    'name' => $movie->name,
                ];
            });
        } else {
            $data = Movies::orderBy('id', 'DESC')->where('movie_genre_id', $request->GenreId)->get();
            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->video_slug,
                    'name' => $movie->title,
                ];
            });
        }
        return response()->json($list);
    }

    public function Custom($GenreValue)
    {

        if ($GenreValue == "Series") {
            $data = Series::orderBy('id', 'DESC')->select('id', 'name')->get();

            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->series_slug,
                    'name' => $movie->name,
                ];
            });
        } else if ($GenreValue == "Movie") {
            $data = Movies::orderBy('id', 'DESC')->get();
            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->video_slug,
                    'name' => $movie->title,
                ];
            });
        } else {
            $data = LiveTV::orderBy('id', 'DESC')->get();
            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->channel_slug,
                    'name' => $movie->name,
                ];
            });
        }
        return $list;
    }

    public function GenreValue($GenreValue)
    {

        if ($GenreValue == "Series") {
            $list = Genres::with('series')->has('series')->get();
        } else {
            $list = Genres::with('movies')->has('movies')->get();
        }
        return $list;
    }

    public function getOptions(Request $request)
    {

        if ($request->selectedValue == "Series") {
            $data = Series::orderBy('id', 'DESC')->select('id', 'name')->get();

            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->series_slug,
                    'name' => $movie->name,
                ];
            });
        } else if ($request->selectedValue == "Movie") {
            $data = Movies::orderBy('id', 'DESC')->get();
            $list = $data->map(function ($movie) {
                return [
                    'id' => $movie->video_slug,
                    'name' => $movie->title,
                ];
            });
        } else if ($request->selectedValue == "Custom" || $request->selectedValue == "Slider") {

            $list = $this->Custom($request->GenreValue);
        } else if ($request->selectedValue == "Genres") {
            $genres = Genres::query()->where('status', 1)->orderby('id')
            ->select('id', 'genre_name as name')
            ->get();
//            ->paginate(config('data.per_page'));

            $genres->map(function ($genres) {
                $items = Movies::where('status', 1)
                    ->select('id', 'movie_genre_id')
                    ->whereRaw("movie_genre_id LIKE '%".$genres->id."%'")
                    ->orderBy('id', 'ASC');
                $count = $items->count();
                if ($count) {
                    $genres->movies = [
                        'totalItems' => $count,
                        'items' => $items->limit(10)->get(),
                    ];
                    $genres->name = stripslashes($genres->name);
                } 
                
            });
            $genres->map(function ($genres) {
                $items1 = Series::where('status', 1)
                    ->select('id', 'series_genres')
                    ->whereRaw("series_genres LIKE '%".$genres->id."%'")
                    ->orderBy('id', 'ASC');
                $count1 = $items1->count();
                if ($count1) {
                    $genres->movies = [
                        'totalItems' => $count1,
                        'items' => $items1->limit(10)->get(),
                    ];
                    $genres->name = stripslashes($genres->name);
                }                 
            });
            
            $list = [];
            foreach ($genres as $genre) {
                if (isset($genre->movies)) {
                    //unset($genre->movies);
                    if(!in_array($genre,$list)){
                        $list[] = $genre;
                    }
                    
                }
            }
            
            
            //$list = Genres::has('series')->has('movies')->orderBy('id', 'DESC')->get();
        }
        return response()->json($list);
    }



    public function create(Request $request)
    {

        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $id = $request->id;
        if ($id) {
            $item = Section::query()->findOrFail($id);

            $allIdsWithTitle = [];

            if (!empty($item->slug)) {
                foreach ($item->slug as $slug) {

                    if ($item->type == "Custom" || $item->type == "Slider") {
                        $liveTvItem = LiveTV::where('channel_slug', $slug)->first();
                    }
                    $seriesItem = Series::where('series_slug', $slug)->first();
                    $movieItem = Movies::where('video_slug', $slug)->first();

                    // Check if each item exists and add its ID and title to the $allIdsWithTitle array
                    if (!empty($liveTvItem)) {
                        $allIdsWithTitle[] = [
                            'id' => $liveTvItem->channel_slug,
                            'name' => $liveTvItem->name
                        ];
                    }
                    if ($seriesItem) {
                        $allIdsWithTitle[] = [
                            'id' => $seriesItem->series_slug,
                            'name' => $seriesItem->name
                        ];
                    }
                    if ($movieItem) {
                        $allIdsWithTitle[] = [
                            'id' => $movieItem->video_slug,
                            'name' => $movieItem->title
                        ];
                    }
                }
            }
            $item->slug = $allIdsWithTitle;


            //print_r($item->slug);die;

        } else {
            $item = new Section();
        }

        $page_title = trans('words.home_section');

        $language_list = Language::orderBy('language_name')->get();

        if (($item->type == "Movie")) {
            $movies_data =  Movies::orderBy('id', 'DESC')->get();
            $movies_list = $movies_data->map(function ($movie) {
                return [
                    'id' => $movie->video_slug,
                    'name' => $movie->title,
                ];
            });
        } else {
            $movies_list = [];
        }

        if (($item->type == "Series")) {
            $series_data = Series::orderBy('id', 'DESC')->select('id', 'name')->get();
            $series_list = $series_data->map(function ($movie) {
                return [
                    'id' => $movie->series_slug,
                    'name' => $movie->name,
                ];
            });
        } else {
            $series_list = [];
        }



        if (($item->type == "Genres")) {
            $genres_list = $list = Genres::has('series')->has('movies')->where('id', $item->genre_id)->orderBy('id', 'DESC')->get();
        } else {
            $genres_list = [];
        }
        return view('admin.home-section.form', compact('page_title', 'language_list', 'movies_list', 'series_list', 'genres_list', 'item'));
    }


    public function store(Request $request)
    {
        //return $request;

        $id = $request->id;
        $data = $request->validate([
            'title' => 'required',
            //   'type' => 'required',
            'ids' => 'required|array|min:1',
            'status' => 'required',
            'sub_type' => 'required_if:type,Genres,Custom',
        ]);


        $data['is_highlight'] = $request->boolean('is_highlight');
        if (!empty($request->Genre_id)) {
            $data['genre_id'] = $request->Genre_id;
        }


        $slugIds = $request->ids;

        $liveTvItems = LiveTV::whereIn('channel_slug', $slugIds)->get();
        $seriesItems = Series::whereIn('series_slug', $slugIds)->get();
        $movieItems = Movies::whereIn('video_slug', $slugIds)->get();

        $liveTvIds = $liveTvItems->pluck('id')->toArray();
        $seriesIds = $seriesItems->pluck('id')->toArray();
        $movieIds = $movieItems->pluck('id')->toArray();


        $allIds = [];

        foreach ($slugIds as $slugId) {
            $liveTvItem = LiveTV::where('channel_slug', $slugId)->first();
            $seriesItem = Series::where('series_slug', $slugId)->first();
            $movieItem = Movies::where('video_slug', $slugId)->first();

            // Check if each item exists and add its ID to the $allIds array
            if ($liveTvItem) {
                $allIds[] = $liveTvItem->id;
            }
            if ($seriesItem) {
                $allIds[] = $seriesItem->id;
            }
            if ($movieItem) {
                $allIds[] = $movieItem->id;
            }
        }


        if ($id) {
            $item = Section::query()->findOrFail($id);
            $data['type'] = $item->type;
        } else {
            $data['type'] = $request->type;
            $item = new Section();
        }


        $data['order'] = $allIds;
        $data['slug'] = $request->ids;

        if ($data['type'] == "Custom" || $data['type'] == "Slider") {
            $data['livetv_ids'] = $liveTvIds;
        }
        $data['series_ids'] = $seriesIds;
        $data['movie_ids'] = $movieIds;


        // return $data;
        $item->fill($data);
        $item->save();

        if ($id) {
            \Session::flash('flash_message', trans('words.successfully_updated'));
            return back();
        }
        \Session::flash('flash_message', trans('words.added'));
        return redirect()->route('home-section.index');
    }


    public function delete($slider_id)
    {
        if (Auth::User()->usertype == "Admin" or Auth::User()->usertype == "Sub_Admin") {

            $slider_obj = Slider::findOrFail($slider_id);
            $slider_obj->delete();

            \Session::flash('flash_message', trans('words.deleted'));
            return redirect()->back();
        } else {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        }
    }

    public function destory($id)
    {
        $item = Section::query()->findOrFail($id);
        $item->delete();
        \Session::flash('flash_message', trans('Successfully Deleted'));
        return back();
    }

    public function HomeSectionUpdateOrder(Request $request)
    {
        foreach ($request->order as $id => $orderIndex) {
            Section::where('id', $id)->update(['order_index' => $orderIndex]);
        }
        return response()->json(['message' => 'Order updated successfully']);
    }
    public function Clone($id)
    {

        $originalSection = Section::find($id);
        $clonedSection = new Section();
        $clonedSection->title = $originalSection->title;
        $clonedSection->type = $originalSection->type;
        $clonedSection->movie_ids = $originalSection->movie_ids;
        $clonedSection->series_ids = $originalSection->series_ids;
        $clonedSection->status = $originalSection->status;
        $clonedSection->is_highlight = $originalSection->is_highlight;
        $clonedSection->order_index = $originalSection->order_index;
        $clonedSection->save();
        \Session::flash('flash_message', "Section cloned successfully");
        return Redirect::route('home-section.index');
    }
}
