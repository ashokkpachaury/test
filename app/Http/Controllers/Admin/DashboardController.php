<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Language;
use App\Genres;
use App\Movies;
use App\Series;
use App\Sports;
use App\LiveTV;
use App\SubscriptionPlan;
use App\Transactions;
use App\Models\PremiumPurchase;
use Illuminate\Support\Facades\DB;
 
 
use App\Http\Requests;
use Illuminate\Http\Request;

//use GeoIP;

class DashboardController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
          
         parent::__construct();
         check_verify_purchase();
         
    }
    public function index()
    { 
            if(Auth::User()->usertype!="Admin" AND Auth::User()->usertype!="Sub_Admin")
            {

                \Session::flash('flash_message', 'Access denied!');

                return redirect('dashboard');
                
             }
           
            
    	    $language = Language::count();
            $genres = Genres::count();
            $movies = Movies::count();
            $series = Series::count();
            $sports = Sports::count();
            $livetv = LiveTV::count();
            $users = User::where('usertype','User')->count(); 
            $plan = SubscriptionPlan::count();
            $transactions = PremiumPurchase::count();

            //Revenue
            $start_day = date('Y-m-d 00:00:00');
            $finish_day = date('Y-m-d 23:59:59');
            $daily_amount = $weekly_amount = $monthly_amount = $yearly_amount = 0;
            //DB::enableQueryLog();
            
            $daily_amount= PremiumPurchase::where('created_at', '>',$start_day)->where( 'created_at', '<',$finish_day)->sum('amount');
            
            
            //$daily_amount= Transactions::whereBetween('date', array(strtotime($start_day), strtotime($finish_day)))->sum('payment_amount');

            $start_week = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
            $finish_week = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
            $weekly_amount= PremiumPurchase::where('created_at', '>',$start_week)->where( 'created_at', '<',$finish_week)->sum('amount');
            
            
            $start_month = date('Y-m-d', strtotime('first day of this month'));
            $finish_month = date('Y-m-d', strtotime('last day of this month'));             
            $monthly_amount = PremiumPurchase::where('created_at', '>',$start_month)->where( 'created_at', '<',$finish_month)->sum('amount');
            
            //$monthly_amount = Transactions::whereBetween('date', array(strtotime($start_month), strtotime($finish_month)))->sum('payment_amount');

            $current_year = date('Y'); 
            $start_day_year = date('Y-m-d', strtotime("January 1st, ".$current_year));
            $end_day_year = date('Y-m-d', strtotime("December 31st,".$current_year));
            
            $yearly_amount = PremiumPurchase::where('created_at', '>',$start_day_year)->where( 'created_at', '<',$end_day_year)->sum('amount');

            $daily_amount=number_format($daily_amount,2);
            $weekly_amount=number_format($weekly_amount,2);
            $monthly_amount=number_format($monthly_amount,2);
            $yearly_amount=number_format($yearly_amount,2);
            //$yearly_amount = Transactions::whereBetween('date', array(strtotime($start_day_year), strtotime($end_day_year)))->sum('payment_amount');

            $plan_list = SubscriptionPlan::orderBy('id')->get();

            $page_title = trans('words.dashboard_text')?trans('words.dashboard_text'):'Dashboard';
                
            return view('admin.pages.dashboard',compact('page_title','users','language','genres','movies','series','sports','livetv','transactions','daily_amount','weekly_amount','monthly_amount','yearly_amount','plan_list'));
                  
        
    }
	
	 
    	
}
