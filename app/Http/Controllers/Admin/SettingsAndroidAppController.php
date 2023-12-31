<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\SettingsAndroidApp;
use App\Movies;
use App\Series;
use App\Sports;
use App\LiveTV;
use App\Settings;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 

class SettingsAndroidAppController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
         check_verify_purchase();	
         
    }

    public function android_settings()
    { 
    	if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $page_title=trans('words.android_app_settings');
        
        $settings = SettingsAndroidApp::findOrFail('1'); 
        $settings1 = Settings::findOrFail('1');
        return view('admin.pages.android_settings',compact('page_title','settings','settings1'));
    }	 
    
    public function update_android_settings(Request $request)
    {  
    	  
    	$settings = SettingsAndroidApp::findOrFail('1');
 
	    
	    $data =  \Request::except(array('_token')) ;
	    
	    $rule=array(
		        'app_name' => 'required',
		        'app_logo' => 'required',
                'app_email' => 'required' 
		   		 );
	    
	   	$validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }
	    
	    $inputs = $request->all();

		$settings->app_name = addslashes($inputs['app_name']); 
		$settings->app_logo = $inputs['app_logo'];
        $settings->app_email = $inputs['app_email'];
        $settings->app_company = $inputs['app_company'];  
        $settings->app_website = $inputs['app_website'];  
        $settings->app_contact = $inputs['app_contact'];  
        $settings->app_version = $inputs['app_version'];  

        //$settings->onesignal_app_id = $inputs['onesignal_app_id'];  
        //$settings->onesignal_rest_key = $inputs['onesignal_rest_key'];  

        $settings->publisher_id = $inputs['publisher_id'];
        $settings->banner_ad_type = $inputs['banner_ad_type'];
        $settings->banner_ad = $inputs['banner_ad'];  
        $settings->banner_ad_id = $inputs['banner_ad_id'];
        $settings->fb_banner_id = $inputs['fb_banner_id'];
        $settings->interstitial_ad_type = $inputs['interstitial_ad_type'];
        $settings->interstital_ad = $inputs['interstital_ad'];  
        $settings->interstital_ad_id = $inputs['interstital_ad_id'];  
        $settings->fb_interstitial_id = $inputs['fb_interstitial_id'];
        $settings->interstital_ad_click = $inputs['interstital_ad_click'];  
        $settings->save(); 
        
        $settings1 = Settings::findOrFail('1');
        $settings1->site_name = addslashes($inputs['app_name']); 
		$settings1->site_logo = $inputs['app_logo'];
        $settings1->site_email = $inputs['app_email'];
        $settings1->footer_fb_link = addslashes($inputs['footer_fb_link']);
        $settings1->footer_twitter_link = addslashes($inputs['footer_twitter_link']);
        $settings1->footer_instagram_link = addslashes($inputs['footer_instagram_link']);
        $settings1->footer_google_play_link = addslashes($inputs['footer_google_play_link']);
        $settings1->footer_apple_store_link = addslashes($inputs['footer_apple_store_link']);
        $settings1->save();
        
	    Session::flash('flash_message', trans('words.successfully_updated'));

        return redirect()->back();
    }

    public function android_notification()
    { 
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $page_title=trans('words.android_app_notification_t');
        
        $movies_list = Movies::orderBy('id','DESC')->get();
        $series_list = Series::orderBy('id','DESC')->get();
        $live_tv_list = LiveTV::orderBy('id','DESC')->get();

        return view('admin.pages.android_notification',compact('page_title','movies_list','series_list','live_tv_list'));
    }
    
    public function send_android_notification(Request $request)
    {  
          
        $settings = SettingsAndroidApp::findOrFail('1');
        $data =  \Request::except(array('_token')) ;
        
        $rule=array(
            'notification_title' => 'required',
            'notification_msg' => 'required' 
        );
        
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }
        
        $inputs = $request->all();
        //Onesignal info 
        $onesignal_app_id=$settings->onesignal_app_id;
        $onesignal_rest_key=$settings->onesignal_rest_key;
        if($onesignal_app_id=='' OR $onesignal_rest_key=='')
        {
            Session::flash('flash_error_message', 'Onesignal app id or rest key not set.');
            return redirect()->back();
        } 

        $notification_title= $inputs['notification_title'];
        $notification_msg= $inputs['notification_msg'];
        $notification_image=$inputs['notification_image'];

        $notification_type=$inputs['notification_type'];

        if($notification_type=="Movies")
        {
            $post_id=$inputs['movie_id'];
        }
        else if($notification_type=="Shows")
        {
            $post_id=$inputs['series_id'];
        }
        else if($notification_type=="LiveTV")
        {
            $post_id=$inputs['live_tv_id'];
        }
        else
        {
            $post_id='';
        }        

        if($inputs['external_link']!="")
        {
        $external_link = $inputs['external_link'];
        }
        else
        {
        $external_link = false;
        }
        
        if($notification_image!='')
        {
                 
            $file_path = \URL::to('upload/source/'.$notification_image);
            $content = array(
                "en" => $notification_msg
            );

            $fields = array(
                'app_id' => $onesignal_app_id,
                'included_segments' => array('All'),                                            
                'data' => array("foo" => "bar","type"=>$notification_type,"post_id"=>$post_id,"external_link"=>$external_link),
                'headings'=> array("en" => $notification_title),
                'contents' => $content,
                'big_picture' =>$file_path,
                'ios_attachments' => array('id' => $file_path,),                     
            );

            $fields = json_encode($fields);
            print("\nJSON sent:\n");
            print($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                        'Authorization: Basic '.$onesignal_rest_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);
        } 
        else
        {
            $content = array(
                "en" => $notification_msg
            );

            $fields = array(
                'app_id' => $onesignal_app_id,
                'included_segments' => array('All'),                                      
                'data' => array("foo" => "bar","type"=>$notification_type,"post_id"=>$post_id,"external_link"=>$external_link),
                'headings'=> array("en" => $notification_title),
                'contents' => $content
            );

            $fields = json_encode($fields);
            print("\nJSON sent:\n");
            print($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                       'Authorization: Basic '.$onesignal_rest_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);
        }        
 
        Session::flash('flash_message', trans('words.android_app_notification_msg'));
        return redirect()->back();
    } 
    	
}
