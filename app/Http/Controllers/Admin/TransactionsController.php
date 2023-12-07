<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Transactions;
use App\Models\PremiumPurchase;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct();
        check_verify_purchase();
		  
    }
    public function transactions_list()    { 
        
        if(Auth::User()->usertype!="Admin")
        {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
            
         }
        
        $page_title=trans('words.transactions');
        
        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $transactions_list = Transactions::where("payment_id", "LIKE","%$keyword%")->orwhere("email", "LIKE","%$keyword%")->orderBy('id','DESC')->paginate(10);

            $transactions_list->appends(\Request::only('s'))->links();
        }
        else if(isset($_GET['gateway']))
        {
            $gateway = $_GET['gateway'];
            $transactions_list = Transactions::where("gateway", "=",$gateway)->orderBy('id','DESC')->paginate(10);

            $transactions_list->appends(\Request::only('language_id'))->links();
        }else if(isset($_GET['transactions']) && ($_GET['transactions'] == 'daily-transactions'))
        {
            $gateway = $_GET['transactions'];
            $transactions_list = Transactions::whereDate('created_at', Carbon::today())->orderBy('id','DESC')->paginate(10);

            $transactions_list->appends(\Request::only('language_id'))->links();
        }else if(isset($_GET['transactions']) && ($_GET['transactions'] == 'daily-cancellations'))
        {
            $gateway = $_GET['transactions'];
            $transactions_list = DB::table('transaction')
                ->join('users', 'transaction.user_id', '=', 'users.id')
                ->select('transaction.*')
                ->where('users.exp_date', '<', Carbon::now()->timestamp)
                ->orderBy('transaction.id','DESC')->paginate(10);

            //$transactions_list = Transactions::where("gateway", "=",$gateway)->orderBy('id','DESC')->paginate(10);
            $transactions_list->appends(\Request::only('language_id'))->links();
        }
        else
        {   
            $transactions_list = Transactions::orderBy('id','DESC')->paginate(10);
        }
        
         
        return view('admin.pages.transactions_list',compact('page_title','transactions_list'));
    } 
          
    public function transactions_export()    
    {
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

          return Excel::download(new TransactionsExport, 'transactions.xlsx');

    }	
}
