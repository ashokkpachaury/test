<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Coupons; 

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB; 

class CouponsController extends MainAdminController
{
	public function __construct()
    {
		$this->middleware('auth');
		parent::__construct();
        check_verify_purchase();	 
    }

    public function coupons_list()    { 
        if(Auth::User()->usertype!="Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.coupons');
        $coupons_list = Coupons::orderBy('id')->paginate(10);
        return view('admin.pages.coupons_list',compact('page_title','coupons_list'));
    }
    
    public function addCoupon() { 
        if(Auth::User()->usertype!="Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.add_coupon');
        return view('admin.pages.addeditcoupon',compact('page_title'));
    }
    
    public function addnew(Request $request)
    { 
        $inputs = $request->all();
        $data =  \Request::except(array('_token')) ;
        if(!empty($inputs['id'])){   
            $rule=array(
                'title' => 'required',
                'amount' => 'required'                 
            );
        }else{
            $rule=array(
                'title' => 'required',
                'amount' => 'required'                  
            );
        }

        $validator = \Validator::make($data,$rule);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->messages());
        } 
        
        if(!empty($inputs['id'])){
            $coupon_obj = Coupons::findOrFail($inputs['id']);
        }else{
            $coupon_obj = new Coupons;
        }

        $coupon_obj->title = $inputs['title'];
        $coupon_obj->promo_code = $inputs['promo_code']; 
        $coupon_obj->amount_type = $inputs['amount_type']; 
        $coupon_obj->amount = $inputs['amount'];  
        $coupon_obj->expiry_date = strtotime($inputs['expiry_date']); 
        $coupon_obj->users_limit = $inputs['users_limit']; 
        $coupon_obj->per_users_limit = $inputs['per_users_limit'];
        $coupon_obj->description = $inputs['description'];
        $coupon_obj->status = $inputs['status'];
        $coupon_obj->save();

        if(!empty($inputs['id'])){
            \Session::flash('flash_message', trans('words.successfully_updated'));
            return \Redirect::back();
        }else{
            \Session::flash('flash_message', trans('words.added'));
            return \Redirect::back();
        }
    }     
   
    public function editCoupon($coupon_id)    
    {     
        if(Auth::User()->usertype!="Admin")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }
        $page_title=trans('words.edit_coupon');
        $coupon_info = Coupons::findOrFail($coupon_id);
        return view('admin.pages.addeditcoupon',compact('page_title','coupon_info'));
    }	 
    
    public function delete($coupon_id)
    {
    	if(Auth::User()->usertype=="Admin")
        {
            $coupon_obj = Coupons::findOrFail($coupon_id);
            $coupon_obj->delete();
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
