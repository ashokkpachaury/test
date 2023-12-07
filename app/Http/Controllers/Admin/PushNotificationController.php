<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Notify;
use App\Models\NotifyToken;
use Auth;
use App\User;
use App\PushNotification;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use URL;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class PushNotificationController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');

		parent::__construct();
        check_verify_purchase();

    }
    public function pushNotification_list()    {
//        $notification = PushNotification::find(3);
//        dd($notification);
//        Notify::send($notification);
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');

        }
        $page_title=trans('words.push_settings');
        $notification_list = PushNotification::query()->orderBy('id', 'desc' )->paginate(10);
        return view('admin.pages.push_notification_list',compact('page_title','notification_list'));
    }

    public function addPushNotification()    {
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.add_push');
        return view('admin.pages.addeditnotification',compact('page_title'));
    }

    public function addnew(Request $request)
    {

        $data =  \Request::except(array('_token')) ;
        $rule=array(
            'push_title' => 'required',
            'push_message' => 'required'
        );
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }
        $inputs = $request->all();
        if(!empty($inputs['id'])){
            $pushNotification = PushNotification::findOrFail($inputs['id']);
        }else{
            $pushNotification = new PushNotification;
        }

        $notification_image = '';
        $icon = $request->file('push_image');
        if($icon){
//            $tmpFilePath = public_path('/upload/');
//            $hardPath =  Str::slug($inputs['push_title'], '-').'-'.md5(time());
//            $img = Image::make($icon);
//            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
//            $pushNotification->image = $hardPath.'-b.jpg';
//            $notification_image = $hardPath.'-b.jpg';
        } else {
            $notification_image = '';
        }
        $pushNotification->name = $inputs['push_title'];
        $pushNotification->message = $inputs['push_message'];
        $pushNotification->save();

        /*
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
        $serverKey = 'server key goes here';
        if(!empty($notification_image)){
            $file_path = \URL::to('upload/'.$notification_image);
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => $inputs['push_title'],
                    "body" => $inputs['push_message'],
                    'sound' => 'default',
                    'image' => $notification_image,
                    'icon'  => '',
                ]
            ];
        }else{
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => $inputs['push_title'],
                    "body" => $inputs['push_message'],
                    'sound' => 'default',
                    'icon'  => '',
                ]
            ];
        }

        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        //print_r($result); */
        //        $notification = PushNotification::find(3);
//        dd($notification);
        Notify::send($pushNotification);

        if(!empty($inputs['id'])){
            \Session::flash('flash_message', trans('words.android_app_notification_msg'));
            return \Redirect::back();
        }else{
            \Session::flash('flash_message', trans('words.android_app_notification_msg'));
            return redirect()->route('notification.index');
        }
    }

    public function editPushNotification($push_id)
    {
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.edit_push');
        $notification = PushNotification::findOrFail($push_id);
        return view('admin.pages.addeditnotification',compact('page_title','notification'));
    }
    public function resend($id)
    {
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $notification = PushNotification::findOrFail($id);
        Notify::send($notification);

        \Session::flash('flash_message', trans('Successfully Notification Sended '));

        return back();
    }

    public function delete($push_id)
    {
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
    	if(Auth::User()->usertype=="Admin" OR Auth::User()->usertype=="Sub_Admin")
        {
            $push = PushNotification::findOrFail($push_id);
            $push->delete();
            \Session::flash('flash_message', trans('words.deleted'));
            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        }
    }

}
