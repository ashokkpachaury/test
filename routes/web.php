<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\User;


Route::get('migrate', function () {
    return  Artisan::call('migrate');
});

Route::get('clear-feed', function () {
    DB::table('episodes')->truncate();
    DB::table('channels_list')->truncate();
    DB::table('genres')->truncate();
    DB::table('movie_videos')->truncate();
    DB::table('season')->truncate();
    DB::table('series')->truncate();
    return redirect('admin/dashboard');
});


Route::get('/', function () {
    return view('admin.index');
});

Route::get('change-pass', function () {
    $user = User::find(1);
    $user->password = bcrypt('123456');
    $user->save();
});

Route::get('migrate', function () {
    Artisan::call('migrate');
});


Route::get('clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return view('admin.index');
});

Route::get('test', [TestController::class, 'test']);

Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

Route::get('password/reset/{token}', 'Auth\PasswordController@getReset')->name('password_reset_form');
Route::post('password/reset', 'Auth\PasswordController@postReset')->name('password.reset');

Route::group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'admin'], function () {

    Route::get('/', 'IndexController@index');

    Route::get('login', ['as' => 'login', 'uses' => 'IndexController@index']);

    Route::post('login', 'IndexController@postLogin');
    Route::get('logout', 'IndexController@logout');

    Route::get('dashboard', 'DashboardController@index')->name('admin.dashbord');
    Route::get('revenue', 'RevenueController@index');
    Route::get('profile', 'AdminController@profile');
    Route::post('profile', 'AdminController@updateProfile');

    //Introducing User Profile Images (Admin Defined) Just Selectable by user as like in primevideo and netflix
    Route::get('user_profile_images', 'UserProfileImagesController@user_profile_images')->name('user_profile_images');
    Route::get('add_user_profile_image', 'UserProfileImagesController@add_user_profile_image')->name('add_user_profile_image');
    Route::get('edit_user_profile_image/{id}', 'UserProfileImagesController@edit_user_profile_image')->name('edit_user_profile_image');
    Route::post('save_user_profile_image', 'UserProfileImagesController@save_user_profile_image')->name('save_user_profile_image');
    Route::get('delete_user_profile_image/{id}', 'UserProfileImagesController@delete_user_profile_image')->name('delete_user_profile_image');

    Route::get('user_profiles', 'UserProfilesController@user_profiles')->name('user_profiles');


    Route::get('settings', 'SettingsController@settings');

    Route::get('users_import', 'UsersController@user_import_list');
    Route::post('uploadimport', 'UsersController@uploadFile');

    Route::get('import_feed', 'MoviesController@movieImport');
    Route::post('upload_feed', 'MoviesController@uploadFile');

    Route::get('find_imdb_movie', 'ImportImdbController@find_imdb_movie');
    Route::get('find_imdb_show', 'ImportImdbController@find_imdb_show');
    Route::get('find_imdb_episode', 'ImportImdbController@find_imdb_episode');

    Route::get('genres', 'GenresController@genres_list');
    Route::get('genres/add_genre', 'GenresController@addGenre');
    Route::get('genres/edit_genre/{id}', 'GenresController@editGenre');
    Route::post('genres/add_edit_genre', 'GenresController@addnew');
    Route::get('genres/delete/{id}', 'GenresController@delete');

    Route::get('movies', 'MoviesController@movies_list');
    Route::get('movies/add_movie', 'MoviesController@addMovie');
    Route::get('movies/edit_movie/{id}', 'MoviesController@editMovie');
    Route::post('movies/add_edit_movie', 'MoviesController@addnew');
    Route::get('movies/delete/{id}', 'MoviesController@delete');

    Route::get('series', 'SeriesController@series_list');
    Route::get('series/add_series', 'SeriesController@addSeries');
    Route::get('series/edit_series/{id}', 'SeriesController@editSeries');
    Route::post('series/add_edit_series', 'SeriesController@addnew');
    Route::get('series/delete/{id}', 'SeriesController@delete');

    Route::get('season', 'SeasonController@season_list');
    Route::get('season/add_season', 'SeasonController@addSeason');
    Route::get('season/edit_season/{id}', 'SeasonController@editSeason');
    Route::post('season/add_edit_season', 'SeasonController@addnew');
    Route::get('season/delete/{id}', 'SeasonController@delete');

    Route::get('episodes', 'EpisodesController@episodes_list');
    Route::get('episodes/add_episode', 'EpisodesController@addEpisode');
    Route::get('episodes/edit_episode/{id}', 'EpisodesController@editEpisode');
    Route::post('episodes/add_edit_episode', 'EpisodesController@addnew');
    Route::get('episodes/delete/{id}', 'EpisodesController@delete');

    Route::get('ajax_get_season/{id}', 'EpisodesController@ajax_get_season_list');

    Route::get('tv_category', 'TvCategoryController@category_list');
    Route::get('tv_category/add_category', 'TvCategoryController@addCategory');
    Route::get('tv_category/edit_category/{id}', 'TvCategoryController@editCategory');
    Route::post('tv_category/add_edit_category', 'TvCategoryController@addnew');
    Route::get('tv_category/delete/{id}', 'TvCategoryController@delete');

    Route::get('live_tv', 'LiveTvController@live_tv_list');
    Route::get('live_tv/add_live_tv', 'LiveTvController@addTv');
    Route::get('live_tv/edit_live_tv/{id}', 'LiveTvController@editTv');
    Route::post('live_tv/add_edit_live_tv', 'LiveTvController@addnew');
    Route::get('live_tv/delete/{id}', 'LiveTvController@delete');

    Route::get('slider', 'SliderController@slider_list');
    Route::get('slider/add_slider', 'SliderController@addSlider');
    Route::get('slider/edit_slider/{id}', 'SliderController@editSlider');
    Route::post('slider/add_edit_slider', 'SliderController@addnew');
    Route::get('slider/delete/{id}', 'SliderController@delete');


    Route::resource('home-section', 'SectionController')->middleware(['auth']);
    Route::get('/get-options', 'SectionController@getOptions')->name('get.options');
    Route::get('/get-Genre-data', 'SectionController@GetGenreData')->name('get.Genre.data');

    Route::get('home-section/delete/{id}', 'SectionController@destory')->name('home-section.destroy');
    Route::get('home-section-update-order', 'SectionController@HomeSectionUpdateOrder')->name('home-section-update-order');
    Route::post('home-section-update-order', 'SectionController@HomeSectionUpdateOrderSequence')->name('home-section-update-order-post');
    
    Route::get('home-section/dublicate/{id}', 'SectionController@Clone')->name('home-section.dublicate');
    //    Route::get('home-section/delete/{id}', 'SliderController@delete');

    Route::get('home_section', 'HomeSectionController@home_section');
    Route::post('home_section', 'HomeSectionController@update_home_section');
    Route::resource('items', 'ItemController');

    Route::get('users', 'UsersController@user_list');
    Route::get('users/add_user', 'UsersController@addUser');
    Route::get('users/edit_user/{id}', 'UsersController@editUser');
    Route::post('users/add_edit_user', 'UsersController@addnew');
    Route::get('users/delete/{id}', 'UsersController@delete');
    Route::get('users/history/{id}', 'UsersController@user_history');
    Route::get('users/export', 'UsersController@user_export');

    Route::get('users_subscription', 'UsersController@users_subscription_list')->name('users_subscription');
    Route::get('users/subprofiles/{id}', 'UsersController@subprofiles_list');

    Route::get('sub_admin', 'UsersController@admin_user_list');
    Route::get('sub_admin/add_user', 'UsersController@admin_addUser');
    Route::get('sub_admin/edit_user/{id}', 'UsersController@admin_editUser');
    Route::post('sub_admin/add_edit_user', 'UsersController@admin_addnew');
    Route::get('sub_admin/delete/{id}', 'UsersController@admin_delete');

    Route::get('moderators', 'UsersController@moderators_list');
    Route::get('moderators/add_moderator', 'UsersController@addModerator');
    Route::get('moderators/edit_moderator/{id}', 'UsersController@editModerator');
    Route::post('moderators/add_edit_moderator', 'UsersController@addnewModerator');
    Route::get('moderators/delete/{id}', 'UsersController@moderator_delete');

    Route::get('subscription_plan/{platform?}', 'SubscriptionPlanController@subscription_plan_list')->name("subscription_plan");
    Route::get('plateform/delete/{platform?}', 'SubscriptionPlanController@DeletePlatform')->name("DeletePlatform");
    Route::get('subscription_plan/add_plan/{platform}', 'SubscriptionPlanController@addSubscriptionPlan');
    Route::get('subscription_plan/edit_plan/{id}', 'SubscriptionPlanController@editSubscriptionPlan');
    Route::post('subscription_plan/add_edit_plan', 'SubscriptionPlanController@addnew');
    Route::get('subscription_plan/delete/{id}', 'SubscriptionPlanController@delete');

    Route::get('platform/add_platform', 'SubscriptionPlanController@addplatform');
    Route::post('platform/add_edit_platform', 'SubscriptionPlanController@addnewplatform');

    Route::get('coupons', 'CouponsController@coupons_list');
    Route::get('coupons/add_coupon', 'CouponsController@addCoupon');
    Route::get('coupons/edit_coupon/{id}', 'CouponsController@editCoupon');
    Route::post('coupons/add_edit_coupon', 'CouponsController@addnew');
    Route::get('coupons/delete/{id}', 'CouponsController@delete');

    Route::get('transactions', 'TransactionsController@transactions_list');
    Route::get('transactions/export', 'TransactionsController@transactions_export');

    Route::get('about_page', 'PagesController@about_page');
    Route::post('about_page', 'PagesController@update_about_page');
    Route::get('terms_page', 'PagesController@terms_page');
    Route::post('terms_page', 'PagesController@update_terms_page');
    Route::get('privacy_policy_page', 'PagesController@privacy_policy_page');
    Route::post('privacy_policy_page', 'PagesController@update_privacy_policy_page');
    Route::get('faq_page', 'PagesController@faq_page');
    Route::post('faq_page', 'PagesController@update_faq_page');
    Route::get('contact_page', 'PagesController@contact_page');
    Route::post('contact_page', 'PagesController@update_contact_page');

    Route::get('general_settings', 'SettingsController@general_settings');
    Route::post('general_settings', 'SettingsController@update_general_settings');
    Route::get('email_settings', 'SettingsController@email_settings');
    Route::post('email_settings', 'SettingsController@update_email_settings');
    Route::get('payment_settings', 'SettingsController@payment_settings');
    Route::post('payment_settings', 'SettingsController@update_payment_settings');
    Route::get('social_login_settings', 'SettingsController@social_login_settings');
    Route::post('social_login_settings', 'SettingsController@update_social_login_settings');

    Route::get('player_settings', 'SettingsPlayerController@player_settings');
    Route::post('player_settings', 'SettingsPlayerController@update_player_settings');

    Route::get('ads_list', 'AdsController@ads_list');
    Route::get('ads_list/add_ads', 'AdsController@addAds');
    Route::get('ads_list/ads_edit/{id}', 'AdsController@ads_edit');
    Route::post('ads_list/add_ads_edit', 'AdsController@addnew');
    Route::get('ads_list/delete/{id}', 'AdsController@delete');

    Route::get('push_settings', 'SettingsController@push_settings');
    Route::post('push_settings', 'SettingsController@update_push_settings');

    Route::get('push_notification', 'PushNotificationController@pushNotification_list')->name('notification.index');
    Route::get('push_notification/add_notification', 'PushNotificationController@addPushNotification');
    Route::get('push_notification/edit_notification/{id}', 'PushNotificationController@editPushNotification');
    Route::post('push_notification/add_edit_notification', 'PushNotificationController@addnew');
    Route::get('push_notification/delete/{id}', 'PushNotificationController@delete');
    Route::get('push_notification/send/{id}', 'PushNotificationController@resend')->name('notification-push.resend');

    Route::get('android_settings', 'SettingsAndroidAppController@android_settings');
    Route::post('android_settings', 'SettingsAndroidAppController@update_android_settings');
    Route::get('android_notification', 'SettingsAndroidAppController@android_notification');
    Route::post('android_notification', 'SettingsAndroidAppController@send_android_notification');
});

