<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
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

class UsersController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');	
		
		 parent::__construct();
         check_verify_purchase();
         
    }
    
    public function user_list()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        } 

        $page_title=trans('words.users');

        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $user_list = User::where("usertype", "=","User")->where("name", "LIKE","%$keyword%")->orwhere("email", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);

            $user_list->appends(\Request::only('s'))->links();
        }
        else if(isset($_GET['plan_id']))
        {
            $plan_id = $_GET['plan_id'];
            $user_list = User::where("usertype", "=","User")->where("plan_id", "=",$plan_id)->orderBy('id','DESC')->paginate(10);

            $user_list->appends(\Request::only('plan_id'))->links();
        }
        else
        {
          
            $user_list = User::where('usertype', '=', 'User')->orderBy('id')->paginate(10);
        }
         
        return view('admin.pages.user_list',compact('page_title','user_list'));
    } 
     
    public function addUser()    { 
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $page_title=trans('words.add_user');

        $plan_list = SubscriptionPlan::where('status','1')->orderby('id')->get();
          
        return view('admin.pages.addedituser',compact('page_title','plan_list'));
    }
    
    public function addnew(Request $request)
    { 
    	 
    	$data =  \Request::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    if(!empty($inputs['id']))
	    {
			$rule=array(
		        'name' => 'required',
		        'email' => 'required|email|max:255|unique:users,email,'.$inputs['id'],
                'user_image' => 'mimes:jpg,jpeg,gif,png',
                'exp_date' => 'required'		        
		   	);
		}
		else
		{
			$rule=array(
		        'name' => 'required',
		        'email' => 'required|email|max:255|unique:users,email',
		        'password' => 'required|min:8|max:15',
                'user_image' => 'mimes:jpg,jpeg,gif,png'
		   	);
		}
	    
	   	$validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        } 
	      
		if(!empty($inputs['id'])){
            $user = User::findOrFail($inputs['id']);
        }else{
            $user = new User;
        }
		
        $icon = $request->file('user_image');           
        if($icon){
            //$tmpFilePath = 'upload/';
            $tmpFilePath = public_path('/upload/');
            $hardPath =  Str::slug($inputs['name'], '-').'-'.md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            //$img->fit(80, 80)->save($tmpFilePath.$hardPath. '-s.jpg');
            $user->user_image = $hardPath.'-b.jpg';
        }  
		
        //Get Plan info 
        $plan_id=$inputs['subscription_plan'];
        $plan_info = SubscriptionPlan::where('id',$plan_id)->where('status','1')->first();        
        $plan_days=$plan_info->plan_days;
        $plan_price=$plan_info->plan_price;

		$user->name = $inputs['name'];		 
		$user->email = $inputs['email'];
        
        if($inputs['password'])
        {
            $user->password= bcrypt($inputs['password']); 
        }        
        $user->phone = $inputs['phone'];
        $user->user_address = $inputs['user_address'];

        if(empty($inputs['id']) && $inputs['exp_date']=="")
        {
            $user->exp_date = strtotime(date('m/d/Y', strtotime("+$plan_days days")));
        }
        else
        {
            $user->exp_date = strtotime($inputs['exp_date']);
        }
        $user->plan_id = $plan_id;
        $user->plan_amount = $plan_price;
        $user->status = $inputs['status'];

        if(empty($user->referralCode)){
           $referralCode = $this->random_strings(10); 
           $user->referralCode = $referralCode;
        }
        $user->save(); 
        
        if(!empty($user->plan_id) && ($user->plan_id == $plan_id)){

        }else{
            //Transactions info update
            $payment_trans = new Transactions;
            $payment_trans->user_id = $inputs['id'];
            $payment_trans->email = $inputs['email'];
            $payment_trans->plan_id = $plan_id;
            $payment_trans->gateway = '';//$payment_gateway;
            $payment_trans->payment_amount = $plan_price;
            $payment_trans->payment_id = '';//$payment_id;
            $payment_trans->date = strtotime(date('m/d/Y H:i:s'));                    
            $payment_trans->save();
        }
		
		if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{
            $api_key = env('MAILCHIMP_APIKEY');
            $list_id = env('MAILCHIMP_LIST_ID');
    
            $firstname = $inputs['name'];
            $lastname = '';
            $email = $inputs['email'];
            if($email) {
                //Create mailchimp API url
                $memberId = md5(strtolower($email));
                $dataCenter = substr($api_key,strpos($api_key,'-')+1);
                $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId;
                //Member info
                $data = array(
                    'email_address'=>$email,
                    'status' => 'subscribed',
                    'merge_fields'  => [
                        'FNAME'     => $firstname,
                        'LNAME'     => $lastname,
                        'EMAIL'     => $email
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
                        $msg = 'Oops, please try again.[msg_code='.$httpCode.']';
                        break;
                }
            }

            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        }		     
        
         
    }     
    
    public function editUser($id)    
    {     
    	  if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }		
    	  $page_title=trans('words.edit_user');

          $user = User::findOrFail($id);

          $plan_list = SubscriptionPlan::where('status','1')->orderby('id')->get();
           
          return view('admin.pages.addedituser',compact('page_title','user','plan_list'));
        
    }	 
    
    public function delete($id)
    {
    	
    	if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
    		
        $user = User::findOrFail($id);         
		$user->delete();
		
        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }    

    public function user_history($id)    
    {     
          if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }       
          $page_title=trans('words.user_history');

          $user = User::findOrFail($id);

          $user_id=$user->id;

          $transactions_list = Transactions::where('user_id',$user_id)->orderBy('id','DESC')->paginate(10);
           
          $referrals_list = DB::table('referrals')->where('referredby',$user_id)->orderBy('id','DESC')->paginate(10);
          
          return view('admin.pages.user_history',compact('page_title','user','transactions_list','referrals_list'));
        
    }

    public function user_export()    
    {
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

          return Excel::download(new UsersExport, 'users.xlsx');

    }

    public function users_subscription_list() 
    {
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        } 

        $page_title=trans('words.users_subscription');

        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            if($_GET['s'] == 'per-day'){
                $users_subscription_list = User::where('plan_id', '!=', '0')->whereDate('created_at', Carbon::today())->orderBy('id','DESC')->paginate(10);
            }else if($_GET['s'] == 'per-month'){
               $users_subscription_list = User::where("usertype", "=","User")->where('plan_id', '!=', '0')->whereMonth('created_at', Carbon::now()->month)->orderBy('id','DESC')->paginate(10);
            }
            $users_subscription_list->appends(\Request::only('s'))->links();
        }
        else if(isset($_GET['plan_id']))
        {
            $plan_id = $_GET['plan_id'];
            $users_subscription_list = User::where("usertype", "=","User")->where("plan_id", "=",$plan_id)->orderBy('id','DESC')->paginate(10);

            $users_subscription_list->appends(\Request::only('plan_id'))->links();
        }else{
            $users_subscription_list = User::where("usertype", "=","User")->where('plan_id', '!=', '0')->orderBy('id')->paginate(10);
        }
        $plan_list = SubscriptionPlan::where('status','1')->orderby('id')->get();
        return view('admin.pages.user_subscription_list',compact('page_title','users_subscription_list','plan_list'));
    }
 
    //User Sub Profiles 
    public function subprofiles_list($id)    { 
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        } 
        $page_title=trans('words.sub_profiles');
        $parent_id=$id;
        $user_profile_list = User::where("parent_id",$parent_id)->where("usertype","Sub_Profile")->orderBy('id','DESC')->paginate(10);
        
        return view('admin.pages.sub_profiles_list',compact('page_title','user_profile_list','parent_id'));
    }  

    //Sub Admin
    public function admin_user_list()    { 
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        } 
        $page_title=trans('words.admin_list');
        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $user_list = User::where("usertype", "!=","User")->where("usertype", "!=","Moderator")->where("usertype", "!=","Sub_Profile")->where('id', '!=', 1)->where("name", "LIKE","%$keyword%")->where("email", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);
            $user_list->appends(\Request::only('s'))->links();
        }        
        else
        {
            $user_list = User::where('usertype', '!=', 'User')->where("usertype", "!=","Moderator")->where("usertype", "!=","Sub_Profile")->where('id', '!=', 1)->orderBy('id')->paginate(10);
        }
        return view('admin.pages.admin_user_list',compact('page_title','user_list'));
    } 

    public function admin_addUser()    { 
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        $page_title=trans('words.add_admin');
           
        return view('admin.pages.addeditadminuser',compact('page_title'));
    }
    
    public function admin_addnew(Request $request)
    { 
        $data =  \Request::except(array('_token')) ;
        $inputs = $request->all();
        if(!empty($inputs['id']))
        {
            $rule=array(
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email,'.$inputs['id'],
                'user_image' => 'mimes:jpg,jpeg,gif,png' 
            );
        }
        else
        {
            $rule=array(
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:8|max:15',
                'user_image' => 'mimes:jpg,jpeg,gif,png'
            );
        }
        
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }
          
        if(!empty($inputs['id'])){
            $user = User::findOrFail($inputs['id']);
        }else{
            $user = new User;
        }

        $icon = $request->file('user_image');        
        if($icon){
            $tmpFilePath = public_path('/upload/');
            $hardPath =  Str::slug($inputs['name'], '-').'-'.md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            $user->user_image = $hardPath.'-b.jpg';
        }  
         
        $user->usertype = $inputs['usertype'];
        $user->name = $inputs['name'];       
        $user->email = $inputs['email'];
        if($inputs['password'])
        {
            $user->password= bcrypt($inputs['password']); 
        }        
        $user->phone = $inputs['phone'];
        $user->status = $inputs['status'];
        $user->save(); 
        if(!empty($inputs['id'])){
            \Session::flash('flash_message', trans('words.successfully_updated'));
            return \Redirect::back();
        }else{
            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        }         
    }

    public function admin_editUser($id)    
    {     
          if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }       
          $page_title=trans('words.edit_admin');

          $user = User::findOrFail($id);
            
          return view('admin.pages.addeditadminuser',compact('page_title','user'));
        
    }

    public function admin_delete($id)
    {
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
        
        if($id!=1)
        {
            $user = User::findOrFail($id);         
            $user->delete();
        }   
        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();

    }  
   
    //Moderators
    public function moderators_list()    { 
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        } 
        $page_title=trans('words.moderator_list');
        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $moderator_list = User::where("usertype", "!=","User")->where("usertype", "!=","Admin")->where("usertype", "!=","Sub_Admin")->where("usertype", "!=","Sub_Profile")->where('id', '!=', 1)->where("name", "LIKE","%$keyword%")->where("email", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);
            $moderator_list->appends(\Request::only('s'))->links();
        }        
        else
        {
            $moderator_list = User::where("usertype", "!=","User")->where("usertype", "!=","Admin")->where("usertype", "!=","Sub_Admin")->where("usertype", "!=","Sub_Profile")->where('id', '!=', 1)->orderBy('id')->paginate(10);
        }
        return view('admin.pages.moderator_list',compact('page_title','moderator_list'));
    } 

    public function addModerator(){ 
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        }
        $page_title=trans('words.add_moderator');
        return view('admin.pages.addeditmoderator',compact('page_title'));
    }
    
    public function addnewModerator(Request $request)
    { 
        $data =  \Request::except(array('_token')) ;
        $inputs = $request->all();
        if(!empty($inputs['id']))
        {
            $rule=array(
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email,'.$inputs['id'],
                'user_image' => 'mimes:jpg,jpeg,gif,png' 
            );
        }
        else
        {
            $rule=array(
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:8|max:15',
                'user_image' => 'mimes:jpg,jpeg,gif,png'
            );
        }
        
        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        }
          
        if(!empty($inputs['id'])){
            $moderator = User::findOrFail($inputs['id']);
        }else{
            $moderator = new User;
        }

        $icon = $request->file('user_image');        
        if($icon){
            $tmpFilePath = public_path('/upload/');
            $hardPath =  Str::slug($inputs['name'], '-').'-'.md5(time());
            $img = Image::make($icon);
            $img->fit(250, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            $moderator->user_image = $hardPath.'-b.jpg';
        }  
         
        $moderator->usertype = $inputs['usertype'];
        $moderator->name = $inputs['name'];       
        $moderator->email = $inputs['email'];
        if($inputs['password'])
        {
            $moderator->password= bcrypt($inputs['password']); 
        }  
        $moderator->user_address = $inputs['user_address'];      
        $moderator->phone = $inputs['phone'];
        $moderator->status = $inputs['status'];
        $moderator->save(); 


        if(!empty($moderator->id)){
            if(!empty($inputs['bank_id'])){
                $bank = Bank::findOrFail($inputs['bank_id']);
            }else{
                $bank = new Bank;
            }
            $bank->moderator_id = $moderator->id;
            $bank->name = $inputs['bank_name'];          
            $bank->account = $inputs['bank_account']; 
            if(!empty($bank->name) && !empty($bank->account)){
                $bank->save(); 
            }
        }

        if(!empty($inputs['id'])){
            \Session::flash('flash_message', trans('words.successfully_updated'));
            return \Redirect::back();
        }else{
            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        }         
    }

    public function editModerator($id)    
    {     
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        }       
        $page_title=trans('words.edit_moderator');
        $moderator = User::findOrFail($id);

        $moderatorId = $id;
        $bank = Bank::where("moderator_id",$moderatorId)->orderBy('id','DESC')->get();

        return view('admin.pages.addeditmoderator',compact('page_title','moderator','bank'));
    }

    public function moderator_delete($id)
    {
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        }
        if($id!=1)
        {
            $moderator = User::findOrFail($id);         
            $moderator->delete();
        }   
        \Session::flash('flash_message', trans('words.deleted'));
        return redirect()->back();
    }  

    public function user_import_list() { 
        if(Auth::User()->usertype!="Admin"){
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('admin/dashboard');
        } 
        $page_title=trans('words.users_import');
        return view('admin.pages.user_import_list',compact('page_title'));
    } 

    public function uploadFile(Request $request){
        $file = $request->file('file');
        if ($file != null ){
            // File Details 
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            // Valid File Extensions
            $valid_extension = array("csv");
            // 2MB in Bytes
            $maxFileSize = 2097152; 
            // Check file extension
            if(in_array(strtolower($extension),$valid_extension)){
                // Check file size
                if($fileSize <= $maxFileSize){
                    // File upload location
                    $location = public_path('/upload/');
                    // Upload file
                    $file->move($location,$filename);
                    // Import CSV to Database
                    $filepath = $location."/".$filename;
                    // Reading file
                    $file = fopen($filepath,"r");
                    $importData_arr = array();
                    $i = 0;
                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata );
                        for ($c=0; $c < $num; $c++) {
                            $importData_arr[$i][] = $filedata [$c]; 
                        }
                        $i++;
                    }
                    fclose($file);
                    
                    foreach($importData_arr as $key => $importData){
                        if($importData[0] == 'ID'){
                            
                        }else{
                            if($importData[12] == 'administrator'){
                                $userType = 'Admin';
                            }else{
                                $userType = 'User';
                            }
                            if(!empty($importData[5])){
                                $user = User::where('email', $importData[5])->first();
                                if(!$user){
                                    $hashed_random_password = Str::random(8);
                                    $user = new User;
                                    $referralCode = $this->random_strings(10); 
                                    $user->usertype = $userType;
                                    $user->name = $importData[8]; 
                                    $user->email = $importData[5];         
                                    $user->password = bcrypt($hashed_random_password); //bcrypt($importData[3]); 
                                    if($userType == 'User'){
                                        $user->referralCode = $referralCode;
                                    } 
                                    $user->created_at = $importData[7];
                                    $user->save();  
                                    
                                    $user_data = array(
                                        'name' => $importData[8],
                                        'email' => $importData[5],
                                        'password' => $hashed_random_password,
                                        'site_logo' => getcong('site_logo'),
                                        'site_name' => getcong('site_name'),
                                        'site_email' => getcong('site_email')
                                    ); 
                                    \Mail::send('emails.newpassword', $user_data, function($message) use ($user_data){
                                        $message->to($user_data['email'], $user_data['name'])
                                        ->from($user_data['site_email'], $user_data['site_name'])
                                        ->subject('Welcome to '.$user_data['site_name']);
                                    }); 

                                }
                            }
                        }
                    }
                    Session::flash('flash_message','Import Successful.');
                    return \Redirect::back();
                }else{
                    return redirect()->back()->withErrors('File too large. File must be less than 2MB.');
                }
            }else{
                return redirect()->back()->withErrors('Invalid File Extension.');
            }
        }else{
            return redirect()->back()->withErrors('No File selected.');
        }
    }
        
    public function random_strings($length_of_string) 
    { 
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result), 0, $length_of_string); 
    }

}
