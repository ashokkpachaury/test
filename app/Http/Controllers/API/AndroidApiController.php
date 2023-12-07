<?php

namespace App\Http\Controllers\API;

use Auth;
use App\User;
use App\Slider;
use App\Series;
use App\Season;
use App\Episodes;
use App\Movies;
use App\HomeSection;
use App\Sports;
use App\Pages;
use App\RecentlyWatched;
use App\Language;
use App\Genres;
use App\Settings;
use App\SportsCategory;
use App\SubscriptionPlan;
use App\Transactions;
use App\SettingsAndroidApp;
use App\TvCategory;
use App\LiveTV;
use App\Player;
use App\Subprofiles;
use App\PasswordReset;
use App\Models\PremiumPurchase;
use App\Models\Favourite;
use App\UserProfiles;
use App\ProfileImages;
use URL;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Session;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\UserSync;
use App\Models\ProfileSync;
use function PHPUnit\Framework\returnSelf;

require(base_path() . '/public/stripe-php/init.php');

class AndroidApiController extends MainAPIController
{

    public function index()
    {
        $response[] = array('msg' => "API", 'success' => '1');

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    function default_file()
    {
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Headers: Origin,Content-Type ");
        header("Content-Type:application/json ");
        $rest_json = file_get_contents("php://input");
        $_POST = json_decode($rest_json, true);
    }

    public function app_details()
    {
        $this->default_file();
        $get_data = $_POST;
        if (isset($get_data['user_id']) && $get_data['user_id'] != '') {
            $user_id = $get_data['user_id'];
            $user_info = User::getUserInfo($user_id);

            if ($user_info != '' and $user_info->status == 1) {
                $user_status = true;
            } else {
                $user_status = false;
            }
        } else {
            $user_status = false;
        }


        $settings = SettingsAndroidApp::findOrFail('1');

        $app_name = $settings->app_name;
        $app_logo = \URL::to('upload/source/' . $settings->app_logo);
        $app_version = $settings->app_version ? $settings->app_version : '';
        $app_company = $settings->app_company ? $settings->app_company : '';
        $app_email = $settings->app_email;
        $app_website = $settings->app_website ? $settings->app_website : '';
        $app_contact = $settings->app_contact ? $settings->app_contact : '';
        $publisher_id = $settings->publisher_id;

        $banner_ad = $settings->banner_ad;
        $banner_ad_type = $settings->banner_ad_type;
        if ($banner_ad_type == "Facebook") {
            $banner_ad_id = $settings->fb_banner_id;
        } else {
            $banner_ad_id = $settings->banner_ad_id;
        }

        $interstital_ad = $settings->interstital_ad;
        $interstitial_ad_type = $settings->interstitial_ad_type;
        if ($interstitial_ad_type == "Facebook") {
            $interstital_ad_id = $settings->fb_interstitial_id;
        } else {
            $interstital_ad_id = $settings->interstital_ad_id;
        }
        $interstital_ad_click = $settings->interstital_ad_click;


        $settings1 = Settings::findOrFail('1');
        $fb_link = $settings1->footer_fb_link;
        $twitter_link = $settings1->footer_twitter_link;
        $instagram_link = $settings1->footer_instagram_link;
        $google_play_link = $settings1->footer_google_play_link;
        $apple_store_link = $settings1->footer_apple_store_link;

        $response[] = array('app_name' => $app_name, 'app_logo' => $app_logo, 'app_version' => $app_version, 'app_company' => $app_company, 'app_email' => $app_email, 'app_website' => $app_website, 'app_contact' => $app_contact, 'fb_link' => $fb_link, 'twitter_link' => $twitter_link, 'instagram_link' => $instagram_link, 'google_play_link' => $google_play_link, 'apple_store_link' => $apple_store_link, 'publisher_id' => $publisher_id, 'interstital_ad' => $interstital_ad, 'interstitial_ad_type' => $interstitial_ad_type, 'interstital_ad_id' => $interstital_ad_id, 'interstital_ad_click' => $interstital_ad_click, 'banner_ad' => $banner_ad, 'banner_ad_type' => $banner_ad_type, 'banner_ad_id' => $banner_ad_id, 'success' => '1');

        return \Response::json(array(
            'response_data' => $response,
            'user_status' => $user_status,
            'status_code' => 200
        ));
    }

    public function player_settings()
    {
        $this->default_file();
        $get_data = $_POST;

        $settings = Player::findOrFail('1');

        $player_style = $settings->player_style;
        $autoplay = $settings->autoplay;
        $theater_mode = $settings->theater_mode;
        $pip_mode = $settings->pip_mode;
        $rewind_forward = $settings->rewind_forward;

        $player_watermark = $settings->player_watermark;
        $player_logo = \URL::to('upload/source/' . $settings->player_logo);
        $player_logo_position = $settings->player_logo_position;
        $player_url = $settings->player_url;


        $player_ad_on_off = $settings->player_ad_on_off;
        $ad_offset = $settings->ad_offset;
        $ad_skip = $settings->ad_skip;
        $ad_website_url = $settings->ad_web_url;
        $ad_video_type = $settings->ad_video_type;
        $ad_video_url = $settings->ad_video_url;


        $response[] = array('player_style' => $player_style, 'autoplay' => $autoplay, 'theater_mode' => $theater_mode, 'pip_mode' => $pip_mode, 'rewind_forward' => $rewind_forward, 'player_watermark' => $player_watermark, 'player_logo' => $player_logo, 'player_watermark_position' => $player_logo_position, 'player_watermark_url' => $player_url, 'player_ad_on_off' => $player_ad_on_off, 'ad_offset' => $ad_offset, 'ad_skip' => $ad_skip, 'ad_website_url' => $ad_website_url, 'ad_video_type' => $ad_video_type, 'ad_video_url' => $ad_video_url);

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function payment_settings()
    {
        $this->default_file();
        $get_data = $_POST;

        $settings = Settings::findOrFail('1');

        $currency_code = $settings->currency_code;
        $paypal_payment_on_off = $settings->paypal_payment_on_off ? "true" : "false";
        $paypal_mode = $settings->paypal_mode;
        $paypal_client_id = $settings->paypal_client_id ? $settings->paypal_client_id : '';
        $paypal_secret = $settings->paypal_secret ? $settings->paypal_secret : '';

        $stripe_payment_on_off = $settings->stripe_payment_on_off ? "true" : "false";
        $stripe_secret_key = $settings->stripe_secret_key ? $settings->stripe_secret_key : '';
        $stripe_publishable_key = $settings->stripe_publishable_key ? $settings->stripe_publishable_key : '';

        //$razorpay_payment_on_off=$settings->razorpay_payment_on_off?"true":"false";
        //$razorpay_key=$settings->razorpay_key;
        //$razorpay_secret=$settings->razorpay_secret;

        //$paystack_payment_on_off=$settings->paystack_payment_on_off?"true":"false";
        //$paystack_secret_key=$settings->paystack_secret_key;
        //$paystack_public_key=$settings->paystack_public_key;

        //$response[] = array('currency_code' => $currency_code,'paypal_payment_on_off' => $paypal_payment_on_off,'paypal_mode' => $paypal_mode,'paypal_client_id' => $paypal_client_id,'paypal_secret' => $paypal_secret,'stripe_payment_on_off' => $stripe_payment_on_off,'stripe_secret_key' => $stripe_secret_key,'stripe_publishable_key' => $stripe_publishable_key,'razorpay_payment_on_off' => $razorpay_payment_on_off,'razorpay_key' => $razorpay_key,'razorpay_secret' => $razorpay_secret,'paystack_payment_on_off' => $paystack_payment_on_off,'paystack_secret_key' => $paystack_secret_key,'paystack_public_key' => $paystack_public_key,'success'=>'1');
        $response[] = array('currency_code' => $currency_code, 'paypal_payment_on_off' => $paypal_payment_on_off, 'paypal_mode' => $paypal_mode, 'paypal_client_id' => $paypal_client_id, 'paypal_secret' => $paypal_secret, 'stripe_payment_on_off' => $stripe_payment_on_off, 'stripe_secret_key' => $stripe_secret_key, 'stripe_publishable_key' => $stripe_publishable_key, 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function postLogin()
    {
        $this->default_file();
        $get_data = $_POST;

        $email = isset($get_data['email']) ? $get_data['email'] : '';
        $password = isset($get_data['password']) ? $get_data['password'] : '';

        if ($email == '' and $password == '') {
            $response[] = array('msg' => "All field required", 'success' => '0');
            return \Response::json(array(
                'response_data' => $response,
                'status_code' => 200
            ));
        }

        $user_info = User::where('email', $email)->first();
        if (Hash::check($password, $user_info['password'])) {
            if ($user_info->status == 0) {
                $response[] = array('msg' => trans('words.account_banned'), 'success' => '0');
            } else {
                $user_id = $user_info->id;
                $user = User::findOrFail($user_id);
                if ($user->user_image != '') {
                    $user_image = \URL::asset('upload/' . $user->user_image);
                } else {
                    $user_image = \URL::asset('upload/profile.png');
                }
                $response[] = array('user_id' => $user_id, 'name' => $user->name, 'email' => $user->email, 'user_image' => $user_image, 'referralCode' => $user->referralCode, 'msg' => 'Login successfully...', 'success' => '1');
            }
        } else {
            $response[] = array('msg' => trans('words.email_password_invalid'), 'success' => '0');
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function postSocialLogin()
    {
        $this->default_file();
        $get_data = $_POST;

        $login_type = $get_data['login_type']; // FB or Google
        $social_id = $get_data['social_id'];
        $user_name = $get_data['name'];
        $user_email = $get_data['email'];

        if ($login_type == "google") {
            $finduser = User::where('google_id', $social_id)->orwhere('email', $user_email)->first();
        } else {
            $finduser = User::where('facebook_id', $social_id)->orwhere('email', $user_email)->first();
        }

        if ($finduser) {

            if ($finduser->user_image != '') {
                $user_image = \URL::asset('upload/' . $finduser->user_image);
            } else {
                $user_image = \URL::asset('upload/profile.png');
            }

            if ($finduser->status == 0) {

                $response[] = array('msg' => trans('words.account_banned'), 'success' => '0');
            } else {

                $response[] = array('user_id' => $finduser->id, 'name' => $finduser->name, 'email' => $finduser->email, 'user_image' => $user_image, 'referralCode' => $finduser->referralCode, 'msg' => 'Login successfully...', 'success' => '1');
            }
        } else {
            $referralCode = $this->random_strings(10);
            $newUser = User::create([
                'name' => $user_name,
                'email' => $user_email,
                'password' => bcrypt('123456dummy'),
                'referralCode' => $referralCode
            ]);

            $user_id = $newUser->id;
            $user = User::findOrFail($user_id);
            if ($login_type == "google") {
                $user->google_id = $social_id;
            } else {
                $user->facebook_id = $social_id;
            }
            $user->save();


            if ($user->user_image != '') {
                $user_image = \URL::asset('upload/' . $user->user_image);
            } else {
                $user_image = \URL::asset('upload/profile.png');
            }

            if ($finduser->status == 0) {

                $response[] = array('msg' => trans('words.account_banned'), 'success' => '0');
            } else {
                $response[] = array('user_id' => $finduser->id, 'name' => $finduser->name, 'email' => $finduser->email, 'user_image' => $user_image, 'referralCode' => $user->referralCode, 'msg' => 'Login successfully...', 'success' => '1');
            }
        }


        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }
    public function getViewerProfile(Request $request){
        $user = auth('sanctum')->user();
        $user_id = $user->id;
        $userProfiles = UserProfiles::query()->where('user_id', $user_id)->whereNull('deleted_at')->get();
        $profiles=[];
        foreach($userProfiles as $userProfile){
            $profile_image = ProfileImages::where('id',$userProfile->image)->first();
            $userProfile->image = URL::to('upload/source/'.$profile_image->url);
            array_push($profiles,$userProfile);
        }
        $response = ['profiles' => $profiles];
        return response([
            'status' => true,
            'data' => $response,
            'msg' => 'Profiles retrieved successfully'
        ]);
    }
    public function getViewerProfileImages(Request $request){
        $user = auth('sanctum')->user();
        $user_id = $user->id;
        $profile_images = ProfileImages::get();
        $images=[];
        foreach($profile_images as $profile_image){
            $profile_image->url = URL::to('upload/source/'.$profile_image->url);
            array_push($images,$profile_image);
        }
        $response = ['images' => $images];
        return response([
            'status' => true,
            'data' => $response,
            'msg' => 'Images retrieved successfully'
        ]);
    }
    public function postViewerProfile(Request $request){
        $title = $request->input('title', '');
        $image = $request->input('image', '');
        $child_profile = $request->input('child_profile', '');
        //child_profile = 1 for creating a child profile
        if ($image == '' and $title == '' and $child_profile == '') {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'All fields required'
            ]);
        }
        $user = auth('sanctum')->user();
        $user_id = $user->id;
        $profile_count = UserProfiles::where('user_id', $user_id)->whereNull('deleted_at')->count();

        $profile_info = UserProfiles::where('user_id', $user_id)->where('title',$title)->whereNull('deleted_at')->first();
        if($profile_info){
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'A profile has been already created with same name for this user.'
            ]);
        }
        if($profile_count>=5){
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Profile limits reached'
            ]);
        }
        $userProfile = new UserProfiles;
        $userProfile->title = $title;
        $userProfile->image = $image;
        $userProfile->child_profile = $child_profile;
        $userProfile->user_id = $user_id;
        $userProfile->save();

        $userProfiles = UserProfiles::query()->where('user_id', $user_id)->get();
        $profiles=[];
        foreach($userProfiles as $userProfile){
            $profile_image = ProfileImages::where('id',$userProfile->image)->first();
            $userProfile->image = URL::to('upload/source/'.$profile_image->url);
            array_push($profiles,$userProfile);
        }
        $response = ['profiles' => $profiles];
        return response([
            'status' => true,
            'data' => $response,
            'msg' => 'Profile Created Successfully'
        ]);
    }
    public function deleteViewerProfile(Request $request){
        $id = $request->input('id', '');
        $user = auth('sanctum')->user();
        $user_id = $user->id;

        if($id==''){
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Missing required field id'
            ]);
        }
        $profile_info = UserProfiles::where('user_id', $user_id)->where('id',$id)->whereNull('deleted_at')->first();
        if($profile_info){
            UserProfiles::where('id',$id)->delete();

            $userProfiles = UserProfiles::query()->where('user_id', $user_id)->get();
            $profiles=[];
            foreach($userProfiles as $userProfile){
                $profile_image = ProfileImages::where('id',$userProfile->image)->first();
                $userProfile->image = URL::to('upload/source/'.$profile_image->url);
                array_push($profiles,$userProfile);
            }
            $response = ['profiles' => $profiles];
            return response([
                'status' => true,
                'data' => $response,
                'msg' => 'Profile Deleted Successfully'
            ]);
        }
        else{
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Profile does not exists'
            ]);
        }
    }
    public function updateViewerProfile(Request $request){
        $title = $request->input('title', '');
        $image = $request->input('image', '');
        $id = $request->input('id', '');
        $child_profile = $request->input('child_profile', '');
        //child_profile = 1 for creating a child profile
        if ($image == '' and $title == '' and $child_profile == '') {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'All fields required'
            ]);
        }
        $user = auth('sanctum')->user();
        $user_id = $user->id;

        $profile_info = UserProfiles::where('user_id', $user_id)->where('id','!=',$id)->where('title',$title)->whereNull('deleted_at')->first();
        if($profile_info){
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'A profile has been already created with same name for this user.'
            ]);
        }
        $userProfile = UserProfiles::findOrFail($id);
        $userProfile->title = $title;
        $userProfile->image = $image;
        $userProfile->child_profile = $child_profile;
        $userProfile->user_id = $user_id;
        $userProfile->save();

        $userProfiles = UserProfiles::query()->where('user_id', $user_id)->get();
        $profiles=[];
        foreach($userProfiles as $userProfile){
            $profile_image = ProfileImages::where('id',$userProfile->image)->first();
            $userProfile->image = URL::to('upload/source/'.$profile_image->url);
            array_push($profiles,$userProfile);
        }
        $response = ['profiles' => $profiles];
        return response([
            'status' => true,
            'data' => $response,
            'msg' => 'Profile Updated Successfully'
        ]);
    }

    public function postSignup(Request $request)
    {
        //$this->default_file();
        //  $get_data = $_POST;

        $name = $request->input('name', '');
        $email = $request->input('email', '');
        $password = $request->input('password', '');
        $phone = $request->input('phone', '');
        $referral = $request->input('referral', '');

        if ($name == '' and $email == '' and $password == '') {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'All fields required'
            ]);
        }

        $user_info = User::where('email', $email)->whereNull('deleted_at')->first();

        if ($user_info) {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'eMail Address already used!'
            ]);
        }
        if (!empty($referral)) {
            $userInfo = User::where('referralCode', $referral)->first();
            if (!empty($userInfo)) {
                $user = new User;
                $referralCode = $this->random_strings(10);

                $user->usertype = 'User';
                $user->name = $name;
                $user->email = $email;
                $user->password = bcrypt($password);
                $user->phone = $phone;
                $user->referralCode = $referralCode;
                $user->save();

                $referredby = $userInfo->id;
                DB::table('referrals')->insert(['referralCode' => $referral, 'referredby' => $referredby, 'userId' => $user->id]);
            } else {
                return response([
                    'status' => false,
                    'data' => null,
                    'msg' => 'Invalid referral code'
                ]);
            }
        } else {
            $user = new User;
            $referralCode = $this->random_strings(10);

            $user->usertype = 'User';
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->phone = $phone;
            $user->referralCode = $referralCode;
            $user->save();
        }

        //Welcome Email
        if (getenv("MAIL_USERNAME")) {
            $user_name = $name;
            $user_email = $email;
            // return getcong('site_email');
            $data_email = array(
                'name' => $user_name,
                'email' => $user_email,
                'site_logo' => "https://admin.rediscovertv.com/site_assets/images/template/logo.png",
                'site_name' => "ReDiscover Television",
                'site_email' => getcong('site_email')
            );

            if (env('MAIL_HOST')) {
                \Mail::send('emails.welcome', $data_email, function ($message) use ($user_name, $user_email) {
                    $message->to($user_email, $user_name)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject('Welcome to ' . getcong('site_name'));
                });
            }
        }

        $api_key = env('MAILCHIMP_APIKEY');
        $list_id = env('MAILCHIMP_LIST_ID');

        $firstname = $name;
        $lastname = '';
        if ($email and $api_key) {
            //Create mailchimp API url
            $memberId = md5(strtolower($email));
            $dataCenter = substr($api_key, strpos($api_key, '-') + 1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId;
            //Member info
            $data = array(
                'email_address' => $email,
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $firstname,
                    'LNAME' => $lastname,
                    'EMAIL' => $email
                ]
            );
            $jsonString = json_encode($data);
            // send a HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //Collecting the status
            switch ($httpCode) {
                case 200:
                    $msg = 'Success, newsletter subcribed using mailchimp API';
                    break;
                case 214:
                    $msg = 'Already Subscribed';
                    break;
                default:
                    $msg = 'Oops, please try again.[msg_code=' . $httpCode . ']';
                    break;
            }
        }
        // Introducing default user profile on signup
        $user_info = User::where('email', $email)->whereNull('deleted_at')->first();
        $profile_image = ProfileImages::where('is_default',1)->first();
        if($user_info){
            $user_id = $user_info->id;
            $userProfile = new UserProfiles();
            $userProfile->title = $name;
            $userProfile->user_id = $user_id;
            $userProfile->image = $profile_image->id;
            $userProfile->save();
        }

        $response['token'] = $user->createToken($request->device ?? 'web')->plainTextToken;

        return response([
            'status' => true,
            'data' => $response,
            'msg' => trans('words.account_created_successfully')
        ]);
    }

    public function forgot_password(Request $request)
    {

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Email not recognised. Kindly reach us on support@rediscovertelevision.com with your query.'
            ]);
        } else {

            $temporaryPassword = Str::random(8);
            $user->password = Hash::make($temporaryPassword);
            $user->save();

            $data = [
                'email' => $email,
                'password' => $temporaryPassword,
                'name' => $user->name,
                "site_name" => "ReDiscover Television",
                'from' => config('mail.from.address'), // Use the 'MAIL_FROM_ADDRESS' from your .env
                'from_name' => config('mail.from.name'), // Use the 'MAIL_FROM_NAME' from your .env
            ];

            Mail::send('emails.forgotpassword', $data, function ($message) use ($data) {
                $message
                    ->to($data['email'])
                    ->from($data['from'], $data['from_name'])
                    ->subject('Reset Password');
            });
            return response([
                'status' => true,
                'data' => "",
                'msg' => 'We have mailed you a new password'
            ]);
        }
    }

    public function change_password()
    {
        $this->default_file();
        $get_data = $_POST;
        $user_id = isset($get_data['user_id']) ? $get_data['user_id'] : '';
        $password = isset($get_data['password']) ? $get_data['password'] : '';
        if ($user_id == '' or $password == '') {
            $response[] = array('msg' => "Password fields required", 'success' => '0');
            return \Response::json(array(
                'response_data' => $response,
                'status_code' => 200
            ));
        }
        $user = User::findOrFail($user_id);
        $user->password = bcrypt($password);
        $user->save();
        $response[] = array('msg' => trans('words.successfully_updated'), 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function logout()
    {
        $this->default_file();
        $get_data = $_POST;
        $user = auth('sanctum')->user();
        $user->tokens()->delete();
        $response[] = array('msg' => 'you have logout successfully', 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function profile()
    {
        $this->default_file();
        $get_data = $_POST;

        $user = auth('sanctum')->user();

        if ($user->user_image != '') {
            $user_image = \URL::asset('upload/' . $user->user_image);
        } else {
            $user_image = \URL::asset('upload/profile.jpg');
        }

        if ($user->plan_id == 0) {
            $current_plan = '';
        } else {
            $current_plan = SubscriptionPlan::getSubscriptionPlanInfo($user->plan_id, 'plan_name');
        }

        if ($user->exp_date) {
            $expires_on = date('F,  d, Y', $user->exp_date);
        } else {
            $expires_on = '';
        }

        if ($user->start_date) {
            $last_invoice_date = date('F,  d, Y', $user->start_date);
        } else {
            $last_invoice_date = '';
        }

        $last_invoice_plan = $current_plan;
        if ($user->plan_amount) {
            $last_invoice_amount = number_format($user->plan_amount, 2, '.', '');
        } else {
            $last_invoice_amount = '';
        }

        $phone = $user->phone ? $user->phone : '';
        $user_address = $user->user_address ? $user->user_address : '';
        $user_registered = date('F,  d, Y', strtotime($user->created_at));

        $subprofiles = User::where("parent_id", $user->id)->where("usertype", "=", "Sub_Profile")->orderBy('id', 'DESC')->get();
        $userSubProfiles = array();
        if (!empty($subprofiles)) {
            foreach ($subprofiles as $ks => $sprofile) {
                $userSubProfiles[$ks]['id'] = $sprofile->id;
                $userSubProfiles[$ks]['name'] = $sprofile->name;
                $userSubProfiles[$ks]['email'] = $sprofile->email;
                $userSubProfiles[$ks]['phone'] = $sprofile->phone;

                if ($sprofile->user_image != '') {
                    $userSubProfiles[$ks]['image'] = \URL::asset('upload/' . $sprofile->user_image);
                } else {
                    $userSubProfiles[$ks]['image'] = \URL::asset('upload/profile.jpg');
                }
            }
        }

        $current_time = time();
        if (!empty($user->plan_id) && (!empty($user->exp_date)) && ($user->exp_date > $current_time)) {
            $subscription_status = 'Active';
            $response[] = array('user_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'phone' => $phone, 'image' => $user_image, 'registered_on' => $user_registered, 'current_plan' => $current_plan, 'expires_on' => $expires_on, 'subscription_status' => $subscription_status, 'amount' => $last_invoice_amount, 'subProfiles' => $userSubProfiles, 'msg' => 'Profile', 'success' => '1');
        } else {
            $subscription_status = 'Inactive';
            $response[] = array('user_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'phone' => $phone, 'image' => $user_image, 'registered_on' => $user_registered, 'current_plan' => $current_plan, 'expires_on' => $expires_on, 'subscription_status' => $subscription_status, 'amount' => $last_invoice_amount, 'subProfiles' => $userSubProfiles, 'msg' => 'Profile', 'success' => '1');
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function profile_update(Request $request)
    {
        $get_data = $request->all();
        $user = auth('sanctum')->user();


        $icon = $request->file('user_image');
        if ($icon) {
            \File::delete(public_path('/upload/') . $user->user_image);
            $tmpFilePath = public_path('/upload/');
            $hardPath = Str::slug($get_data['name'], '-') . '-' . md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath . $hardPath . '-b.jpg');
            $user->user_image = $hardPath . '-b.jpg';
        }

        $user->name = $get_data['name'];
        $user->email = $get_data['email'];
        $user->phone = $get_data['phone'];

        $user->save();
        // $response[] = array('msg' => trans('words.successfully_updated'), 'success' => '1');

        // return \Response::json(array(
        //     'response_data' => $response,
        //     'status_code' => 200
        // ));
        return response([
            'status' => true,
            'data' => ['token' => $user->createToken($request->device ?? 'web')->plainTextToken, 'user' => $user],
            'msg' => trans('words.successfully_updated')
        ]);
    }

    public function subprofile_add()
    {
        $this->default_file();
        $get_data = $_POST;

        $parent_user = auth('sanctum')->user();
        $user_id = $parent_user->id;
        $name = isset($get_data['name']) ? $get_data['name'] : '';
        $email = isset($get_data['email']) ? $get_data['email'] : '';
        $password = isset($get_data['password']) ? $get_data['password'] : '';
        $phone = isset($get_data['phone']) ? $get_data['phone'] : '';

        if ($name == '' and $email == '' and $password == '') {
            $response[] = array('msg' => "All fields required", 'success' => '0');
            return \Response::json(array(
                'response_data' => $response,
                'status_code' => 200
            ));
        }

        $user_info = User::where('email', $email)->first();
        if ($user_info) {
            $response[] = array('msg' => "Adresse e-Mail already used!", 'success' => '0');
            return \Response::json(array(
                'response_data' => $response,
                'status_code' => 200
            ));
        }

        $user = new User;
        $user->usertype = 'Sub_Profile';
        $user->parent_id = $user_id;
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->phone = $phone;

        $user->save();

        $response[] = array('msg' => trans('words.account_created_successfully'), 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function subprofile()
    {
        $this->default_file();
        $get_data = $_POST;

        $user_id = $get_data['user_id'];
        $user = User::findOrFail($user_id);

        if ($user->user_image != '') {
            $user_image = \URL::asset('upload/' . $user->user_image);
        } else {
            $user_image = \URL::asset('upload/profile.jpg');
        }

        $parentUser = User::where('id', $user->parent_id)->first();

        if ($parentUser->plan_id == 0) {
            $current_plan = '';
        } else {
            $current_plan = SubscriptionPlan::getSubscriptionPlanInfo($parentUser->plan_id, 'plan_name');
        }

        if ($parentUser->exp_date) {
            $expires_on = date('F,  d, Y', $parentUser->exp_date);
        } else {
            $expires_on = '';
        }

        if ($parentUser->start_date) {
            $last_invoice_date = date('F,  d, Y', $parentUser->start_date);
        } else {
            $last_invoice_date = '';
        }

        $last_invoice_plan = $current_plan;
        if ($parentUser->plan_amount) {
            $last_invoice_amount = number_format($parentUser->plan_amount, 2, '.', '');
        } else {
            $last_invoice_amount = '';
        }

        $user_registered = date('F,  d, Y', strtotime($parentUser->created_at));
        $current_time = time();
        if (!empty($parentUser->plan_id) && (!empty($parentUser->exp_date)) && ($parentUser->exp_date > $current_time)) {
            $subscription_status = 'Active';
            $response[] = array('user_id' => $user_id, 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'image' => $user_image, 'registered_on' => $user_registered, 'current_plan' => $current_plan, 'expires_on' => $expires_on, 'subscription_status' => $subscription_status, 'amount' => $last_invoice_amount, 'msg' => 'Profile', 'success' => '1');
        } else {
            $subscription_status = 'Inactive';
            $response[] = array('user_id' => $user_id, 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'image' => $user_image, 'registered_on' => $user_registered, 'current_plan' => $current_plan, 'expires_on' => $expires_on, 'subscription_status' => $subscription_status, 'amount' => $last_invoice_amount, 'msg' => 'Profile', 'success' => '1');
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function subprofile_update(Request $request)
    {
        $get_data = $request->all();
        $user_id = $get_data['user_id'];
        $user = User::findOrFail($user_id);

        $icon = $request->file('user_image');
        if ($icon) {
            \File::delete(public_path('/upload/') . $user->user_image);
            $tmpFilePath = public_path('/upload/');
            $hardPath = Str::slug($get_data['name'], '-') . '-' . md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath . $hardPath . '-b.jpg');
            $user->user_image = $hardPath . '-b.jpg';
        }

        $user->name = $get_data['name'];
        $user->email = $get_data['email'];
        $user->phone = $get_data['phone'];

        $user->save();
        $response[] = array('msg' => trans('words.successfully_updated'), 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function check_user_plan()
    {
        $this->default_file();
        $get_data = $_POST;
        $user_info = auth('sanctum')->user();

        $user_plan_id = $user_info->plan_id;
        $user_plan_exp_date = $user_info->exp_date;

        if ($user_plan_id == 0) {
            $response = array('msg' => 'Please select a subscription plan', 'success' => '0', 'isPremium' => false);
        } else if (strtotime(date('m/d/Y')) > $user_plan_exp_date) {
            $current_plan = SubscriptionPlan::find($user_plan_id);
            $expired_on = $user_plan_exp_date;
            $response = array('current_plan' => $current_plan, 'expired_on' => $expired_on, 'msg' => 'Renew subscription plan', 'success' => '0', 'isPremium' => false);
        } else {
            $current_plan = SubscriptionPlan::find($user_plan_id);
            $expired_on = $user_plan_exp_date;
            $response = array('current_plan' => $current_plan, 'expired_on' => $expired_on, 'msg' => 'My Subscription', 'success' => '1', 'isPremium' => true);
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function subscription_plan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json([
                'status' => false,
                'error' => $errors
            ], 403);
        }
        //return $request;
        $platform = strtolower(trim($request->platform));
        $plan_list = SubscriptionPlan::where('status', '1')->where("platform", $platform)->orderby('id')->get();
        if ($plan_list->isEmpty()) {
            $response = [];
            $msg = "no plans added";
            $status = false;
        } else {
            $settings = Settings::findOrFail('1');
            $currency_code = $settings->currency_code;
            $response = [];
            foreach ($plan_list as $plan_data) {
                $plan_id = $plan_data->id;
                $plan_name = $plan_data->plan_name;
                $productId = $plan_data->productId;
                $platform = $plan_data->platform;
                $plan_duration = SubscriptionPlan::getPlanDuration($plan_data->id);
                $plan_price = $plan_data->plan_price;

                $response[] = array("plan_id" => $plan_id, "plan_name" => $plan_name, "plan_duration" => $plan_duration, "plan_price" => $plan_price, "currency_code" => $currency_code, "productId" => $productId, "platform" => $platform);
                $msg = "success";
                $status = true;
            }
        }


        return \Response::json(array(
            'status' => $status,
            'data' => $response,
            'msg' => $msg

        ));
    }

    public function stripe_token_get()
    {
        $this->default_file();
        $get_data = $_POST;

        $amount = $get_data['amount'];
        \Stripe\Stripe::setApiKey(getcong('stripe_secret_key'));
        $currency_code = getcong('currency_code') ? getcong('currency_code') : 'USD';
        //$amount=10;
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount * 100,
            'currency' => $currency_code,
        ]);

        if (!isset($intent->client_secret)) {
            $response[] = array("msg" => "The Stripe Token was not generated correctly", 'success' => '0');
        } else {
            $client_secret = $intent->client_secret;

            $response[] = array("stripe_payment_token" => $client_secret, "msg" => "Stripe Token", 'success' => '1');
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function transaction_add()
    {
        $this->default_file();
        $get_data = $_POST;

        $user = auth('sanctum')->user();

        $plan_id = $get_data['plan_id'];
        $user_id = $user->id;
        $payment_id = $get_data['payment_id'];
        $payment_gateway = $get_data['payment_gateway'];

        $plan_info = SubscriptionPlan::where('id', $plan_id)->where('status', '1')->first();
        $plan_name = $plan_info->plan_name;
        $plan_days = $plan_info->plan_days;
        $plan_amount = $plan_info->plan_price;

        $user_email = $user->email;
        $user->plan_id = $plan_id;
        $user->start_date = strtotime(date('m/d/Y'));
        $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));

        if ($payment_gateway == "Stripe") {
            $user->stripe_payment_id = $payment_id;
        } else if ($payment_gateway == "Razorpay") {
            $user->razorpay_payment_id = $payment_id;
        } else if ($payment_gateway == "Paystack") {
            $user->paystack_payment_id = $payment_id;
        } else {
            $user->paypal_payment_id = $payment_id;
        }

        $user->plan_amount = $plan_amount;
        $user->save();

        //Transactions info update
        $payment_trans = new Transactions;
        $payment_trans->user_id = $user_id;
        $payment_trans->email = $user_email;
        $payment_trans->plan_id = $plan_id;
        $payment_trans->gateway = $payment_gateway;
        $payment_trans->payment_amount = $plan_amount;
        $payment_trans->payment_id = $payment_id;
        $payment_trans->date = strtotime(date('m/d/Y H:i:s'));
        $payment_trans->save();

        $response[] = array('msg' => trans('words.payment_success'), 'success' => '1');
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function roku_transaction_add()
    {
        $this->default_file();
        $get_data = $_POST;

        $plan_id = $get_data['plan_id'];
        $user_id = $get_data['user_id'];
        $payment_id = $get_data['payment_id'];
        $payment_gateway = $get_data['payment_gateway'];

        $plan_info = SubscriptionPlan::where('id', $plan_id)->where('status', '1')->first();
        $plan_name = $plan_info->plan_name;
        $plan_days = $plan_info->plan_days;
        $plan_amount = $plan_info->plan_price;

        //User info update
        $user = User::findOrFail($user_id);
        $user_email = $user->email;
        $user->plan_id = $plan_id;
        $user->start_date = strtotime(date('m/d/Y'));
        $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));

        if ($payment_gateway == "Roku") {
            $partnerAPIKey = '';
            $transactionid = '';
            $url = 'https://apipub.roku.com/listen/transaction-service.svc/validate-transaction/' . $partnerAPIKey . '/' . $transactionid;
            $curl = curl_init();
            curl_setopt_array($curl, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = json_decode($response, true);
            if (!empty($result['isEntitled'])) {
                $user->roku_payment_id = $payment_id;
                $user->plan_amount = $plan_amount;
                $user->save();

                //Transactions info update
                $payment_trans = new Transactions;
                $payment_trans->user_id = $user_id;
                $payment_trans->email = $user_email;
                $payment_trans->plan_id = $plan_id;
                $payment_trans->gateway = $payment_gateway;
                $payment_trans->payment_amount = $plan_amount;
                $payment_trans->payment_id = $payment_id;
                $payment_trans->date = strtotime(date('m/d/Y H:i:s'));
                $payment_trans->save();

                $response[] = array('msg' => trans('words.payment_success'), 'success' => '1');
            } else {
                $response[] = array('msg' => trans('words.payment_failed'), 'success' => '0');
            }
        }
        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function roku_transaction_test()
    {
        $this->default_file();
        $get_data = $_POST;

        $partnerAPIKey = $get_data['partnerAPIKey'];
        $transactionid = $get_data['transactionid'];

        $url = 'https://apipub.roku.com/listen/transaction-service.svc/validate-transaction/' . $partnerAPIKey . '/' . $transactionid;
        $curl = curl_init();
        curl_setopt_array($curl, array(CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true));
        $roku_response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $rokuresult = json_decode($roku_response, true);
        return \Response::json(array(
            'response_data' => $rokuresult,
            'status_code' => 200
        ));
    }

    public function home()
    {
        $this->default_file();
        $get_data = $_POST;

        $slider = Slider::where('status', 1)->orderby('id', 'DESC')->get();
        foreach ($slider as $slider_data) {
            $response['slider'][] = array("slider_title" => stripslashes($slider_data->slider_title), "slider_type" => $slider_data->slider_type, "slider_post_id" => $slider_data->slider_post_id, "slider_image" => \URL::to('upload/source/' . $slider_data->slider_image));
        }

        //Recently Watched
        if (isset($get_data['user_id'])) {
            $current_user_id = $get_data['user_id'];
            $recently_watched = RecentlyWatched::where('user_id', $current_user_id)->orderby('id', 'DESC')->get();
            if (count($recently_watched) > 0) {
                foreach ($recently_watched as $watched_videos) {
                    if ($watched_videos->video_type == "Movies") {
                        $thumb_image = URL::to('upload/source/' . recently_watched_info($watched_videos->video_type, $watched_videos->video_id)->video_image);
                        $video_thumb_image = $thumb_image ? $thumb_image : "";
                        $video_id = $watched_videos->video_id;
                        $video_type = $watched_videos->video_type;
                    } else if ($watched_videos->video_type == "Sports") {
                        $thumb_image = URL::to('upload/source/' . recently_watched_info($watched_videos->video_type, $watched_videos->video_id)->video_image);
                        $video_thumb_image = $thumb_image ? $thumb_image : "";
                        $video_id = $watched_videos->video_id;
                        $video_type = $watched_videos->video_type;
                    } else if ($watched_videos->video_type == "Episodes") {
                        $thumb_image = URL::to('upload/source/' . recently_watched_info($watched_videos->video_type, $watched_videos->video_id)->video_image);
                        $video_thumb_image = $thumb_image ? $thumb_image : "";
                        $video_id = recently_watched_info($watched_videos->video_type, $watched_videos->video_id)->episode_series_id;
                        $video_type = "Shows";
                    } else if ($watched_videos->video_type == "LiveTV") {
                        $thumb_image = URL::to('upload/source/' . recently_watched_info($watched_videos->video_type, $watched_videos->video_id)->channel_thumb);
                        $video_thumb_image = $thumb_image ? $thumb_image : "";
                        $video_id = $watched_videos->video_id;
                        $video_type = $watched_videos->video_type;
                    } else {
                        $video_thumb_image = "";
                        $video_id = $watched_videos->video_id;
                        $video_type = $watched_videos->video_type;
                    }
                    $response['recently_watched'][] = array("video_id" => $video_id, "video_type" => $video_type, "video_thumb_image" => $video_thumb_image);
                }
            } else {
                $response['recently_watched'] = array();
            }
        } else {
            $response['recently_watched'] = array();
        }

        $home_sections = HomeSection::findOrFail('1');
        foreach (explode(",", $home_sections->section1_latest_movie) as $latest_movie) {
            if (Movies::getMoviesInfo($latest_movie, 'status') == 1) {
                $movie_id = Movies::getMoviesInfo($latest_movie, 'id');
                $movie_title = Movies::getMoviesInfo($latest_movie, 'video_title');
                $movie_poster = URL::to('upload/source/' . Movies::getMoviesInfo($latest_movie, 'video_image_thumb'));
                $movie_duration = Movies::getMoviesInfo($latest_movie, 'duration');
                $movie_access = Movies::getMoviesInfo($latest_movie, 'video_access');

                $response['latest_movies'][] = array("movie_id" => $movie_id, "movie_title" => stripslashes($movie_title), "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        }

        foreach (explode(",", $home_sections->section2_latest_series) as $latest_series) {
            if (Series::getSeriesInfo($latest_series, 'status') == 1) {
                $show_id = Series::getSeriesInfo($latest_series, 'id');
                $show_title = Series::getSeriesInfo($latest_series, 'series_name');
                $show_poster = URL::to('upload/source/' . Series::getSeriesInfo($latest_series, 'series_poster'));
                $response['latest_shows'][] = array("show_id" => $show_id, "show_title" => stripslashes($show_title), "show_poster" => $show_poster);
            }
        }

        //Popular
        foreach (explode(",", $home_sections->section3_popular_movie) as $popular_movie) {
            if (Movies::getMoviesInfo($popular_movie, 'status') == 1) {
                $movie_id = Movies::getMoviesInfo($popular_movie, 'id');
                $movie_title = Movies::getMoviesInfo($popular_movie, 'video_title');
                $movie_poster = URL::to('upload/source/' . Movies::getMoviesInfo($popular_movie, 'video_image_thumb'));
                $movie_duration = Movies::getMoviesInfo($popular_movie, 'duration');
                $movie_access = Movies::getMoviesInfo($popular_movie, 'video_access');

                $response['popular_movies'][] = array("movie_id" => $movie_id, "movie_title" => stripslashes($movie_title), "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        }

        foreach (explode(",", $home_sections->section3_popular_series) as $popular_series) {
            if (Series::getSeriesInfo($popular_series, 'status') == 1) {
                $show_id = Series::getSeriesInfo($popular_series, 'id');
                $show_title = Series::getSeriesInfo($popular_series, 'series_name');
                $show_poster = URL::to('upload/source/' . Series::getSeriesInfo($popular_series, 'series_poster'));
                $response['popular_shows'][] = array("show_id" => $show_id, "show_title" => stripslashes($show_title), "show_poster" => $show_poster);
            }
        }

        //Section 3
        $response['home_sections3_title'] = $home_sections->section3_title;
        $response['home_sections3_type'] = $home_sections->section3_type;
        $response['home_sections3_lang_id'] = $home_sections->section3_lang ? $home_sections->section3_lang : '';
        if ($home_sections->section3_type == "Series") {
            $section3_lang_id = $home_sections->section3_lang;
            $home_sections_list3 = Series::where('status', 1)->where('series_lang_id', $section3_lang_id)->orderBy('id', 'DESC')->take(10)->get();

            if (count($home_sections_list3) > 0 and $section3_lang_id != '') {
                foreach ($home_sections_list3 as $list3_data) {
                    $s_m_id = $list3_data->id;
                    $s_m_title = stripslashes($list3_data->series_name);
                    $s_m_poster = ($list3_data->series_poster);
                    $response['home_sections3'][] = array("show_id" => $s_m_id, "show_title" => $s_m_title, "show_poster" => $s_m_poster);
                }
            } else {
                $response['home_sections3'] = array();
            }
        } else {
            $section3_lang_id = $home_sections->section3_lang;
            $home_sections_list3 = Movies::where('status', 1)->where('movie_lang_id', $section3_lang_id)->orderBy('id', 'DESC')->take(10)->get();

            if (count($home_sections_list3) > 0 and $section3_lang_id != '') {
                foreach ($home_sections_list3 as $list3_data) {
                    $s_m_id = $list3_data->id;
                    $s_m_title = stripslashes($list3_data->video_title);
                    $s_m_poster = ($list3_data->video_image_thumb);
                    $s_m_duration = $list3_data->duration;
                    $s_m_access = $list3_data->video_access;
                    $response['home_sections3'][] = array("movie_id" => $s_m_id, "movie_title" => $s_m_title, "movie_poster" => $s_m_poster, "movie_duration" => $s_m_duration, "movie_access" => $s_m_access);
                }
            } else {
                $response['home_sections3'] = array();
            }
        }

        //Section 4
        $response['home_sections4_title'] = $home_sections->section4_title;
        $response['home_sections4_type'] = $home_sections->section4_type;
        $response['home_sections4_lang_id'] = $home_sections->section4_lang ? $home_sections->section4_lang : '';
        if ($home_sections->section4_type == "Series") {
            $section4_lang_id = $home_sections->section4_lang;
            $home_sections_list4 = Series::where('status', 1)->where('series_lang_id', $section4_lang_id)->orderBy('id', 'DESC')->take(10)->get();
            if (count($home_sections_list4) > 0 and $section4_lang_id != '') {

                foreach ($home_sections_list4 as $list4_data) {
                    $s_m_id = $list4_data->id;
                    $s_m_title = stripslashes($list4_data->series_name);
                    $s_m_poster = ($list4_data->series_poster);
                    $response['home_sections4'][] = array("show_id" => $s_m_id, "show_title" => $s_m_title, "show_poster" => $s_m_poster);
                }
            } else {
                $response['home_sections4'] = array();
            }
        } else {
            $section4_lang_id = $home_sections->section4_lang;
            $home_sections_list4 = Movies::where('status', 1)->where('movie_lang_id', $section4_lang_id)->orderBy('id', 'DESC')->take(10)->get();

            if (count($home_sections_list4) > 0 and $section4_lang_id != '') {
                foreach ($home_sections_list4 as $list4_data) {
                    $s_m_id = $list4_data->id;
                    $s_m_title = stripslashes($list4_data->video_title);
                    $s_m_poster = ($list4_data->video_image_thumb);
                    $s_m_duration = $list4_data->duration;
                    $s_m_access = $list4_data->video_access;
                    $response['home_sections4'][] = array("movie_id" => $s_m_id, "movie_title" => $s_m_title, "movie_poster" => $s_m_poster, "movie_duration" => $s_m_duration, "movie_access" => $s_m_access);
                }
            } else {
                $response['home_sections4'] = array();
            }
        }

        //Section 5
        $response['home_sections5_title'] = $home_sections->section5_title;
        $response['home_sections5_type'] = $home_sections->section5_type;
        $response['home_sections5_lang_id'] = $home_sections->section5_lang ? $home_sections->section5_lang : '';

        if ($home_sections->section5_type == "Series") {
            $section5_lang_id = $home_sections->section5_lang;
            $home_sections_list5 = Series::where('status', 1)->where('series_lang_id', $section5_lang_id)->orderBy('id', 'DESC')->take(10)->get();

            if (count($home_sections_list5) > 0 and $section5_lang_id != '') {
                foreach ($home_sections_list5 as $list5_data) {
                    $s_m_id = $list5_data->id;
                    $s_m_title = stripslashes($list5_data->series_name);
                    $s_m_poster = ($list5_data->series_poster);
                    $response['home_sections5'][] = array("show_id" => $s_m_id, "show_title" => $s_m_title, "show_poster" => $s_m_poster);
                }
            } else {
                $response['home_sections5'] = array();
            }
        } else {
            $section5_lang_id = $home_sections->section5_lang;
            $home_sections_list5 = Movies::where('status', 1)->where('movie_lang_id', $section5_lang_id)->orderBy('id', 'DESC')->take(10)->get();

            if (count($home_sections_list5) > 0 and $section5_lang_id != '') {
                foreach ($home_sections_list5 as $list5_data) {
                    $s_m_id = $list5_data->id;
                    $s_m_title = stripslashes($list5_data->video_title);
                    $s_m_poster = ($list5_data->video_image_thumb);
                    $s_m_duration = $list5_data->duration;
                    $s_m_access = $list5_data->video_access;
                    $response['home_sections5'][] = array("movie_id" => $s_m_id, "movie_title" => $s_m_title, "movie_poster" => $s_m_poster, "movie_duration" => $s_m_duration, "movie_access" => $s_m_access);
                }
            } else {
                $response['home_sections5'] = array();
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function latest_movies()
    {
        $this->default_file();
        $get_data = $_POST;
        $home_sections = HomeSection::findOrFail('1');

        foreach (explode(",", $home_sections->section1_latest_movie) as $latest_movie) {
            if (Movies::getMoviesInfo($latest_movie, 'status') == 1) {
                $movie_id = Movies::getMoviesInfo($latest_movie, 'id');
                $movie_title = Movies::getMoviesInfo($latest_movie, 'video_title');
                $movie_poster = URL::to('upload/source/' . Movies::getMoviesInfo($latest_movie, 'video_image_thumb'));
                $movie_duration = Movies::getMoviesInfo($latest_movie, 'duration');
                $movie_access = Movies::getMoviesInfo($latest_movie, 'video_access');

                $response[] = array("movie_id" => $movie_id, "movie_title" => stripslashes($movie_title), "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function latest_shows()
    {
        $this->default_file();
        $get_data = $_POST;

        $home_sections = HomeSection::findOrFail('1');
        foreach (explode(",", $home_sections->section2_latest_series) as $latest_series) {
            if (Series::getSeriesInfo($latest_series, 'status') == 1) {
                $show_id = Series::getSeriesInfo($latest_series, 'id');
                $show_title = Series::getSeriesInfo($latest_series, 'series_name');
                $show_poster = URL::to('upload/source/' . Series::getSeriesInfo($latest_series, 'series_poster'));

                $response[] = array("show_id" => $show_id, "show_title" => stripslashes($show_title), "show_poster" => $show_poster);
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function popular_movies()
    {
        $this->default_file();
        $get_data = $_POST;

        $home_sections = HomeSection::findOrFail('1');
        foreach (explode(",", $home_sections->section3_popular_movie) as $popular_movie) {
            if (Movies::getMoviesInfo($popular_movie, 'status') == 1) {
                $movie_id = Movies::getMoviesInfo($popular_movie, 'id');
                $movie_title = Movies::getMoviesInfo($popular_movie, 'video_title');
                $movie_poster = URL::to('upload/source/' . Movies::getMoviesInfo($popular_movie, 'video_image_thumb'));
                $movie_duration = Movies::getMoviesInfo($popular_movie, 'duration');
                $movie_access = Movies::getMoviesInfo($popular_movie, 'video_access');

                $response[] = array("movie_id" => $movie_id, "movie_title" => stripslashes($movie_title), "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function popular_shows()
    {
        $this->default_file();
        $get_data = $_POST;

        $home_sections = HomeSection::findOrFail('1');
        foreach (explode(",", $home_sections->section3_popular_series) as $popular_series) {
            if (Series::getSeriesInfo($popular_series, 'status') == 1) {
                $show_id = Series::getSeriesInfo($popular_series, 'id');
                $show_title = Series::getSeriesInfo($popular_series, 'series_name');
                $show_poster = URL::to('upload/source/' . Series::getSeriesInfo($popular_series, 'series_poster'));

                $response[] = array("show_id" => $show_id, "show_title" => stripslashes($show_title), "show_poster" => $show_poster);
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function languages()
    {
        $this->default_file();
        $get_data = $_POST;

        $lang_list = Language::where('status', 1)->orderby('id')->get();

        foreach ($lang_list as $lang_data) {
            $language_id = $lang_data->id;
            $language_name = stripslashes($lang_data->language_name);
            $language_image = URL::to('upload/source/' . $lang_data->language_image);

            $response[] = array("language_id" => $language_id, "language_name" => $language_name, "language_image" => $language_image);
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function genres()
    {
        //$this->default_file();
        $get_data = $_POST;

        $genres_list = Genres::where('status', 1)->orderby('id')->get();
        foreach ($genres_list as $genres_data) {
            $genre_id = $genres_data->id;
            $genre_name = stripslashes($genres_data->genre_name);
            $genre_image = URL::to('upload/source/' . $genres_data->genres_image);

            $response[] = array("genre_id" => $genre_id, "genre_name" => $genre_name, "genre_image" => $genre_image);
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function shows_by_language()
    {
        $this->default_file();
        $get_data = $_POST;

        $series_lang_id = $get_data['lang_id'];
        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $series_list = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->orderBy('id', 'ASC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $series_list = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->orderBy('series_name', 'ASC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $series_list = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->inRandomOrder()->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else {
                $series_list = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->orderBy('id', 'DESC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $series_list = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = Series::where('status', 1)->where('series_lang_id', $series_lang_id)->count();
        if ($series_list->count()) {
            foreach ($series_list as $series_data) {
                $show_id = $series_data->id;
                $show_title = stripslashes($series_data->series_name);
                $show_poster = ($series_data->series_poster);

                $response[] = array("show_id" => $show_id, "show_title" => $show_title, "show_poster" => $show_poster);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function shows_by_genre()
    {
        $this->default_file();
        $get_data = $_POST;

        $series_genres_id = $get_data['genre_id'];

        $series = Series::query()->where('status', 1)
            ->whereRaw("find_in_set('$series_genres_id',series_genres)");
        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];
            switch ($keyword) {
                case 'old':
                    $series->orderBy('id', 'ASC');
                    break;
                case 'alpha':
                    $series->orderBy('series_name', 'ASC');
                    break;
                case 'rand':
                    $series->inRandomOrder();
                    break;
                default:
                    $series->orderBy('id', 'DESC');
                    break;
            }
        } else {
            $series->orderBy('id', 'DESC');
        }
        $series = $series->paginate(config('data.per_page'));


        return \Response::json(array(
            'status' => true,
            'data' => $series->items(),
            'currentPage' => $series->currentPage(),
            'totalItems' => $series->total(),
            'msg' => ''
        ));
    }

    public function shows()
    {
        $this->default_file();
        $get_data = $_POST;

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $series_list = Series::where('status', 1)->orderBy('id', 'ASC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $series_list = Series::where('status', 1)->orderBy('series_name', 'ASC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $series_list = Series::where('status', 1)->inRandomOrder()->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            } else {
                $series_list = Series::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
                $series_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $series_list = Series::where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = Series::where('status', '1')->count();
        if ($series_list->count()) {
            foreach ($series_list as $series_data) {
                $show_id = $series_data->id;
                $show_title = stripslashes($series_data->series_name);
                $show_poster = ($series_data->series_poster);

                //Genres List
                $genre_list = array();
                $series_genres_ids = $series_data->series_genres;
                if (!empty($series_genres_ids)) {
                    foreach (explode(',', $series_genres_ids) as $genres_ids) {
                        $genre_name = Genres::getGenresInfo($genres_ids, 'genre_name');
                        $genre_list[] = array('genre_id' => $genres_ids, 'genre_name' => $genre_name);
                    }
                }

                //Season List
                $seasonList = array();
                $season_list = Season::where('status', 1)->where('series_id', $show_id)->get();
                if ($season_list->count()) {
                    foreach ($season_list as $season_data) {
                        $season_id = $season_data->id;
                        $season_name = stripslashes($season_data->season_name);
                        $season_poster = ($season_data->season_poster);
                        $seasonList[] = array("season_id" => $season_id, "season_name" => $season_name, "season_poster" => $season_poster);
                    }
                }

                //Episodes List
                $episodeList = array();
                $episode_list = Episodes::where('status', 1)->where('episode_series_id', $show_id)->get();
                if ($episode_list->count()) {
                    $episodeData = array();
                    foreach ($episode_list as $episode_data) {
                        $episode_id = $episode_data->id;
                        $episode_title = stripslashes($episode_data->video_title);
                        $episode_image = ($episode_data->video_image);
                        $description = stripslashes($episode_data->video_description);
                        $duration = $episode_data->duration;
                        $release_date = isset($episode_data->release_date) ? date('M d Y', $episode_data->release_date) : '';
                        $series_name = Series::getSeriesInfo($episode_data->episode_series_id, 'series_name');
                        $season_name = Season::getSeasonInfo($episode_data->episode_season_id, 'season_name');
                        $imdb_rating = $episode_data->imdb_rating ? $episode_data->imdb_rating : "";
                        //Genres List
                        $series_genres_ids = Series::getSeriesInfo($episode_data->episode_series_id, 'series_genres');
                        foreach (explode(',', $series_genres_ids) as $key => $genres_ids) {
                            $genre_name = Genres::getGenresInfo($genres_ids, 'genre_name');
                            $genrelist[$key] = array('genre_id' => $genres_ids, 'genre_name' => $genre_name);
                        }
                        $episodeData[] = array("episode_id" => $episode_id, "episode_title" => $episode_title, "episode_image" => $episode_image, "description" => $description, "duration" => $duration, "release_date" => $release_date, "imdb_rating" => $imdb_rating, 'genre_list' => $genrelist, "series_name" => stripslashes($series_name), "season_name" => $season_name);
                    }
                    $episodeList[] = $episodeData;
                }

                $response[] = array("show_id" => $show_id, "show_title" => $show_title, "show_poster" => $show_poster, "show_genres" => $genre_list, "show_season" => $seasonList, "show_episodes" => $episodeList);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function show_details(Request $request)
    {
        // $this->default_file();
        // $get_data = $_POST;
        $series_id = $request->show_id;

        $series_info = Series::where('status', 1)->where('id', $series_id)->orWhere('mediaId', $series_id)
            ->with('seasons')
            ->first();
        $series_info->append('related');

        if (!$series_info) {
            return \Response::json(array(
                'status' => false,
                'data' => '',
                'msg' => 'Series not found'
            ));
        }

        return \Response::json(array(
            'status' => true,
            'data' => $series_info,
            'msg' => ''
        ));
    }

    public function seasons()
    {
        $this->default_file();
        $get_data = $_POST;

        $series_id = $get_data['show_id'];

        $season_list = Season::where('status', 1)->where('series_id', $series_id)->get();

        if ($season_list->count()) {
            foreach ($season_list as $season_data) {
                $season_id = $season_data->id;
                $season_name = stripslashes($season_data->season_name);
                $season_poster = ($season_data->season_poster);

                $response[] = array("season_id" => $season_id, "season_name" => $season_name, "season_poster" => $season_poster);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function episodes(Request $request)
    {
        // $this->default_file();
        // $get_data = $_POST;

        //        $user_id = $get_data['user_id'];
        //
        //        if ($user_id != '') {
        //            $user_plan_status = check_app_user_plan($user_id);
        //        } else {
        //            $user_plan_status = false;
        //        }
        //
        //
        $season_id = $request->season_id;

        $items = Episodes::query()->where('status', 1)->where('episode_season_id', $season_id)
            ->orderBy('episode_number', 'ASC')->get();
        return response([
            'status' => true,
            'data' => $items,
            'currentPage' => 1,//$items->currentPage(),
            'totalItems' => $items->count(),
            'msg' => ''
        ]);
        //        $episode_list = Episodes::query()->where('status', 1)->where('episode_season_id', $season_id)->get();
        /*
        if ($episode_list->count()) {
            foreach ($episode_list as $episode_data) {

                $episode_title = stripslashes($episode_data->video_title);
                $episode_image = URL::to('upload/source/' . $episode_data->video_image);
                $video_access = $episode_data->video_access;
                $description = stripslashes($episode_data->video_description);

                $duration = $episode_data->duration;
                $release_date = isset($episode_data->release_date) ? date('M d Y', $episode_data->release_date) : '';

                $video_type = $episode_data->video_type;

                $imdb_rating = $episode_data->imdb_rating ? $episode_data->imdb_rating : "";

                if ($video_type == "Local") {
                    $video_url = $episode_data->video_url ? URL::to('upload/source/' . $episode_data->video_url) : "";
                } else {
                    $video_url = $episode_data->video_url ? $episode_data->video_url : "";
                }

                $video_url_480 = $episode_data->video_url_480 ? $episode_data->video_url_480 : '';
                $video_url_720 = $episode_data->video_url_720 ? $episode_data->video_url_720 : '';
                $video_url_1080 = $episode_data->video_url_1080 ? $episode_data->video_url_1080 : '';

                $subtitle_language1 = $episode_data->subtitle_language1 ? $episode_data->subtitle_language1 : '';
                $subtitle_url1 = $episode_data->subtitle_url1 ? $episode_data->subtitle_url1 : '';

                $subtitle_language2 = $episode_data->subtitle_language2 ? $episode_data->subtitle_language2 : '';
                $subtitle_url2 = $episode_data->subtitle_url2 ? $episode_data->subtitle_url2 : '';

                $subtitle_language3 = $episode_data->subtitle_language3 ? $episode_data->subtitle_language3 : '';
                $subtitle_url3 = $episode_data->subtitle_url3 ? $episode_data->subtitle_url3 : '';

                $download_enable = $episode_data->download_enable ? 'true' : 'false';
                $download_url = $episode_data->download_url ? $episode_data->download_url : "";

                $series_name = Series::getSeriesInfo($episode_data->episode_series_id, 'series_name');
                $season_name = Season::getSeasonInfo($episode_data->episode_season_id, 'season_name');

                $series_lang_id = Series::getSeriesInfo($episode_data->episode_series_id, 'series_lang_id');

                //Genres List
                $series_genres_ids = Series::getSeriesInfo($episode_data->episode_series_id, 'series_genres');
                foreach (explode(',', $series_genres_ids) as $genres_ids) {
                    $genre_name = Genres::getGenresInfo($genres_ids, 'genre_name');
                    $genre_list[] = $genre_name;
                }

                $language_name = Language::getLanguageInfo($series_lang_id, 'language_name');

                $video_quality = $episode_data->video_quality ? 'true' : 'false';
                $subtitle_on_off = $episode_data->subtitle_on_off ? 'true' : 'false';

                $response[] = array("episode_id" => $episode_id, "episode_title" => $episode_title, "episode_image" => $episode_image, "video_access" => $video_access, "description" => $description, "duration" => $duration, "release_date" => $release_date, "imdb_rating" => $imdb_rating, 'video_type' => $video_type, 'video_url' => $video_url, 'video_url_480' => $video_url_480, 'video_url_720' => $video_url_720, 'video_url_1080' => $video_url_1080, 'lang_id' => $series_lang_id, 'language_name' => $language_name, 'genre_list' => $genre_list, "series_name" => stripslashes($series_name), "season_name" => $season_name, "download_enable" => $download_enable, "download_url" => $download_url, 'subtitle_language1' => $subtitle_language1, 'subtitle_url1' => $subtitle_url1, 'subtitle_language2' => $subtitle_language2, 'subtitle_url2' => $subtitle_url2, 'subtitle_language3' => $subtitle_language3, 'subtitle_url3' => $subtitle_url3, 'video_quality' => $video_quality, 'subtitle_on_off' => $subtitle_on_off);

                unset($genre_list);
            }
        } else {
            $response = array();
        }
        */

        //        $total_records = Episodes::where('status', 1)->where('episode_season_id', $season_id)->count();


    }

    public function episodes_details(Request $request)
    {
        // $this->default_file();
        // $get_data = $_POST;

        $user_id = $request->user_id;

        if ($user_id != '') {
            $user_plan_status = check_app_user_plan($user_id);
        } else {
            $user_plan_status = false;
        }

        $episode_id = $request->episode_id;
        $episode = Episodes::query()->where('status', 1)
            ->where('id', $episode_id)->orWhere('mediaId', $episode_id)
            ->with(['season', 'series', 'genre'])
            ->get();
        return response([
            'status' => false,
            'data' => $episode,
            'user_plan_status' => $user_plan_status,
            'msg' => ''
        ]);
    }

    public function episodes_recently_watched()
    {
        $this->default_file();
        $get_data = $_POST;

        //Recently Watched
        if (isset($get_data['user_id']) && $get_data['user_id'] != "") {
            $current_user_id = $get_data['user_id'];
            $video_id = $get_data['episode_id'];

            $recently_video_count = RecentlyWatched::where('video_type', 'Episodes')->where('user_id', $current_user_id)->where('video_id', $video_id)->count();

            if ($recently_video_count <= 0) {
                //Current user recently count
                $current_user_video_count = RecentlyWatched::where('user_id', $current_user_id)->count();

                if ($current_user_video_count == 10) {
                    DB::table("recently_watched")
                        ->where("user_id", "=", $current_user_id)
                        ->orderBy("id", "ASC")
                        ->take(1)
                        ->delete();

                    $video_recent_obj = new RecentlyWatched;
                    $video_recent_obj->video_type = 'Episodes';
                    $video_recent_obj->user_id = $current_user_id;
                    $video_recent_obj->video_id = $video_id;
                    $video_recent_obj->save();
                } else {
                    $video_recent_obj = new RecentlyWatched;
                    $video_recent_obj->video_type = 'Episodes';
                    $video_recent_obj->user_id = $current_user_id;
                    $video_recent_obj->video_id = $video_id;
                    $video_recent_obj->save();
                }
            }

            $response = array('success' => true);
        } else {
            $response = array('success' => true);
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function movies()
    {
        $this->default_file();
        $get_data = $_POST;

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $movies_list = Movies::where('status', 1)->orderBy('id', 'ASC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $movies_list = Movies::where('status', 1)->orderBy('video_title', 'ASC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $movies_list = Movies::where('status', 1)->inRandomOrder()->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else {
                $movies_list = Movies::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $movies_list = Movies::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
        }

        $trending = array();
        $total_records = Movies::where('status', '1')->count();
        if ($movies_list->count()) {
            foreach ($movies_list as $movie_data) {
                $movie_id = $movie_data->id;
                $movie_title = stripslashes($movie_data->video_title);
                $movie_poster = ($movie_data->video_image_thumb);
                $movie_duration = $movie_data->duration;
                $movie_access = $movie_data->video_access;
                $trending[] = array("movie_id" => $movie_id, "movie_title" => $movie_title, "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
            $response['trending'] = $trending;
        } else {
            $response['trending'] = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function movies_by_language()
    {

        $this->default_file();
        $get_data = $_POST;

        $movie_lang_id = $get_data['lang_id'];

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $movies_list = Movies::where('status', 1)->where('movie_lang_id', $movie_lang_id)->orderBy('id', 'ASC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $movies_list = Movies::where('status', 1)->where('movie_lang_id', $movie_lang_id)->orderBy('video_title', 'ASC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $movies_list = Movies::where('status', 1)->where('movie_lang_id', $movie_lang_id)->inRandomOrder()->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            } else {
                $movies_list = Movies::where('status', 1)->where('movie_lang_id', $movie_lang_id)->orderBy('id', 'DESC')->paginate(12);
                $movies_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $movies_list = Movies::where('status', 1)->where('movie_lang_id', $movie_lang_id)->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = Movies::where('status', '1')->where('movie_lang_id', $movie_lang_id)->count();

        if ($movies_list->count()) {
            foreach ($movies_list as $movie_data) {

                $movie_id = $movie_data->id;
                $movie_title = stripslashes($movie_data->video_title);
                $movie_poster =  ($movie_data->video_image_thumb);
                $movie_duration = $movie_data->duration;
                $movie_access = $movie_data->video_access;

                $response[] = array("movie_id" => $movie_id, "movie_title" => $movie_title, "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function movies_by_genre()
    {
        $this->default_file();
        $get_data = $_POST;
        $movie_genre_id = $get_data['genre_id'];
        $movies = Movies::query()->where('status', 1)
            ->whereRaw("find_in_set('$movie_genre_id',movie_genre_id)");
        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];
            switch ($keyword) {
                case 'old':
                    $movies->orderBy('id', 'ASC');
                    break;
                case 'alpha':
                    $movies->orderBy('video_title', 'ASC');
                    break;
                case 'rand':
                    $movies->inRandomOrder();
                    break;
                default:
                    $movies->orderBy('id', 'DESC');
                    break;
            }
        } else {
            $movies->orderBy('id', 'DESC');
        }
        $movies = $movies->paginate(config('data.per_page'));
        return response([
            'status' => true,
            'data' => $movies->items(),
            'currentPage' => $movies->currentPage(),
            'totalItems' => $movies->total(),
            'msg' => ''
        ]);
    }

    public function movies_details(Request $request)
    {
        //$this->default_file();
        $movie_id = $request->movie_id;
        // print_r($get_data);die;
        //        $user = auth('sanctum')->user();

        //        $user_id = $user->id;
        //
        //        if ($user_id != "") {
        //            $user_plan_status = check_app_user_plan($user_id);
        //        } else {
        //            $user_plan_status = false;
        //        }


        // $movie_id = $get_data['movie_id'];
        $movies_info = Movies::where('id', $movie_id)
            ->orWhere('mediaId', $movie_id)
            ->first();
        //append related if found
        if ($movies_info) {
            $movies_info->append('related');
        }
        //        return $movies_info;


        //Recently Watched
        //        if (isset($user_id) && $user_id != "") {
        //            $current_user_id = $user_id;
        //            $video_id = $movies_info->id;
        //
        //            $recently_video_count = RecentlyWatched::where('video_type', 'Movies')->where('user_id', $current_user_id)->where('video_id', $video_id)->count();
        //
        //            if ($recently_video_count <= 0) {
        //                //Current user recently count
        //                $current_user_video_count = RecentlyWatched::where('user_id', $current_user_id)->count();
        //
        //                if ($current_user_video_count == 10) {
        //                    DB::table("recently_watched")
        //                        ->where("user_id", "=", $current_user_id)
        //                        ->orderBy("id", "ASC")
        //                        ->take(1)
        //                        ->delete();
        //
        //                    $video_recent_obj = new RecentlyWatched;
        //                    $video_recent_obj->video_type = 'Movies';
        //                    $video_recent_obj->user_id = $current_user_id;
        //                    $video_recent_obj->video_id = $video_id;
        //                    $video_recent_obj->save();
        //                } else {
        //                    $video_recent_obj = new RecentlyWatched;
        //                    $video_recent_obj->video_type = 'Movies';
        //                    $video_recent_obj->user_id = $current_user_id;
        //                    $video_recent_obj->video_id = $video_id;
        //                    $video_recent_obj->save();
        //                }
        //            }
        //
        //        }
        return response([
            'status' => true,
            'data' => $movies_info,
            //            'user_plan_status' => $user_plan_status,
            'msg' => ''
        ]);
    }

    public function sports_category()
    {
        $this->default_file();
        $get_data = $_POST;

        $cat_list = SportsCategory::where('status', 1)->orderby('id')->get();

        foreach ($cat_list as $cat_data) {
            $category_id = $cat_data->id;
            $category_name = stripslashes($cat_data->category_name);

            $response[] = array("category_id" => $category_id, "category_name" => $category_name);
        }

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }

    public function sports()
    {
        $this->default_file();
        $get_data = $_POST;

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $sports_video_list = Sports::where('status', 1)->orderBy('id', 'ASC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $sports_video_list = Sports::where('status', 1)->orderBy('video_title', 'ASC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $sports_video_list = Sports::where('status', 1)->inRandomOrder()->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else {
                $sports_video_list = Sports::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $sports_video_list = Sports::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = Sports::where('status', '1')->count();

        if ($sports_video_list->count()) {
            foreach ($sports_video_list as $sports_data) {

                $sport_id = $sports_data->id;
                $sport_title = stripslashes($sports_data->video_title);
                $sport_poster = URL::to('upload/source/' . $sports_data->video_image);
                $sport_duration = $sports_data->duration;
                $sport_access = $sports_data->video_access;

                $response[] = array("sport_id" => $sport_id, "sport_title" => $sport_title, "sport_image" => $sport_poster, "sport_duration" => $sport_duration, "sport_access" => $sport_access);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function sports_by_category()
    {
        $this->default_file();
        $get_data = $_POST;

        $sports_cat_id = $get_data['category_id'];

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $sports_video_list = Sports::where('status', 1)->where('sports_cat_id', $sports_cat_id)->orderBy('id', 'ASC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $sports_video_list = Sports::where('status', 1)->where('sports_cat_id', $sports_cat_id)->orderBy('video_title', 'ASC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $sports_video_list = Sports::where('status', 1)->where('sports_cat_id', $sports_cat_id)->inRandomOrder()->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            } else {
                $sports_video_list = Sports::where('status', 1)->where('sports_cat_id', $sports_cat_id)->orderBy('id', 'DESC')->paginate(12);
                $sports_video_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $sports_video_list = Sports::where('status', 1)->where('sports_cat_id', $sports_cat_id)->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = Sports::where('status', '1')->where('sports_cat_id', $sports_cat_id)->count();

        if ($sports_video_list->count()) {
            foreach ($sports_video_list as $sports_data) {

                $sport_id = $sports_data->id;
                $sport_title = stripslashes($sports_data->video_title);
                $sport_poster = URL::to('upload/source/' . $sports_data->video_image);
                $sport_duration = $sports_data->duration;
                $sport_access = $sports_data->video_access;

                $response[] = array("sport_id" => $sport_id, "sport_title" => $sport_title, "sport_image" => $sport_poster, "sport_duration" => $sport_duration, "sport_access" => $sport_access);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function sports_details()
    {
        $this->default_file();
        $get_data = $_POST;

        $user_id = $get_data['user_id'];

        if ($user_id != "") {
            $user_plan_status = check_app_user_plan($user_id);
        } else {
            $user_plan_status = false;
        }

        $sport_id = $get_data['sport_id'];
        $sports_info = Sports::where('id', $sport_id)->first();

        $sport_id = $sports_info->id;
        $sport_title = stripslashes($sports_info->video_title);
        $sport_image = URL::to('upload/source/' . $sports_info->video_image);
        $sport_access = $sports_info->video_access;
        $description = stripslashes($sports_info->video_description);

        $duration = $sports_info->duration;
        $date = isset($sports_info->date) ? date('M d Y', $sports_info->date) : '';

        $video_type = $sports_info->video_type;

        if ($video_type == "Local") {
            $video_url = $sports_info->video_url ? URL::to('upload/source/' . $sports_info->video_url) : "";
        } else {
            $video_url = $sports_info->video_url ? $sports_info->video_url : "";
        }

        $video_url_480 = $sports_info->video_url_480 ? $sports_info->video_url_480 : '';
        $video_url_720 = $sports_info->video_url_720 ? $sports_info->video_url_720 : '';
        $video_url_1080 = $sports_info->video_url_1080 ? $sports_info->video_url_1080 : '';

        $subtitle_language1 = $sports_info->subtitle_language1 ? $sports_info->subtitle_language1 : '';
        $subtitle_url1 = $sports_info->subtitle_url1 ? $sports_info->subtitle_url1 : '';

        $subtitle_language2 = $sports_info->subtitle_language2 ? $sports_info->subtitle_language2 : '';
        $subtitle_url2 = $sports_info->subtitle_url2 ? $sports_info->subtitle_url2 : '';

        $subtitle_language3 = $sports_info->subtitle_language3 ? $sports_info->subtitle_language3 : '';
        $subtitle_url3 = $sports_info->subtitle_url3 ? $sports_info->subtitle_url3 : '';

        $download_enable = $sports_info->download_enable ? 'true' : 'false';
        $download_url = $sports_info->download_url ? $sports_info->download_url : "";

        $sport_cat_id = $sports_info->sports_cat_id;


        $category_name = SportsCategory::getSportsCategoryInfo($sport_cat_id, 'category_name');

        $video_quality = $sports_info->video_quality ? 'true' : 'false';
        $subtitle_on_off = $sports_info->subtitle_on_off ? 'true' : 'false';

        $response = array("sport_id" => $sport_id, "sport_title" => $sport_title, "sport_image" => $sport_image, "sport_access" => $sport_access, "description" => $description, "sport_duration" => $duration, "date" => $date, "video_type" => $video_type, "video_url" => $video_url, 'video_url_480' => $video_url_480, 'video_url_720' => $video_url_720, 'video_url_1080' => $video_url_1080, 'sport_cat_id' => $sport_cat_id, 'category_name' => $category_name, 'download_enable' => $download_enable, 'download_url' => $download_url, 'subtitle_language1' => $subtitle_language1, 'subtitle_url1' => $subtitle_url1, 'subtitle_language2' => $subtitle_language2, 'subtitle_url2' => $subtitle_url2, 'subtitle_language3' => $subtitle_language3, 'subtitle_url3' => $subtitle_url3, 'video_quality' => $video_quality, 'subtitle_on_off' => $subtitle_on_off);


        //Related Movies List
        $related_sports_list = Sports::where('status', 1)->where('id', '!=', $sport_id)->where('sports_cat_id', $sport_cat_id)->orderBy('id', 'DESC')->get();

        if ($related_sports_list->count()) {
            foreach ($related_sports_list as $related_sports_data) {
                $l_sport_id = $related_sports_data->id;
                $l_sport_title = stripslashes($related_sports_data->video_title);
                $l_sport_poster = URL::to('upload/source/' . $related_sports_data->video_image);
                $l_sport_access = $related_sports_data->video_access;
                $l_sport_duration = $related_sports_data->duration;

                $response['related_sports'][] = array("sport_id" => $l_sport_id, "sport_title" => $l_sport_title, "sport_image" => $l_sport_poster, "sport_access" => $l_sport_access, "sport_duration" => $l_sport_duration);
            }
        } else {
            $response['related_sports'] = array();
        }

        //Recently Watched
        if (isset($get_data['user_id']) && $get_data['user_id'] != "") {
            $current_user_id = $get_data['user_id'];
            $video_id = $sports_info->id;

            $recently_video_count = RecentlyWatched::where('video_type', 'Sports')->where('user_id', $current_user_id)->where('video_id', $video_id)->count();

            if ($recently_video_count <= 0) {
                //Current user recently count
                $current_user_video_count = RecentlyWatched::where('user_id', $current_user_id)->count();

                if ($current_user_video_count == 10) {
                    DB::table("recently_watched")
                        ->where("user_id", "=", $current_user_id)
                        ->orderBy("id", "ASC")
                        ->take(1)
                        ->delete();

                    $video_recent_obj = new RecentlyWatched;
                    $video_recent_obj->video_type = 'Sports';
                    $video_recent_obj->user_id = $current_user_id;
                    $video_recent_obj->video_id = $video_id;
                    $video_recent_obj->save();
                } else {
                    $video_recent_obj = new RecentlyWatched;
                    $video_recent_obj->video_type = 'Sports';
                    $video_recent_obj->user_id = $current_user_id;
                    $video_recent_obj->video_id = $video_id;
                    $video_recent_obj->save();
                }
            }
        }

        return \Response::json(array(
            'response_data' => $response,
            'user_plan_status' => $user_plan_status,
            'status_code' => 200
        ));
    }

    public function livetv_category()
    {
        $cat_list = TvCategory::where('status', 1)->orderby('category_name')->get();
        return response([
            'status' => true,
            'data' => $cat_list,
            'msg' => ''
        ]);
    }

    public function livetv()
    {
        $this->default_file();
        $get_data = $_POST;

        if (isset($get_data['filter'])) {
            $keyword = $get_data['filter'];

            if ($keyword == 'old') {
                $live_tv_list = LiveTV::where('status', 1)->orderBy('id', 'ASC')->paginate(12);
                $live_tv_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'alpha') {
                $live_tv_list = LiveTV::where('status', 1)->orderBy('channel_name', 'ASC')->paginate(12);
                $live_tv_list->appends(\Request::only('filter'))->links();
            } else if ($keyword == 'rand') {
                $live_tv_list = LiveTV::where('status', 1)->inRandomOrder()->paginate(12);
                $live_tv_list->appends(\Request::only('filter'))->links();
            } else {
                $live_tv_list = LiveTV::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
                $live_tv_list->appends(\Request::only('filter'))->links();
            }
        } else {
            $live_tv_list = LiveTV::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
        }

        $total_records = LiveTV::where('status', '1')->count();
        if ($live_tv_list->count()) {
            foreach ($live_tv_list as $live_tv_data) {
                $tv_id = $live_tv_data->id;
                $tv_title = stripslashes($live_tv_data->channel_name);
                $tv_logo = ($live_tv_data->channel_thumb);
                $tv_access = $live_tv_data->channel_access;
                $tv_description = $live_tv_data->channel_description;
                $response[] = array("tv_id" => $tv_id, "tv_title" => $tv_title, "tv_logo" => $tv_logo, "tv_description" => $tv_description, "tv_access" => $tv_access);
            }
        } else {
            $response = array();
        }

        return \Response::json(array(
            'response_data' => $response,
            'total_records' => $total_records,
            'status_code' => 200
        ));
    }

    public function livetv_by_category()
    {
        $this->default_file();
        $get_data = $_POST;

        $channel_cat_id = $get_data['category_id'];

        $items = LiveTV::query()->with('category')->where('status', 1)->where('channel_cat_id', $channel_cat_id)->orderBy('id', 'DESC')->paginate(config('data.per_page'));

        return response()->json([
            'status' => true,
            'data' => $items->items(),
            'currentPage' => $items->currentPage(),
            'totalItems' => $items->total(),
            'msg' => ''
        ]);
    }

    public function livetv_details(Request $request)
    {

        $live_tv_id = $request['tv_id'];

        $live_tv_info = LiveTV::where('id', $live_tv_id)
            ->orWhere('mediaId', $live_tv_id)
            ->with('category')
            ->first();
        $live_tv_info->append('related');

        //Recently Watched
        //        if (isset($user_id) && $user_id != "") {
        //            $current_user_id = $user_id;
        //            $video_id = $live_tv_info->id;
        //
        //            $recently_video_count = RecentlyWatched::where('video_type', 'LiveTV')->where('user_id', $current_user_id)->where('video_id', $video_id)->count();
        //
        //            if ($recently_video_count <= 0) {
        //                //Current user recently count
        //                $current_user_video_count = RecentlyWatched::where('user_id', $current_user_id)->count();
        //
        //                if ($current_user_video_count == 10) {
        //                    DB::table("recently_watched")
        //                        ->where("user_id", "=", $current_user_id)
        //                        ->orderBy("id", "ASC")
        //                        ->take(1)
        //                        ->delete();
        //
        //                    $video_recent_obj = new RecentlyWatched;
        //                    $video_recent_obj->video_type = 'LiveTV';
        //                    $video_recent_obj->user_id = $current_user_id;
        //                    $video_recent_obj->video_id = $video_id;
        //                    $video_recent_obj->save();
        //                } else {
        //                    $video_recent_obj = new RecentlyWatched;
        //                    $video_recent_obj->video_type = 'LiveTV';
        //                    $video_recent_obj->user_id = $current_user_id;
        //                    $video_recent_obj->video_id = $video_id;
        //                    $video_recent_obj->save();
        //                }
        //            }
        //
        //        }
        return response([
            'status' => true,
            'data' => $live_tv_info,
            //            'user_plan_status' => $user_plan_status,
            'msg' => ''
        ]);
    }

    public function search(Request $request)
    {
        // $this->default_file();
        // $get_data = $_POST;

        $keyword = $request->search_text;

        //Movie Data
        $movies_list = Movies::where('status', 1)->where("video_title", "LIKE", "%$keyword%")->orderBy('video_title')->get();

        if ($movies_list->count()) {
            foreach ($movies_list as $movie_data) {

                $movie_id = $movie_data->id;
                $mediaId = $movie_data->mediaId;
                $movie_title = stripslashes($movie_data->video_title);
                $movie_poster = ($movie_data->video_image_thumb);
                $movie_duration = $movie_data->duration;
                $movie_access = $movie_data->video_access;

                $response['movies'][] = array("movie_id" => $movie_id, "movie_title" => $movie_title, "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access, "mediaId" => $mediaId);
            }
        } else {
            $response['movies'] = array();
        }

        //Movie End

        //Show Start
        $series_list = Series::where('status', 1)->where("series_name", "LIKE", "%$keyword%")->orderBy('series_name')->get();

        if ($series_list->count()) {
            foreach ($series_list as $series_data) {
                $show_id = $series_data->id;
                $mediaId = $series_data->mediaId;
                $show_title = stripslashes($series_data->series_name);
                $show_poster = ($series_data->series_poster);

                $response['shows'][] = array("show_id" => $show_id, "show_title" => $show_title, "show_poster" => $show_poster, "mediaId" => $mediaId);
            }
        } else {
            $response['shows'] = array();
        }
        //Show End

        //Sports Start
        $sports_video_list = Sports::where('status', 1)->where("video_title", "LIKE", "%$keyword%")->orderBy('video_title')->get();

        if ($sports_video_list->count()) {
            foreach ($sports_video_list as $sports_data) {

                $sport_id = $sports_data->id;
                $sport_title = stripslashes($sports_data->video_title);
                $sport_poster = URL::to('upload/source/' . $sports_data->video_image);
                $sport_duration = $sports_data->duration;
                $sport_access = $sports_data->video_access;

                $response['sports'][] = array("sport_id" => $sport_id, "sport_title" => $sport_title, "sport_image" => $sport_poster, "sport_duration" => $sport_duration, "sport_access" => $sport_access);
            }
        } else {
            $response['sports'] = array();
        }
        //Sports End

        //Live TV Start
        $live_tv_list = LiveTV::where('status', 1)->where("channel_name", "LIKE", "%$keyword%")->orderBy('channel_name')->get();

        if ($live_tv_list->count()) {
            foreach ($live_tv_list as $live_tv_data) {

                $tv_id = $live_tv_data->id;
                $tv_title = stripslashes($live_tv_data->channel_name);
                // $thumb = ($live_tv_data->channel_thumb);
                $tv_access = $live_tv_data->channel_access;

                $channel_cat_id = $live_tv_data->channel_cat_id;
                $channel_slug = $live_tv_data->channel_slug;
                $channel_description = $live_tv_data->channel_description;
                $thumbUrl = $live_tv_data->thumbUrl;
                $channel_url_type = $live_tv_data->channel_url_type;
                $channel_url = $live_tv_data->channel_url;
                $channel_url2 = $live_tv_data->channel_url2;
                $channel_url3 = $live_tv_data->channel_url3;
                $seo_title = $live_tv_data->seo_title;
                $seo_description = $live_tv_data->seo_description;
                $seo_keyword = $live_tv_data->seo_keyword;
                $status = $live_tv_data->status;
                $moderator_id = $live_tv_data->moderator_id;
                $mediaId = $live_tv_data->mediaId;

                $response['live_tv'][] = array(
                    "id" => $tv_id,
                    "name" => $tv_title,
                    "channelAccess" => $tv_access,
                    "categoryId" => $channel_cat_id,
                    "slug" => $channel_slug,
                    "description" => $channel_description,
                    "thumbUrl" => $thumbUrl,
                    "urlType" => $channel_url_type,
                    "url" => $channel_url,
                    "url2" => $channel_url2,
                    "url3" => $channel_url3,
                    "seoTitle" => $seo_title,
                    "seoDescription" => $seo_description,
                    "seoKeyword" => $seo_keyword,
                    "status" => $status,
                    "moderatorId" => $moderator_id,
                    "mediaId" => $mediaId

                );
            }
        } else {
            $response['live_tv'] = array();
        }
        //Live TV End

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }



    public function random_strings($length_of_string)
    {
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    public function PremiumPurchase(Request $request)
    {
        try {

            // Define the allowed platform values
            //  $allowedPlatforms = ['web', 'ios', 'android', 'roku', 'android_tv', 'apple_tv'];

            $validator = Validator::make($request->all(), [
                'platform' => 'required',
                'receipt' => 'required',
                'productId' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                return response()->json([
                    'status' => false,
                    'error' => $errors
                ], 403);
            }
            $user_record = auth('sanctum')->user();
            
            if ($request->platform == "ios" || $request->platform == "roku") {
                $receiptData = $this->validateReceiptId($request->receipt, $request->platform);

                if ($receiptData['status']) {
                    $current_timestamp = $receiptData['data']['purchase_date'];
                    $productId = $receiptData['data']['productId'];
                } else {
                    return response()->json(['status' => false, 'error' => $receiptData['error']], 403);
                }

                if ($productId != $request->productId) {
                    return response()->json(['status' => false, 'error' => "product id not matched with receipt product id"], 403);
                }
            } else {
                $current_timestamp = strtotime(date('Y-m-d'));
            }

            $subscription_plan_data = SubscriptionPlan::where("productId", $request->productId)->where("platform", $request->platform)->first();

            if (empty($subscription_plan_data)) {
                return response()->json(['status' => false, 'data' => [], 'error' => 'Subscription Plan Record not found'], 403);
            }
            $duration = $subscription_plan_data->plan_days;
            // Creating Premium Purchase record
                $amount=$subscription_plan_data->amount;
                $model = new PremiumPurchase();
                $model->platform = $request->platform;
                $model->receipt = $request->receipt;
                $model->productId = $request->productId;
                $model->amount = $amount;
                $model->userId = $user_record['id'];
                $model->save();
                
            $user = User::find($user_record['id']);

            $user->start_date = $current_timestamp;
            $user->exp_date = strtotime("+{$duration} days", $current_timestamp);
            $user->plan_id = $subscription_plan_data->id;
            $user->plan_amount = $subscription_plan_data->plan_price;
            $user->save();

            return response([
                'status' => true,
                'data' => ['user' => $user],
                'msg' => 'Subscription Successful'
            ]);
        } catch (\Exception $e) {
            // Handle the exception here
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function validateReceiptId($receipt, $platform)
    {
        if ($platform == 'ios') {
            $record = $this->VadidateIosReceipt($receipt);
            if ($record['status'] === 0) {
                $data['purchase_date'] = strtotime($record['receipt']['in_app'][0]['purchase_date']);
                $data['productId'] = $record['receipt']['in_app'][0]['product_id'];

                return [
                    'status' => true,
                    'data' => $data,
                ];
            } else {
                return [
                    'status' => false,
                    'error' => "Receipt not valid",
                ];
            }
        }

        if ($platform == 'roku') {
            $record = $this->ValidateRokuReceipt($receipt);
            if (!empty($record['status']) && $record['status'] === "Success") {
                $data['purchase_date'] = strtotime($record['originalPurchaseDate']);
                $data['productId'] = $record['productId'];

                return [
                    'status' => true,
                    'data' => $data,
                ];
            } else {
                return [
                    'status' => false,
                    'error' => "Receipt not valid",
                ];
            }
        }
    }

    public function ValidateRokuReceipt($receipt)
    {
        $verificationUrl = 'https://apipub.roku.com/listen/transaction-service.svc/validate-transaction/453B0EE7F8A8414EBA71A3C00161B09B5642/' . $receipt;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $verificationUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/xml', // Request XML response
                'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8'
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            // Handle the cURL request failure
            echo 'cURL error: ' . curl_error($curl);
            // Return or do further error handling as needed
            exit;
        }

        curl_close($curl);

        if (strpos($response, '<') === 0) {
            // Parse the XML response into a SimpleXMLElement
            $xml = simplexml_load_string($response);
            // Convert the SimpleXMLElement to JSON
            $json = json_encode($xml, JSON_PRETTY_PRINT);
            return json_decode($json, true);
        } else {
            // Handle the case when the response is not in XML format (e.g., plain text or HTML)
            return ['error' => 'Response is not in XML format', 'response' => $response];
        }
    }



    public function VadidateIosReceipt($receipt)
    {
        $verificationUrl = 'https://sandbox.itunes.apple.com/verifyReceipt';

        $curl = curl_init();

        $requestData = json_encode([
            'receipt-data' => $receipt
        ]);

        curl_setopt_array($curl, [
            CURLOPT_URL => $verificationUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $requestData,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json, text/plain',
                'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response === false) {
            // Handle the cURL request failure
            echo 'cURL error: ' . curl_error($curl);
            // Return or do further error handling as needed
            exit;
        }


        return  $responseData = json_decode($response, true);
    }

    public function  addFavourite(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required',

        ]);

        $slug = $request->slug;

        $liveTv = LiveTV::where('channel_slug', $slug)->first();
        $series = Series::where('series_slug', $slug)->first();
        $movie = Movies::where('video_slug', $slug)->first();
        $user_record = auth('sanctum')->user();
        $slugReference = new Favourite;
        $slugReference->user_id = $user_record['id'];
        $slugReference->slug = $slug;
        $slugReference->live_tv_id = $liveTv ? $liveTv->id : null;
        $slugReference->series_id = $series ? $series->id : null;
        $slugReference->movie_id = $movie ? $movie->id : null;

        $slugReference->save();

        return response([
            'status' => true,
            'data' => null,
            'msg' => 'Favourite Added Successful'
        ]);
    }

    public function removeFavourite(Request $request)
    {

        $data = $request->validate([
            'slug' => 'required',

        ]);
        $slug = $request->slug;

        $slugReference  = Favourite::query()->where('slug', $slug)->first();

        if (!$slugReference) {

            return response([
                'status' => false,
                'data' => null,
                'msg' => 'Slug  not found'
            ]);
        }

        if ($slugReference->delete()) {
            return response([
                'status' => true,
                'data' => null,
                'msg' => 'Favourite removed successfully'
            ]);
        }
    }

    public function userFavourites(Request $request)
    {

        $user_record = auth('sanctum')->user();
        $user_id = $user_record['id'];

        $favourites = Favourite::where('user_id', $user_id)->get();


        $live_tv_ids = [];
        $series_ids = [];
        $movie_ids = [];

        foreach ($favourites as $favourite) {
            if ($favourite->live_tv_id) {
                $live_tv_ids[] = $favourite->live_tv_id;
            }
            if ($favourite->series_id) {
                $series_ids[] = $favourite->series_id;
            }
            if ($favourite->movie_id) {
                $movie_ids[] = $favourite->movie_id;
            }
        }

        // return [
        //     'live_tv_ids' => $live_tv_ids,
        //     'series_ids' => $series_ids,
        //     'movie_ids' => $movie_ids,
        // ];












        $movies_list = Movies::where('status', 1)->whereIn("id", $movie_ids)->orderBy('video_title')->get();
        if ($movies_list->count()) {
            foreach ($movies_list as $movie_data) {

                $movie_id = $movie_data->id;
                $movie_title = stripslashes($movie_data->video_title);
                $movie_poster = ($movie_data->video_image_thumb);
                $movie_duration = $movie_data->duration;
                $movie_access = $movie_data->video_access;

                $response['movies'][] = array("movie_id" => $movie_id, "movie_title" => $movie_title, "movie_poster" => $movie_poster, "movie_duration" => $movie_duration, "movie_access" => $movie_access);
            }
        } else {
            $response['movies'] = array();
        }

        //Movie End

        //Show Start
        $series_list = Series::where('status', 1)->whereIn("id", $series_ids)->orderBy('series_name')->get();

        if ($series_list->count()) {
            foreach ($series_list as $series_data) {
                $show_id = $series_data->id;
                $show_title = stripslashes($series_data->series_name);
                $show_poster = ($series_data->series_poster);

                $response['shows'][] = array("show_id" => $show_id, "show_title" => $show_title, "show_poster" => $show_poster);
            }
        } else {
            $response['shows'] = array();
        }
        //Show End

        //Sports Start
        // $sports_video_list = Sports::where('status', 1)->where("video_title", "LIKE", "%$keyword%")->orderBy('video_title')->get();

        // if ($sports_video_list->count()) {
        //     foreach ($sports_video_list as $sports_data) {

        //         $sport_id = $sports_data->id;
        //         $sport_title = stripslashes($sports_data->video_title);
        //         $sport_poster = URL::to('upload/source/' . $sports_data->video_image);
        //         $sport_duration = $sports_data->duration;
        //         $sport_access = $sports_data->video_access;

        //         $response['sports'][] = array("sport_id" => $sport_id, "sport_title" => $sport_title, "sport_image" => $sport_poster, "sport_duration" => $sport_duration, "sport_access" => $sport_access);
        //     }
        // } else {
        $response['sports'] = array();
        //  }
        //Sports End

        //Live TV Start
        $live_tv_list = LiveTV::where('status', 1)->whereIn("id", $live_tv_ids)->orderBy('channel_name')->get();


        if ($live_tv_list->count()) {
            foreach ($live_tv_list as $live_tv_data) {

                $tv_id = $live_tv_data->id;
                $tv_title = stripslashes($live_tv_data->channel_name);
                // $thumb = ($live_tv_data->channel_thumb);
                $tv_access = $live_tv_data->channel_access;

                $channel_cat_id = $live_tv_data->channel_cat_id;
                $channel_slug = $live_tv_data->channel_slug;
                $channel_description = $live_tv_data->channel_description;
                $thumbUrl = $live_tv_data->thumbUrl;
                $channel_url_type = $live_tv_data->channel_url_type;
                $channel_url = $live_tv_data->channel_url;
                $channel_url2 = $live_tv_data->channel_url2;
                $channel_url3 = $live_tv_data->channel_url3;
                $seo_title = $live_tv_data->seo_title;
                $seo_description = $live_tv_data->seo_description;
                $seo_keyword = $live_tv_data->seo_keyword;
                $status = $live_tv_data->status;
                $moderator_id = $live_tv_data->moderator_id;

                $response['live_tv'][] = array(
                    "id" => $tv_id,
                    "name" => $tv_title,
                    "channelAccess" => $tv_access,
                    "categoryId" => $channel_cat_id,
                    "slug" => $channel_slug,
                    "description" => $channel_description,
                    "thumbUrl" => $thumbUrl,
                    "urlType" => $channel_url_type,
                    "url" => $channel_url,
                    "url2" => $channel_url2,
                    "url3" => $channel_url3,
                    "seoTitle" => $seo_title,
                    "seoDescription" => $seo_description,
                    "seoKeyword" => $seo_keyword,
                    "status" => $status,
                    "moderatorId" => $moderator_id,

                );
            }
        } else {
            $response['live_tv'] = array();
        }
        //Live TV End

        return \Response::json(array(
            'response_data' => $response,
            'status_code' => 200
        ));
    }


    // api create for user_sync
    public function post_user_sync(Request $request)
    {
        // Step 1: Validate input fields
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'type_id' => 'required|integer',
            'profile_id' => 'required|integer',
            'progress' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json([
                'status' => false,
                'error' => $errors
            ], 400);
        }

        try {
            // Step 2: Create a new UserSync instance and fill it with data
            $user_record = auth('sanctum')->user();
            $user_id = $user_record->id;
            $userSync = new UserSync;
            $userSync->user_id = $user_id;
            $userSync->type = $request->input('type');
            $userSync->type_id = $request->input('type_id');
            $userSync->progress = $request->input('progress');
            $userSync->save();

            // updating profile sync
            //Create a new ProfileSync instance and fill it with data
            $profileSync = new ProfileSync;
            $profileSync->user_id = $user_id;
            $profileSync->type = $request->input('type');
            $profileSync->type_id = $request->input('type_id');
            $profileSync->progress = $request->input('progress');
            $userSync->profile_id = $request->input('profile_id');
            $profileSync->save();           


            return response()->json(["status" => true, "msg" => "Data save", 'data' => []]);
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500);
            // Handle any exceptions that occur during the database operation
            return response()->json(["status" => false, 'error' => $e->getMessage(), 'data' => []], 500);
        }
    }
    //get_user_sync
    public function get_user_sync(Request $request)
    {
        try {
            $user_record = auth('sanctum')->user();
            $user_id = $user_record->id;
            $userSyncData = UserSync::where('user_id', $user_id)->orderby('id', 'DESC')->get();
            
            $transformedData = $userSyncData->map(function ($item) {

                $data = $item->data;

                $mappedData = [
                    'id' => $item->id,
                    'itemType' => $item->type,
                    'itemId' => $item->type_id,
                    'progress' => $item->progress,
                    'mediaId' => $data->mediaId,
                ];

                if ($item->type === 'movie') {
                    $mappedData += [
                        'thumbUrl' => $data->thumbUrl,
                        'videoAccess' => $data->videoAccess,
                        'langId' => $data->langId,
                        'title' => $data->title,
                        'releaseDate' => $data->releaseDate,
                        'description' => $data->description,
                        'slug' => $data->slug,
                        'image' => $data->video_image,
                        'type' => $data->type,
                        'quality' => $data->quality,
                        'downloadEnable' => $data->downloadEnable,
                        'downloadUrl' => $data->download_url,
                        'subtitleOnOff' => $data->subtitleOnOff,
                        'subtitleLanguage1' => $data->subtitle_language1,
                        'subtitleUrl1' => $data->subtitle_url1,
                        'subtitleLanguage2' => $data->subtitle_language2,
                        'subtitleUrl2' => $data->subtitle_url2,
                        'subtitleLanguage3' => $data->subtitle_language3,
                        'subtitleUrl3' => $data->subtitle_url3,
                        'imdbId' => $data->imdb_id,
                        'imdbRating' => $data->imdb_rating,
                        'imdbVotes' => $data->imdb_votes,
                        'seoDescription' => $data->seoDescription,
                        'seoKeyword' => $data->seoKeyword,
                        'moderatorId' => $data->moderatorId,
                        'createdAt' => $data->created_at,
                        'updatedAt' => $data->updated_at,
                        'imageThumb' => $data->thumbUrl,
                        'genres' => $data->genres,
                        'posterUrl' => $data->posterUrl,
                        'videoUrl' => $data->videoUrl,
                        'videoUrl480' => $data->videoUrl480,
                        'videoUrl720' => $data->videoUrl720,
                        'videoUrl1080' => $data->videoUrl1080,
                        'language' => $data->language,

                    ];
                }

                if ($item->type === 'episode') {
                    $mappedData += [

                        'seasonId' => $data->episode_season_id,
                        'seriesId' => $data->episode_series_id,
                        'imageUrl' => $data->imageUrl,
                        'videoAccess' => $data->videoAccess,
                        'genreId' => $data->genreId,
                        'episodeNumber' => $data->episodeNumber,
                        'videoTitle' => $data->videoTitle,
                        'releaseDate' => $data->releaseDate,
                        'videoDescription' => $data->videoDescription,
                        'videoSlug' => $data->videoSlug,
                        'videoType' => $data->videoType,
                        'videoQuality' => $data->videoQuality,
                        'videoUrl' => $data->videoUrl,
                        'videoUrl480' => $data->video_url_480,
                        'videoUrl720' => $data->video_url_720,
                        'videoUrl1080' => $data->video_url_1080,
                        'downloadEnable' => $data->downloadEnable,
                        'downloadUrl' => $data->download_url,
                        'subtitleOnOff' => $data->subtitle_on_off,
                        'subtitleLanguage1' => $data->subtitle_language1,
                        'subtitleUrl1' => $data->subtitle_url1,
                        'subtitleLanguage2' => $data->subtitle_language2,
                        'subtitleUrl2' => $data->subtitle_url2,
                        'subtitleLanguage3' => $data->subtitle_language3,
                        'subtitleUrl3' => $data->subtitle_url3,
                        'imdbId' => $data->imdb_id,
                        'imdbRating' => $data->imdb_rating,
                        'imdbVotes' => $data->imdb_votes,
                        'seoTitle' => $data->seo_title,
                        'seoDescription' => $data->seo_description,
                        'seoKeyword' => $data->seo_keyword,
                        'moderatorId' => $data->moderator_id,
                        'createdAt' => $data->created_at,
                        'updatedAt' => $data->updatedAt,
                        'videoImage' => $data->videoImage,

                    ];
                }

                return $mappedData;
            });



            return response()->json([
                "status" => true,
                "msg" => "Data get successfully",
                'data' => $transformedData

            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'error' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    //get_profile_sync
    public function get_user_profile_sync(Request $request)
    {
        try {
            $user_record = auth('sanctum')->user();
            $user_id = $user_record->id;
            $profile_id = $request->profile_id;
            $profileSyncData = ProfileSync::where('profile_id', $profile_id)->orderby('id', 'DESC')->get();
            
            $transformedData = $profileSyncData->map(function ($item) {

                $data = $item->data;

                $mappedData = [
                    'id' => $item->id,
                    'itemType' => $item->type,
                    'itemId' => $item->type_id,
                    'progress' => $item->progress,
                    'mediaId' => $data->mediaId,
                ];

                if ($item->type === 'movie') {
                    $mappedData += [
                        'thumbUrl' => $data->thumbUrl,
                        'videoAccess' => $data->videoAccess,
                        'langId' => $data->langId,
                        'title' => $data->title,
                        'releaseDate' => $data->releaseDate,
                        'description' => $data->description,
                        'slug' => $data->slug,
                        'image' => $data->video_image,
                        'type' => $data->type,
                        'quality' => $data->quality,
                        'downloadEnable' => $data->downloadEnable,
                        'downloadUrl' => $data->download_url,
                        'subtitleOnOff' => $data->subtitleOnOff,
                        'subtitleLanguage1' => $data->subtitle_language1,
                        'subtitleUrl1' => $data->subtitle_url1,
                        'subtitleLanguage2' => $data->subtitle_language2,
                        'subtitleUrl2' => $data->subtitle_url2,
                        'subtitleLanguage3' => $data->subtitle_language3,
                        'subtitleUrl3' => $data->subtitle_url3,
                        'imdbId' => $data->imdb_id,
                        'imdbRating' => $data->imdb_rating,
                        'imdbVotes' => $data->imdb_votes,
                        'seoDescription' => $data->seoDescription,
                        'seoKeyword' => $data->seoKeyword,
                        'moderatorId' => $data->moderatorId,
                        'createdAt' => $data->created_at,
                        'updatedAt' => $data->updated_at,
                        'imageThumb' => $data->thumbUrl,
                        'genres' => $data->genres,
                        'posterUrl' => $data->posterUrl,
                        'videoUrl' => $data->videoUrl,
                        'videoUrl480' => $data->videoUrl480,
                        'videoUrl720' => $data->videoUrl720,
                        'videoUrl1080' => $data->videoUrl1080,
                        'language' => $data->language,

                    ];
                }

                if ($item->type === 'episode') {
                    $mappedData += [

                        'seasonId' => $data->episode_season_id,
                        'seriesId' => $data->episode_series_id,
                        'imageUrl' => $data->imageUrl,
                        'videoAccess' => $data->videoAccess,
                        'genreId' => $data->genreId,
                        'episodeNumber' => $data->episodeNumber,
                        'videoTitle' => $data->videoTitle,
                        'releaseDate' => $data->releaseDate,
                        'videoDescription' => $data->videoDescription,
                        'videoSlug' => $data->videoSlug,
                        'videoType' => $data->videoType,
                        'videoQuality' => $data->videoQuality,
                        'videoUrl' => $data->videoUrl,
                        'videoUrl480' => $data->video_url_480,
                        'videoUrl720' => $data->video_url_720,
                        'videoUrl1080' => $data->video_url_1080,
                        'downloadEnable' => $data->downloadEnable,
                        'downloadUrl' => $data->download_url,
                        'subtitleOnOff' => $data->subtitle_on_off,
                        'subtitleLanguage1' => $data->subtitle_language1,
                        'subtitleUrl1' => $data->subtitle_url1,
                        'subtitleLanguage2' => $data->subtitle_language2,
                        'subtitleUrl2' => $data->subtitle_url2,
                        'subtitleLanguage3' => $data->subtitle_language3,
                        'subtitleUrl3' => $data->subtitle_url3,
                        'imdbId' => $data->imdb_id,
                        'imdbRating' => $data->imdb_rating,
                        'imdbVotes' => $data->imdb_votes,
                        'seoTitle' => $data->seo_title,
                        'seoDescription' => $data->seo_description,
                        'seoKeyword' => $data->seo_keyword,
                        'moderatorId' => $data->moderator_id,
                        'createdAt' => $data->created_at,
                        'updatedAt' => $data->updatedAt,
                        'videoImage' => $data->videoImage,

                    ];
                }

                return $mappedData;
            });



            return response()->json([
                "status" => true,
                "msg" => "Data get successfully",
                'data' => $transformedData

            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'error' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
