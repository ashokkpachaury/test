<?php

namespace App\Http\Controllers\Moderator;

use Auth;
use App\User;
use App\Series;
use App\Season;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeasonController extends MainModeratorController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
        check_verify_purchase();
		  
    }
    public function season_list()    { 
        
        if(Auth::User()->usertype!="Moderator")
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('dashboard');
        }

        $moderatorId = Auth::User()->id;  

        $page_title=trans('words.seasons_text');

        $series_list = Series::orderBy('series_name')->get();  
        
        if(isset($_GET['s']))
        {
            $keyword = $_GET['s'];  
            $season_list = DB::table('season')
                           ->join('series', 'season.series_id', '=', 'series.id')
                           ->select('season.*', 'series.series_name')
                           ->where("season.moderator_id",$moderatorId)
                           ->where("season_name", "LIKE","%$keyword%")
                           ->orderBy('season_name')
                           ->paginate(10);

            $season_list->appends(\Request::only('s'))->links();
        }    
        else if(isset($_GET['series_id']))
        {
            $series_id = $_GET['series_id'];
            $season_list = DB::table('season')
                           ->join('series', 'season.series_id', '=', 'series.id')
                           ->select('season.*', 'series.series_name')
                           ->where("season.moderator_id",$moderatorId)
                           ->where("series_id", "=",$series_id)
                           ->orderBy('id','DESC')
                           ->paginate(10);

            $season_list->appends(\Request::only('series_id'))->links();
        }
        else
        {
            $season_list = DB::table('season')
                           ->join('series', 'season.series_id', '=', 'series.id')
                           ->select('season.*', 'series.series_name')
                           ->where("season.moderator_id",$moderatorId)
                           ->orderBy('id','DESC')
                           ->paginate(10);              
        }
         
        return view('moderator.pages.season_list',compact('page_title','season_list','series_list'));
    }
    
    public function addSeason()    { 
        
        if(Auth::User()->usertype!="Moderator")
        {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
            
         }

        $page_title=trans('words.add_season');

        $series_list = Series::orderBy('series_name')->get();  

        return view('moderator.pages.addeditseason',compact('page_title','series_list'));
    }
    
    public function addnew(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        if(!empty($inputs['id'])){
                
                $rule=array(
                'series' => 'required',
                'season_name' => 'required'
                  );
        }else
        {
            $rule=array(
                'series' => 'required',
                'season_name' => 'required',                 
                'season_poster' => 'required'                
                 );
        }

        
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 

        if(Auth::User()->usertype!="Moderator")
        {
            $moderatorId = '0';
        }else{
            $moderatorId = Auth::User()->id;
        }

        $inputs = $request->all();
        
        if(!empty($inputs['id'])){
           
            $season_obj = Season::findOrFail($inputs['id']);

        }else{

            $season_obj = new Season;

        }

         $season_slug = Str::slug($inputs['season_name'], '-');

         $season_obj->series_id = $inputs['series'];
         $season_obj->season_name = addslashes($inputs['season_name']);
         $season_obj->season_slug = $season_slug;
         $season_obj->season_poster = $inputs['season_poster']; 
        
         $season_obj->status = '0';//$inputs['status']; 

         $season_obj->seo_title = addslashes($inputs['seo_title']);  
         $season_obj->seo_description = addslashes($inputs['seo_description']);  
         $season_obj->seo_keyword = addslashes($inputs['seo_keyword']); 
          
         $season_obj->moderator_id = $moderatorId;
         $season_obj->save();
         
        
        if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.added'));

            return \Redirect::back();

        }            
        
         
    }     
   
    
    public function editSeason($season_id)    
    {      
          if(Auth::User()->usertype!="Moderator")
        {

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('dashboard');
            
         }  

          $page_title=trans('words.edit_season');

          $season_info = Season::findOrFail($season_id);

          $series_list = Series::orderBy('series_name')->get();    

          return view('moderator.pages.addeditseason',compact('page_title','season_info','series_list'));
        
    }	 
    
    public function delete($season_id)
    {
    	if(Auth::User()->usertype!="Moderator")
        {
        	
            $season_obj = Season::findOrFail($season_id);
            $season_obj->delete();

            \Session::flash('flash_message', trans('words.deleted'));
            return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));
            return redirect('moderator/dashboard');            
        
        }
    }

     
     
    	
}
