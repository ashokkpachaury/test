<?php

use App\Http\Controllers\API\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\API'], function () {
    Route::get('/', 'AndroidApiController@index');
    Route::post('app_details', 'AndroidApiController@app_details');
    Route::post('player_settings', 'AndroidApiController@player_settings');
    Route::post('payment_settings', 'AndroidApiController@payment_settings');

    //    Route::post('login', 'AndroidApiController@postLogin');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('deleteAccount', 'AuthController@deleteAccount');

    Route::post('signup', 'AndroidApiController@postSignup');
    //user viewer profile create update delete
    Route::post('create_user_profile', 'AndroidApiController@postViewerProfile');
    Route::post('update_user_profile', 'AndroidApiController@updateViewerProfile');
    Route::post('delete_user_profile', 'AndroidApiController@deleteViewerProfile');
    Route::get('get_viewer_profile', 'AndroidApiController@getViewerProfile');

    Route::get('get_viewer_profile_images', 'AndroidApiController@getViewerProfileImages');

    Route::post('login_social', 'AndroidApiController@postSocialLogin');
    Route::post('forgot_password', 'AndroidApiController@forgot_password');
    Route::post('change_password', 'AndroidApiController@change_password');

    Route::post('dashboard', 'AndroidApiController@dashboard');


    Route::post('subprofile', 'AndroidApiController@subprofile');
    Route::post('subprofile_update', 'AndroidApiController@subprofile_update');

    Route::post('subscription_plan', 'AndroidApiController@subscription_plan');

    Route::post('roku_transaction_add', 'AndroidApiController@roku_transaction_add');
    Route::post('roku_transaction_test', 'AndroidApiController@roku_transaction_test');

    Route::post('home', 'AndroidApiController@home');
    Route::post('latest_movies', 'AndroidApiController@latest_movies');
    Route::post('latest_shows', 'AndroidApiController@latest_shows');
    Route::post('popular_movies', 'AndroidApiController@popular_movies');
    Route::post('popular_shows', 'AndroidApiController@popular_shows');

    Route::post('languages', 'AndroidApiController@languages');
    Route::post('genres', 'AndroidApiController@genres');

    Route::post('shows', 'AndroidApiController@shows');
    Route::post('shows_by_language', 'AndroidApiController@shows_by_language');
    Route::post('shows_by_genre', 'AndroidApiController@shows_by_genre');

    Route::post('show_details', 'AndroidApiController@show_details');
    Route::post('seasons', 'AndroidApiController@seasons');
    Route::post('episodes', 'AndroidApiController@episodes');
    Route::post('episodes_recently_watched', 'AndroidApiController@episodes_recently_watched');
    Route::post('episodes_details', 'AndroidApiController@episodes_details');


    Route::post('movies', 'AndroidApiController@movies');
    Route::post('movies_by_language', 'AndroidApiController@movies_by_language');
    Route::post('movies_by_genre', 'AndroidApiController@movies_by_genre');

    Route::post('livetv_category', 'AndroidApiController@livetv_category');
    Route::post('livetv', 'AndroidApiController@livetv');
    Route::post('livetv_by_category', 'AndroidApiController@livetv_by_category');

    Route::post('search', 'AndroidApiController@search');


    Route::post('stripe_token_get', 'AndroidApiController@stripe_token_get');





    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('profile', 'AndroidApiController@profile');
        Route::post('profile_update', 'AndroidApiController@profile_update');
        Route::post('check_user_plan', 'AndroidApiController@check_user_plan');
        Route::post('transaction_add', 'AndroidApiController@transaction_add');
        Route::post('logout', 'AndroidApiController@logout');
        Route::post('subprofile_add', 'AndroidApiController@subprofile_add');
        Route::post('premium_purchase', 'AndroidApiController@PremiumPurchase');
        Route::post('post_user_sync', 'AndroidApiController@post_user_sync');
        Route::get('get_user_sync', 'AndroidApiController@get_user_sync');
        Route::get('get_user_profile_sync', 'AndroidApiController@get_user_profile_sync');

        Route::post('addFavourite', 'AndroidApiController@addFavourite');
        Route::post('removeFavourite', 'AndroidApiController@removeFavourite');
        Route::get('userFavourites', 'AndroidApiController@userFavourites');
    });



    Route::get('home', 'HomeController@index');
    Route::get('home-section/{id}', 'HomeController@single_section');
    Route::get('genres-movies', 'GenreController@movies');
    Route::get('genres-series', 'GenreController@series');
    Route::get('live-tv-categories', 'LivetvController@categories');

    Route::post('movies_details', 'AndroidApiController@movies_details');
    Route::post('livetv_details', 'AndroidApiController@livetv_details');
    Route::post('notify-submit-token', 'NotifyController@add_token');
    Route::get('pages', [PageController::class, 'list']);
});
