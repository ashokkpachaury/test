<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Movies;
use App\Series;
use App\Season;
use App\Genres;
use App\Episodes;
use App\LiveTV;
use App\Language;
use App\RecentlyWatched;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;


use GuzzleHttp\Client;

class MoviesController extends MainAdminController
{
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
        check_verify_purchase();
    }
    public function movies_list()
    {
        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }

        $page_title = trans('words.movies_text');

        $language_list = Language::orderBy('language_name')->get();

        if (isset($_GET['s'])) {
            $keyword = $_GET['s'];
            $movies_list = Movies::where("video_title", "LIKE", "%$keyword%")->orderBy('video_title')->paginate(10);

            $movies_list->appends(\Request::only('s'))->links();
        } else if (isset($_GET['language_id'])) {
            $language_id = $_GET['language_id'];
            $movies_list = Movies::where("movie_lang_id", "=", $language_id)->orderBy('id', 'DESC')->paginate(10);

            $movies_list->appends(\Request::only('language_id'))->links();
        } else {
            $movies_list = Movies::orderBy('id', 'DESC')->paginate(10);
        }

        return view('admin.pages.movies_list', compact('page_title', 'movies_list', 'language_list'));
    }

    public function addMovie()
    {

        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
        }

        $page_title = trans('words.add_movie');

        $language_list = Language::orderBy('language_name')->get();
        $genre_list = Genres::orderBy('genre_name')->get();

        return view('admin.pages.addeditmovie', compact('page_title', 'language_list', 'genre_list'));
    }

    public function addnew(Request $request)
    {

        $data =  \Request::except(array('_token'));

        if (!empty($inputs['id'])) {
            $rule = array(
                'movie_language' => 'required',
                'genres' => 'required',
                'video_title' => 'required'
            );
        } else {
            $rule = array(
                'movie_language' => 'required',
                'genres' => 'required',
                'video_title' => 'required',
                'video_image_thumb' => 'required'
            );
        }

        $validator = \Validator::make($data, $rule);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }
        $inputs = $request->all();

        if (!empty($inputs['id'])) {

            $movie_obj = Movies::findOrFail($inputs['id']);
        } else {

            $movie_obj = new Movies;
        }

        $video_slug = Str::slug($inputs['video_title'], '-');


        $movie_obj->video_access = $inputs['video_access'];
        $movie_obj->movie_lang_id = $inputs['movie_language'];
        $movie_obj->movie_genre_id = implode(',', $inputs['genres']);
        $movie_obj->video_title = addslashes($inputs['video_title']);
        $movie_obj->video_slug = $video_slug;
        $movie_obj->video_description = addslashes($inputs['video_description']);

        $movie_obj->release_date = strtotime($inputs['release_date']);
        $movie_obj->duration = $inputs['duration'];


        $movie_obj->video_type = $inputs['video_type'];

        if (isset($inputs['video_quality'])) {
            $movie_obj->video_quality = $inputs['video_quality'];
        }

        if ($inputs['video_type'] == "URL") {
            $movie_obj->video_url = $inputs['video_url'];

            $movie_obj->video_url_480 = $inputs['video_url_480'];
            $movie_obj->video_url_720 = $inputs['video_url_720'];
            $movie_obj->video_url_1080 = $inputs['video_url_1080'];
        } else if ($inputs['video_type'] == "Embed") {
            $movie_obj->video_url = $inputs['video_embed_code'];
        } else if ($inputs['video_type'] == "HLS") {

            $movie_obj->video_url = $inputs['video_url_hls'];
        } else if ($inputs['video_type'] == "DASH") {

            $movie_obj->video_url = $inputs['video_url_dash'];
        } else {

            $movie_obj->video_url = $inputs['video_url_local'];

            $movie_obj->video_url_480 = $inputs['video_url_local_480'];
            $movie_obj->video_url_720 = $inputs['video_url_local_720'];
            $movie_obj->video_url_1080 = $inputs['video_url_local_1080'];
        }

        //$movie_obj->video_url = $video_url;

        $movie_obj->video_image_thumb = $inputs['video_image_thumb'];
        $movie_obj->video_image = $inputs['video_image'];

        if (isset($inputs['thumb_link']) && $inputs['thumb_link'] != '') {
            $image_source           =   $inputs['thumb_link'];
            $save_to                =   public_path('/upload/source/' . $inputs['video_image_thumb']);
            grab_image($image_source, $save_to);
        }

        $movie_obj->imdb_id = $inputs['imdb_id'];
        $movie_obj->imdb_rating = $inputs['imdb_rating'];
        $movie_obj->imdb_votes = $inputs['imdb_votes'];


        $movie_obj->status = $inputs['status'];

        if (isset($inputs['download_enable'])) {
            $movie_obj->download_enable = $inputs['download_enable'];
            $movie_obj->download_url = $inputs['download_url'];
        }

        if (isset($inputs['subtitle_on_off'])) {
            $movie_obj->subtitle_on_off = $inputs['subtitle_on_off'];
        }

        $movie_obj->subtitle_language1 = $inputs['subtitle_language1'];
        $movie_obj->subtitle_url1 = $inputs['subtitle_url1'];
        $movie_obj->subtitle_language2 = $inputs['subtitle_language2'];
        $movie_obj->subtitle_url2 = $inputs['subtitle_url2'];
        $movie_obj->subtitle_language3 = $inputs['subtitle_language3'];
        $movie_obj->subtitle_url3 = $inputs['subtitle_url3'];


        $movie_obj->seo_title = addslashes($inputs['seo_title']);
        $movie_obj->seo_description = addslashes($inputs['seo_description']);
        $movie_obj->seo_keyword = addslashes($inputs['seo_keyword']);



        $movie_obj->save();


        if (!empty($inputs['id'])) {

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        } else {

            \Session::flash('flash_message', trans('words.added'));

            return \Redirect::back();
        }
    }

    public function editMovie($movie_id)
    {
        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
        }

        $page_title = trans('words.edit_movie');

        $language_list = Language::orderBy('language_name')->get();
        $genre_list = Genres::orderBy('genre_name')->get();

        $movie = Movies::findOrFail($movie_id);

        return view('admin.pages.addeditmovie', compact('page_title', 'movie', 'language_list', 'genre_list'));
    }

    public function delete($movie_id)
    {
        if (Auth::User()->usertype == "Admin" or Auth::User()->usertype == "Sub_Admin") {

            $recently = RecentlyWatched::where('video_type', 'Movies')->where('video_id', $movie_id)->delete();

            $movie = Movies::findOrFail($movie_id);
            $movie->delete();

            \Session::flash('flash_message', trans('words.deleted'));

            return redirect()->back();
        } else {
            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
        }
    }

    public function movieImport()
    {
        if (Auth::User()->usertype != "Admin" and Auth::User()->usertype != "Sub_Admin") {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title = 'Import from feed';
        return view('admin.pages.movieimport', compact('page_title'));
    }

    public function uploadFile(Request $request)
    {
        try {
            $inputs = $request->all();
            $url = $inputs['import_url'];

            $client = new Client();
            $response = $client->get($url, [
                'verify' => false, // Disable SSL verification (use with caution)
            ]);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $json_data = json_decode($response->getBody(), true);
            } else {
                return 'HTTP error code: ' . $statusCode;
            }


            $insertCategories = array();
            $updateCategories = array();

            $insertMovies = array();
            $updateMovies = array();

            $insertSeries = array();
            $updateSeries = array();

            $insertLiveFeeds = array();
            $updateLiveFeeds = array();

            if (!empty($json_data['categories'])) {

                $categories = $json_data['categories'];
                $categoriesCount = count($categories);

                foreach ($categories as $keyC => $category) {
                    $category_slug = Str::slug($category['query'], '-');
                    if (!empty($category_slug)) {
                        $category_list = Genres::where('genre_slug', $category_slug)->orderBy('id')->first();
                        if (!empty($category_list['id'])) {
                            $genres_obj = Genres::findOrFail($category_list['id']);

                            $genres_obj->genre_name = addslashes($category['name']);
                            $genres_obj->genre_slug = strtolower($category_slug);
                            $genres_obj->save();
                        } else {
                            //$genres_obj = new Genres;
                            $genres_obj = new Genres();

                            $genres_obj->genre_name = addslashes($category['name']);
                            $genres_obj->genre_slug = strtolower($category_slug);
                            $genres_obj->save();
                        }
                    }
                }
                //if (!empty($insertCategories)) {
                    //Genres::insert($insertCategories);
                //}
            }
            
            if (!empty($json_data['movies'])) {
                // echo  "data"; die();
                $movies = $json_data['movies'];
                $movieCount = count($movies);
                $chunkdata = array_chunk($movies, 50);
                foreach ($chunkdata as $keyChunk => $inputsChunk) {
                    //if($keyChunk == 10){
                    foreach ($inputsChunk as $key => $inputs) {
                        $video_slug = Str::slug($inputs['title'], '-');
                        if (!empty($video_slug)) {
                            $movie_list = Movies::where('video_slug', $video_slug)->orderBy('id')->first();
                            if (!empty($movie_list['id'])) {
                                $movie_obj = Movies::findOrFail($movie_list['id']);
                                $movie_allready = "1";
                            } else {
                                $movie_obj = new Movies;
                                $movie_obj->video_access = 'Paid';
                            }

                            
                            $movie_obj->movie_lang_id = '2';
                            if (!empty($inputs['genres'])) {
                                foreach($inputs['genres'] as $keyG => $genreV){
                                    $inputs['genres'][$keyG]=strtolower($genreV);
                                }
                                $genre_slug = $inputs['genres'];
                                $genre_list = Genres::whereIn('genre_slug', $genre_slug)->orWhereIn('genre_name', $genre_slug)->get();
                                if (!empty($genre_list)) {
                                    $genreArray = array();
                                    foreach ($genre_list as $key1 => $genreList) {
                                        $genreArray[$key1] = $genreList['id'];
                                    }
                                    $movie_obj->movie_genre_id = implode(',', $genreArray);
                                }
                                // else {
                                //     $movie_obj->movie_genre_id = '';
                                // }
                            }
                            // else {
                            //     $movie_obj->movie_genre_id = '';
                            // }
                            $movie_obj->video_title = addslashes($inputs['title']);
                            $movie_obj->video_slug = $video_slug;
                            $movie_obj->video_description = addslashes($inputs['longDescription']);
                            $movie_obj->release_date = strtotime($inputs['releaseDate']);
                            $movie_obj->duration = $inputs['content']['duration'];
                            if (!empty($inputs['content']['videos'][0]['videoType']) && ($inputs['content']['videos'][0]['videoType'] == 'MP4')) {
                                $movie_obj->video_type = 'URL';
                            } else if (!empty($inputs['content']['videos'][0]['videoType']) && ($inputs['content']['videos'][0]['videoType'] == 'HLS')) {
                                $movie_obj->video_type = 'HLS';
                            } else {
                                $movie_obj->video_type = 'URL';
                            }
                            $movie_obj->video_quality = '0';

                            if ($movie_obj->video_type == "URL") {
                                $movie_obj->video_url = $inputs['content']['videos'][0]['url'];
                                $movie_obj->video_url_480 = '';
                                $movie_obj->video_url_720 = '';
                                $movie_obj->video_url_1080 = '';
                            } else if ($movie_obj->video_type == "HLS") {
                                $movie_obj->video_url = $inputs['content']['videos'][0]['url'];
                            } else {
                                $movie_obj->video_url = $inputs['content']['videos'][0]['url'];
                                $movie_obj->video_url_480 = '';
                                $movie_obj->video_url_720 = '';
                                $movie_obj->video_url_1080 = '';
                            }
                            if (!empty($inputs['thumbnail'])) {
                                $movie_obj->thumbUrl = $inputs['thumbnail'];
                                //                                $image_source = $inputs['thumbnail'];
                                //                                $pathinfo = pathinfo($image_source);
                                //                                $movie_obj->video_image_thumb = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                //                                $save_to = public_path('/upload/source/'.$movie_obj->video_image_thumb);
                                //                                grab_image($image_source,$save_to);
                            } else {
                                $movie_obj->video_image_thumb = '';
                            }
                            $movie_obj->status = 1;
                            $movie_obj->download_enable = 0;
                            $movie_obj->subtitle_on_off = 0;
                            $movie_obj->seo_title = addslashes($inputs['title']);
                            $movie_obj->seo_description = addslashes($inputs['shortDescription']);
                            $movie_obj->seo_keyword = implode(',', $inputs['tags']);
                            $movie_obj->mediaId = $inputs['id'];
                            $movie_obj->save();
                        }
                    }
                    //}
                }
            }

            if (!empty($json_data['series'])) {
                // echo  "data"; die();
                $seriesData = $json_data['series'];
                $seriesCount = count($seriesData);
                $chunkseriesdata = array_chunk($seriesData, 10);
                foreach ($chunkseriesdata as $keySChunk => $seriesChunk) {
                    //if($keySChunk == 12){
                    foreach ($seriesChunk as $keyS => $series) {
                        $series_slug = Str::slug($series['title'], '-');
                        if (!empty($series_slug)) {
                            $series_list = Series::where('series_slug', $series_slug)->orderBy('id')->first();
                            if (!empty($series_list['id'])) {
                                $series_obj = Series::findOrFail($series_list['id']);
                                $Series_already = "1";
                            } else {
                                $series_obj = new Series;
                            }
                            $series_obj->series_lang_id = '2';
                            if (!empty($series['genres'])) {
                                foreach($series['genres'] as $keyG => $genreV){
                                    $series['genres'][$keyG]=strtolower($genreV);
                                }
                                $genre_slug =$series['genres'];
                                $genre_list = Genres::whereIn('genre_slug', $genre_slug)->orWhereIn('genre_name', $genre_slug)->orderBy('genre_name')->get();
                                if (!empty($genre_list)) {
                                    $genreArray = array();
                                    foreach ($genre_list as $key11 => $genreList) {
                                        $genreArray[$key11] = $genreList['id'];
                                    }
                                    $series_obj->series_genres = implode(',', $genreArray);
                                }
                                // else{
                                //     $series_obj->series_genres = '';
                                // }
                            }
                            // else{
                            //     $series_obj->series_genres = '';
                            // }
                            $series_obj->series_name = addslashes($series['title']);
                            $series_obj->series_slug = $series_slug;
                            $series_obj->series_info = addslashes($series['longDescription']);
                            if (!empty($series['thumbnail'])) {
                                $series_obj->posterUrl = $series['thumbnail'];
                                //                                $image_source = $series['thumbnail'];
                                //                                $pathinfo = pathinfo($image_source);
                                //                                $series_obj->series_poster = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                //                                $series_obj->series_poster = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                //                                $save_to = public_path('/upload/source/'.$series_obj->series_poster);
                                //                                grab_image($image_source,$save_to);
                            } else {
                                $series_obj->series_poster = '';
                            }
                            $series_obj->status = 1;
                            $series_obj->seo_title = addslashes($series['title']);
                            $series_obj->seo_description = addslashes($series['shortDescription']);
                            $series_obj->seo_keyword = implode(',', $series['tags']);
                            $series_obj->mediaId = $series['id'];
                            $series_obj->save();
                            $seriesId = $series_obj->id;
                            if (!empty($seriesId)) {
                                if (!empty($series['seasons'])) {
                                    $seasons = $series['seasons'];
                                    foreach ($seasons as $keySS => $season) {
                                        $season_slug = Str::slug('Season ' . $season['seasonNumber'], '-');
                                        $season_list = Season::where('season_slug', $season_slug)->where('series_id', $seriesId)->orderBy('id')->first();
                                        if (!empty($season_list['id'])) {
                                            $season_obj = Season::findOrFail($season_list['id']);
                                        } else {
                                            $season_obj = new Season;
                                        }
                                        $season_obj->series_id = $seriesId;
                                        $season_obj->season_name = addslashes('Season ' . $season['seasonNumber']);
                                        $season_obj->season_slug = $season_slug;
                                        $season_obj->season_poster = $series_obj->series_poster ? $series_obj->series_poster : $series_obj->posterUrl;
                                        $season_obj->status = 1;
                                        $season_obj->save();
                                        $seasonId = $season_obj->id;
                                        if (!empty($seasonId)) {
                                            if (!empty($season['episodes'])) {
                                                $episodes = $season['episodes'];
                                                foreach ($episodes as $keyE => $episode) {

                                                    $episode_slug = Str::slug($episode['title'], '-');
                                                    $episode_list = Episodes::where('video_slug', $episode_slug)->where('episode_series_id', $seriesId)->where('episode_season_id', $seasonId)->orderBy('id')->first();
                                                    if (!empty($episode_list['id'])) {
                                                        $episode_obj = Episodes::findOrFail($episode_list['id']);
                                                        $genre_allready = "1";
                                                    } else {
                                                        $episode_obj = new Episodes;
                                                        $episode_obj->video_access = 'Paid';
                                                    }

                                                    
                                                    $episode_obj->episode_series_id = $seriesId;
                                                    $episode_obj->episode_season_id = $seasonId;
                                                    $episode_obj->video_title = addslashes($episode['title']);
                                                    $episode_obj->video_slug = $episode_slug;
                                                    $episode_obj->video_description = addslashes($episode['longDescription']);

                                                    if (!empty($episode['genres'])) {
                                                        //$episode_genre_slug = strtolower($episode['genres']);
                                                        foreach($episode['genres'] as $keyG => $genreV){
                                                            $episode['genres'][$keyG]=strtolower($genreV);
                                                        }
                                                        $episode_genre_slug = $episode['genres'];
                                                        $episode_genre_list = Genres::whereIn('genre_slug', $episode_genre_slug)->orWhereIn('genre_name', $episode_genre_slug)->get();
                                                        if (!empty($episode_genre_list)) {
                                                            $episode_genreArray = array();
                                                            foreach ($episode_genre_list as $keyE1 => $episode_genreList) {
                                                                $episode_genreArray[$keyE1] = $episode_genreList['id'];
                                                            }
                                                            $episode_obj->episode_genre_id = implode(',', $episode_genreArray);
                                                        }
                                                    }

                                                    $episode_obj->episode_number = (int)($episode['episodeNumber']);
                                                    $episode_obj->release_date = strtotime($episode['releaseDate']);
                                                    $episode_obj->duration = $episode['content']['duration'];

                                                    if (!empty($episode['content']['videos'][0]['videoType']) && ($episode['content']['videos'][0]['videoType'] == 'MP4')) {
                                                        $episode_obj->video_type = 'URL';
                                                    } else if (!empty($episode['content']['videos'][0]['videoType']) && ($episode['content']['videos'][0]['videoType'] == 'HLS')) {
                                                        $episode_obj->video_type = 'HLS';
                                                    } else {
                                                        $episode_obj->video_type = 'URL';
                                                    }
                                                    $episode_obj->video_quality = '0';

                                                    if ($episode_obj->video_type == "URL") {
                                                        $episode_obj->video_url = $episode['content']['videos'][0]['url'];
                                                        $episode_obj->video_url_480 = '';
                                                        $episode_obj->video_url_720 = '';
                                                        $episode_obj->video_url_1080 = '';
                                                    } else if ($episode_obj->video_type == "HLS") {
                                                        $episode_obj->video_url = $episode['content']['videos'][0]['url'];
                                                    } else {
                                                        $episode_obj->video_url = $episode['content']['videos'][0]['url'];
                                                        $episode_obj->video_url_480 = '';
                                                        $episode_obj->video_url_720 = '';
                                                        $episode_obj->video_url_1080 = '';
                                                    }

                                                    if (!empty($episode['thumbnail'])) {
                                                        $episode_obj->imageUrl = $episode['thumbnail'];
                                                        //                                                        $image_source = $episode['thumbnail'];
                                                        //                                                        $pathinfo = pathinfo($image_source);
                                                        //                                                        $episode_obj->video_image = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                                        //                                                        $save_to = public_path('/upload/source/'.$episode_obj->video_image);
                                                        //                                                        grab_image($image_source,$save_to);
                                                    } else {
                                                        $episode_obj->video_image = '';
                                                    }

                                                    $episode_obj->status = 1;
                                                    $episode_obj->download_enable = 0;
                                                    $episode_obj->subtitle_on_off = 0;

                                                    $episode_obj->seo_title = addslashes($episode['title']);
                                                    $episode_obj->seo_description = addslashes($episode['shortDescription']);
                                                    $episode_obj->seo_keyword = implode(',', $episode['tags']);
                                                    $episode_obj->mediaId = $episode['id'];
                                                    $episode_obj->save();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //}
                }
            }

            if (!empty($json_data['liveFeeds'])) {
                // echo  "data"; die();
                $liveFeeds = $json_data['liveFeeds'];
                $chunkliveFeeds = array_chunk($liveFeeds, 10);
                foreach ($chunkliveFeeds as $keyLChunk => $liveFeedsChunk) {
                    //if($keyLChunk == 8){
                    foreach ($liveFeedsChunk as $keyL => $liveFeed) {
                        $liveFeed_slug = Str::slug($liveFeed['title'], '-');
                        if (!empty($liveFeed_slug)) {
                            $tv_list = LiveTV::where('channel_slug', $liveFeed_slug)->orderBy('id')->first();
                            if (!empty($tv_list['id'])) {
                                $tv_obj = LiveTV::findOrFail($tv_list['id']);
                            } else {
                                $tv_obj = new LiveTV;
                                $tv_obj->channel_access = 'Paid';
                            }
                            $tv_slug = Str::slug($liveFeed['title'], '-');
                            
                            // $tv_obj->channel_cat_id = 0;
                            $tv_obj->channel_name = addslashes($liveFeed['title']);
                            $tv_obj->channel_slug = $tv_slug;
                            $tv_obj->channel_description = addslashes($liveFeed['longDescription']);

                            if (!empty($liveFeed['content']['videos'][0]['videoType']) && ($liveFeed['content']['videos'][0]['videoType'] == 'MP4')) {
                                $tv_obj->channel_url_type = 'url';
                            } else if (!empty($liveFeed['content']['videos'][0]['videoType']) && ($liveFeed['content']['videos'][0]['videoType'] == 'HLS')) {
                                $tv_obj->channel_url_type = 'hls';
                            } else {
                                $tv_obj->channel_url_type = 'url';
                            }

                            if ($tv_obj->channel_url_type == "url") {
                                $tv_obj->channel_url = $liveFeed['content']['videos'][0]['url'];
                            } else if ($tv_obj->channel_url_type == "hls") {
                                $tv_obj->channel_url = $liveFeed['content']['videos'][0]['url'];
                            } else {
                                $tv_obj->channel_url = $liveFeed['content']['videos'][0]['url'];
                            }

                            if (!empty($liveFeed['thumbnail'])) {
                                $tv_obj->thumbUrl = $liveFeed['thumbnail'];
                                //                                $image_source = $liveFeed['thumbnail'];
                                //                                $pathinfo = pathinfo($image_source);
                                //                                $tv_obj->channel_thumb = $pathinfo['filename'].'.'.$pathinfo['extension'];
                                //                                $save_to = public_path('/upload/source/'.$tv_obj->channel_thumb);
                                //                                grab_image($image_source,$save_to);
                            } else {
                                $tv_obj->channel_thumb = '';
                            }
                            $tv_obj->status = 1;

                            $tv_obj->seo_title = addslashes($liveFeed['title']);
                            $tv_obj->seo_description = addslashes($liveFeed['shortDescription']);
                            $tv_obj->seo_keyword = implode(',', $liveFeed['tags']);
                            $tv_obj->mediaId = $liveFeed['id'];
                            $tv_obj->save();
                        }
                    }
                    //}
                }
            }


            Session::flash('flash_message', 'Import Successful.');
            return \Redirect::back();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return redirect()->back()->with('error', 'Request Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            //var_dump($e);
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
