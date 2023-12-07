<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Ads;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class AdsController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
        check_verify_purchase();
		  
    }
    public function ads_list()    { 
        
        if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
        {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
            
        }

        $page_title=trans('words.ad_management');
              
        $ads_list = Ads::orderBy('id')->paginate(10);
         
        return view('admin.pages.ads_list',compact('page_title','ads_list'));
    }

    public function addAds()    { 
        
        if(Auth::User()->usertype!="Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.add_ad');
        return view('admin.pages.ads_edit',compact('page_title'));
    }
     
    public function addnew(Request $request)
    {  
        $data =  \Request::except(array('_token')) ;
        $inputs = $request->all();        
        if(!empty($inputs['id'])){
            $ads_obj = Ads::findOrFail($inputs['id']);
        }else{
            $ads_obj = new Ads;
        }
        $ad_slug = Str::slug($inputs['ad_title'], '-');  
        $ads_obj->ad_title = $inputs['ad_title'];
        $ads_obj->ad_key = $ad_slug;
        $ads_obj->status = $inputs['status']; 

        $icon = $request->file('ad_image');        
        if($icon){
            $tmpFilePath = public_path('/upload/');
            $hardPath =  Str::slug($inputs['ad_title'], '-').'-'.md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            $ads_obj->ad_image = $hardPath.'-b.jpg';
        }

        $ads_obj->save();
        if(!empty($inputs['id'])){
            \Session::flash('flash_message', trans('words.successfully_updated'));
            return \Redirect::back();
        }else{
            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        } 
    }     
   
    public function ads_edit($ad_id)    
    {     
            if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
            {

                \Session::flash('flash_message', trans('words.access_denied'));

                return redirect('dashboard');
                
            }  
          $page_title=trans('words.ad_edit');

          $ad_info = Ads::findOrFail($ad_id);   

          return view('admin.pages.ads_edit',compact('page_title','ad_info'));
        
    }	 
     
    public function delete($ad_id)
    {
    	if(Auth::User()->usertype=="Admin")
        {
            $ad_obj = Ads::findOrFail($ad_id);
            $ad_obj->delete();

            \Session::flash('flash_message', trans('words.delete'));
            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');            
        
        }
    }	
}
