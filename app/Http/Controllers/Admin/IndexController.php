<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User; 
use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;

class IndexController extends MainAdminController
{
	
    public function index()
    {   
    	if (Auth::check()) {
                        
            return redirect('admin/dashboard'); 
        }
 
        return view('admin.index');
    }
	
	/**
     * Do user login
     * @return $this|\Illuminate\Http\RedirectResponse
     */
	 
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials, $request->has('remember'))) {
            if(Auth::user()->status=='0'){
                \Auth::logout();
                return redirect('/admin')->withErrors(trans('words.account_banned'));
            }
            return $this->handleUserWasAuthenticated($request);
        }
        return redirect('/admin')->withErrors(trans('words.email_password_invalid'));
    }
    
     /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request)
    {
        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }
        /*$previous_session = Auth::User()->session_id;
        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }
        Auth::user()->session_id = Session::getId();
        Auth::user()->save();
        */
        return redirect('admin/dashboard'); 
    }
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect('admin/');
    }
    	
}
