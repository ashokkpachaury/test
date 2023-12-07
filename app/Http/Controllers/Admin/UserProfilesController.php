<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\ProfileImages;
use App\UserProfiles;
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

class UserProfilesController extends MainAdminController
{
	public function __construct()
    {
		$this->middleware('auth');	
		
		 parent::__construct();
         //check_verify_purchase();
         
    }
    
    public function user_profiles()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        } 

        $page_title=trans('words.profile');

        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $user_profiles = UserProfiles::where("title", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);
            $user_profiles->appends(\Request::only('s'))->links();
        }
        else if(isset($_GET['id']))
        {
            $id = $_GET['id'];
            $user_profiles = UserProfiles::where("id", "=",$id)->orderBy('id','DESC')->paginate(10);

            $user_profiles->appends(\Request::only('id'))->links();
        }
        else
        {
          
            $user_profiles = UserProfiles::orderBy('id')->paginate(10);
        }
         
        return view('admin.pages.user_profiles',compact('page_title','user_profiles'));
    }   
    
}
