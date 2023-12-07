<?php

namespace App\Http\Controllers\Moderator;

use Auth;
use App\User;
use App\Language;
use App\Genres;
use App\Bank;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str; 

class ModeratorController extends MainModeratorController
{
	public function __construct()
    {
		$this->middleware('auth');	
         
    }
 
    public function index()
    { 
        return view('moderator.pages.dashboard');
    }
	
	public function profile()
    { 
        $page_title = trans('words.profile');
        return view('moderator.pages.profile',compact('page_title'));
    }
    
    public function updateProfile(Request $request)
    {   
    	$id=Auth::user()->id;	 
    	$user = User::findOrFail($id);

	    $data =  \Request::except(array('_token')) ;
	    
	    $rule=array(
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'user_image' => 'mimes:jpg,jpeg,gif,png'
        );
    
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }

	    $inputs = $request->all();
		$icon = $request->file('user_image');
        if($icon){
            $tmpFilePath = public_path('/upload/');
            $hardPath =  Str::slug($inputs['name'], '-').'-'.md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            $user->user_image = $hardPath.'-b.jpg';
        }
        
		$user->name = $inputs['name'];          
		$user->email = $inputs['email']; 
        $user->phone = $inputs['phone'];
        $user->user_address = $inputs['user_address'];
        if($inputs['password'])
        {
            $user->password = bcrypt($inputs['password']);
        }
	    $user->save();
	    Session::flash('flash_message', trans('words.successfully_updated'));
        return redirect()->back();
    }
    
    public function updatePassword(Request $request)
    { 
    	 
        $data =  \Request::except(array('_token')) ;
        $rule  =  array(
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ) ;

        $validator = \Validator::make($data,$rule);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }    
         
	    $credentials = $request->only('password', 'password_confirmation');
        $user = \Auth::user();
        $user->password = bcrypt($credentials['password']);
        $user->save();
	    Session::flash('flash_message', trans('words.successfully_updated'));
        return redirect()->back();
    }

    public function bank()
    { 
        $moderatorId = Auth::User()->id;
        $page_title = trans('words.bank');
        $bank = Bank::where("moderator_id",$moderatorId)->orderBy('id','DESC')->get();
        return view('moderator.pages.bank',compact('page_title','bank'));
    }

    public function updateBank(Request $request)
    {   
    	$id=Auth::user()->id;	 

        $data =  \Request::except(array('_token')) ;
        
        $rule  =  array(
            'name' => 'required',
            'account' => 'required'
        );
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }   

        $inputs = $request->all();
        if(!empty($inputs['id'])){
            $bank = Bank::findOrFail($inputs['id']);
        }else{
            $bank = new Bank;
        }
        $bank->moderator_id = $id;
		$bank->name = $inputs['name'];          
		$bank->account = $inputs['account']; 
	    $bank->save();
	    Session::flash('flash_message', trans('words.successfully_updated'));
        return redirect()->back();
    }
	 
}