Route::group(['namespace' => 'Moderator', 'prefix' => 'moderator'], function () {
    Route::get('/', 'IndexController@index');

    Route::get('login', ['as' => 'login', 'uses' => 'IndexController@index']);

    Route::post('login', 'IndexController@postLogin');
    Route::get('logout', 'IndexController@logout');

    Route::get('dashboard', 'DashboardController@index');
    Route::get('profile', 'ModeratorController@profile');
    Route::post('profile', 'ModeratorController@updateProfile');

    Route::get('bank', 'ModeratorController@bank');
    Route::post('bank', 'ModeratorController@updateBank');

    Route::get('movies', 'MoviesController@movies_list');
    Route::get('movies/add_movie', 'MoviesController@addMovie');
    Route::get('movies/edit_movie/{id}', 'MoviesController@editMovie');
    Route::post('movies/add_edit_movie', 'MoviesController@addnew');
    Route::get('movies/delete/{id}', 'MoviesController@delete');

    Route::get('series', 'SeriesController@series_list');
    Route::get('series/add_series', 'SeriesController@addSeries');
    Route::get('series/edit_series/{id}', 'SeriesController@editSeries');
    Route::post('series/add_edit_series', 'SeriesController@addnew');
    Route::get('series/delete/{id}', 'SeriesController@delete');

    Route::get('season', 'SeasonController@season_list');
    Route::get('season/add_season', 'SeasonController@addSeason');
    Route::get('season/edit_season/{id}', 'SeasonController@editSeason');
    Route::post('season/add_edit_season', 'SeasonController@addnew');
    Route::get('season/delete/{id}', 'SeasonController@delete');

    Route::get('episodes', 'EpisodesController@episodes_list');
    Route::get('episodes/add_episode', 'EpisodesController@addEpisode');
    Route::get('episodes/edit_episode/{id}', 'EpisodesController@editEpisode');
    Route::post('episodes/add_edit_episode', 'EpisodesController@addnew');
    Route::get('episodes/delete/{id}', 'EpisodesController@delete');

    Route::get('ajax_get_season/{id}', 'EpisodesController@ajax_get_season_list');

    Route::get('live_tv', 'LiveTvController@live_tv_list');
    Route::get('live_tv/add_live_tv', 'LiveTvController@addTv');
    Route::get('live_tv/edit_live_tv/{id}', 'LiveTvController@editTv');
    Route::post('live_tv/add_edit_live_tv', 'LiveTvController@addnew');
    Route::get('live_tv/delete/{id}', 'LiveTvController@delete');
});
