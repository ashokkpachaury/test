<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\ProfileImages;
use App\Subprofiles; 
use App\SubscriptionPlan; 
use App\Transactions;
use App\Bank;
use App\SettingsAndroidApp;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str; 
use Mail;
use Illuminate\Support\Facades\Hash;

class UserProfileImagesController extends MainAdminController
{
	public function __construct()
    {
		$this->middleware('auth');	
		
		 parent::__construct();
         //check_verify_purchase();
         
    }
    
    public function user_profile_images()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        } 

        $page_title=trans('words.user_profile_images');

        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $user_profile_images = ProfileImages::where("title", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);

            $user_profile_images->appends(\Request::only('s'))->links();
        }
        else if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $user_profile_images = ProfileImages::where("id", "=",$id)->orderBy('id','DESC')->paginate(10);

            $user_profile_images->appends(\Request::only('id'))->links();
        }
        else
        {
          
            $user_profile_images = ProfileImages::orderBy('id')->paginate(10);
        }
         
        return view('admin.pages.user_profile_images',compact('page_title','user_profile_images'));
    } 
     
    public function add_user_profile_image()    { 
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $page_title=trans('words.add_user_profile_image');
          
        return view('admin.pages.addedituserprofileimage',compact('page_title'));
    }
    
    public function save_user_profile_image(Request $request)
    { 
    	 
    	$data =  \Request::except(array('_token')) ;
	    
	    $inputs = $request->all();	    
	    $rule=array(
            'name' => 'required',
            'url' => 'required',        
           );
	    
	   	$validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        } 
	      
		if(!empty($inputs['id'])){
            $profile_image = ProfileImages::findOrFail($inputs['id']);
        }else{
            $profile_image = new ProfileImages;
        }
		
		$profile_image->title = $inputs['name'];		 
		$profile_image->url = $inputs['url'];       
       
        $profile_image->is_default = $inputs['is_default'];
        $profile_image->save(); 
       
		
		if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{        

            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        }		     
        
         
    }     
    
    public function edit_user_profile_image($id)    
    {     
    	  if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }		
    	  $page_title=trans('words.edit_user_profile_images');

          $profile_image = ProfileImages::findOrFail($id);

           
          return view('admin.pages.addedituserprofileimage',compact('page_title','profile_image'));
        
    }	 
    
    public function delete_user_profile_image($id)
    {
    	
    	if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
    		
        $user = ProfileImages::findOrFail($id);         
		$user->delete();
		
        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }
}
