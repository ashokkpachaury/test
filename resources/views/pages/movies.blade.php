@extends('site_app')

@section('head_title', trans('words.movies_text').' | '.getcong('site_name') )

@section('head_url', Request::url())

@section('content')


<div class="page-header">
  <div class="vfx_page_header_overlay">
    <div class="container">
      <div class="vfx_breadcrumb">
		  <ul>
			<li><a href="{{ URL::to('/') }}">{{trans('words.home')}}</a></li>
			<li>{{trans('words.movies_text')}}</li>
		  </ul>
	  </div>
    </div>
  </div>
</div>
<div class="container">
    <div class="row">
	<div class="custom_select_filter">
	  <div class="custom-select">
		<select id="filter_list" class="selectpicker show-tick form-control is-invalid form-control-lg" required>
		  <option value="?filter=new" @if(isset($_GET['filter']) && $_GET['filter']=='new' ) selected @endif>{{trans('words.newest')}}</option>
		  <option value="?filter=old" @if(isset($_GET['filter']) && $_GET['filter']=='old' ) selected @endif>{{trans('words.oldest')}}</option>
		  <option value="?filter=alpha" @if(isset($_GET['filter']) && $_GET['filter']=='alpha' ) selected @endif>{{trans('words.a_to_z')}}</option>
		  <option value="?filter=rand" @if(isset($_GET['filter']) && $_GET['filter']=='rand' ) selected @endif>{{trans('words.random')}}</option>
		</select>
	  </div>
	</div>
  </div>
</div>

@if(get_ads('movie_list_ad_top')->status!=0)
        <div class="add_banner_section">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                {!!get_ads('movie_list_ad_top')->ad_code!!}
              </div>
            </div>
          </div>
        </div>
        @endif

 <div class="main-wrap">
  <div class="section section-padding tv_show vfx_video_list_section text-white">
    <div class="container">
      <div class="row">

        <div class="show-listing vfx_movie_list_item">

      @foreach($movies_list as $movies_data)
      @if(Auth::check())
          <a class="icon" href="{{ URL::to('movies/'.$movies_data->video_slug.'/'.$movies_data->id) }}">
      @else
         @if($movies_data->video_access=='Paid')
          <a class="icon" href="Javascript::void();" data-toggle="modal" data-target="#loginAlertModal">
         @else
          <a class="icon" href="{{ URL::to('movies/'.$movies_data->video_slug.'/'.$movies_data->id) }}">
         @endif
      @endif
      <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="vfx_video_item">
              <div class="thumb-wrap"> <img src="{{($movies_data->video_image_thumb)}}" alt="{{$movies_data->video_title}}">
                @if($movies_data->video_access=='Paid')<span class="premium_video"><i class="fa fa-lock"></i>Premium</span>@endif

                <div class="thumb-hover">

          <i class="icon fa fa-play"></i><span class="ripple"></span>

          </div>
              </div>
              <div class="vfx_video_detail">
                <h4 class="vfx_video_title"><a href="{{ URL::to('movies/'.$movies_data->video_slug.'/'.$movies_data->id) }}">{{Str::limit(stripslashes($movies_data->video_title),12)}}</a></h4>
                <p class="vfx_video_length"><i class="fa fa-clock-o"></i> {{$movies_data->duration}}</p>
               </div>
            </div>
      </div>
      </a>


      @endforeach


              @include('_particles.pagination', ['paginator' => $movies_list])


        </div>
      </div>
    </div>
  </div>
</div>

    @if(get_ads('movie_list_ad_bottom')->status!=0)
        <div class="add_banner_section">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                {!!get_ads('movie_list_ad_bottom')->ad_code!!}
              </div>
            </div>
          </div>
        </div>
        @endif

@endsection
