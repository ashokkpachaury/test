@extends('site_app')

@if($episode_info->seo_title)
  @section('head_title', stripslashes($episode_info->seo_title).' | '.getcong('site_name'))
@else
  @section('head_title', $series_name.' '.$season_name.' '.stripslashes($episode_info->video_title).' | '.getcong('site_name'))
@endif

@if($episode_info->seo_description)
  @section('head_description', stripslashes($episode_info->seo_description))
@else
  @section('head_description', Str::limit(stripslashes($episode_info->video_description),160))
@endif

@if($episode_info->seo_keyword)
  @section('head_keywords', stripslashes($episode_info->seo_keyword))
@endif

@section('head_image', ($episode_info->video_image))

@section('head_url', Request::url())

@section('content')

 <script src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js?loadCastFramework=1"></script>

 @if(get_player_cong('player_style')!="")
 	<link href="{{ URL::asset('site_assets/videojs_player/css/'.get_player_cong('player_style').'.min.css') }}" rel="stylesheet" type="text/css" />
 @else
	<link href="{{ URL::asset('site_assets/videojs_player/css/videojs_style1.min.css') }}" rel="stylesheet" type="text/css" />
 @endif


 <style type="text/css">

  .videoWrapper {
  position: relative;
  padding-bottom: 56.25%; /* 16:9 */
  padding-top: 25px;
  height: 0;
}
.videoWrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.vjs-pip-control
{
	@if(get_player_cong('pip_mode')=="ON")
	display: block!important;
	@else
	display: none!important;
	@endif
}

 </style>

<div class="main-wrap">
  <div class="section section-padding vfx_video_single_section">
    <div class="container">
      <div class="video-single">
        <div class="row">
          <div class="col-xs-12">
            <div class="content-wrap">
				<div class="vfx_video_detail xs-top-40">
					<div class="row">
					<div class="single-section col-md-8 col-sm-12 col-xs-12" id="left_video_player">
					  <main>

						@if($episode_info->video_type=="Embed")

						  <div class="videoWrapper">{!! $episode_info->video_url!!}</div>

						@elseif($episode_info->video_type=="HLS")

							<div id="container">
							<video id="v_player" class="video-js vjs-big-play-centered skin-blue vjs-16-9" controls preload="auto" playsinline crossorigin="anonymous" width="640" height="450" poster="{{($episode_info->video_image)}}" data-setup="{}" @if(get_player_cong('autoplay')=="true")autoplay="true"@endif>

							  	<!-- video source -->
							  	<source src="{{$episode_info->video_url}}" type="application/x-mpegURL" />
  								@if($episode_info->subtitle_on_off)
	  								<!-- video subtitle -->
									@if($episode_info->subtitle_url1)
										<track kind="captions" src="{{$episode_info->subtitle_url1}}"   label="{{$episode_info->subtitle_language1?$episode_info->subtitle_language1:'English'}}" default>
									@endif
									@if($episode_info->subtitle_url2)
										<track kind="captions" src="{{$episode_info->subtitle_url2}}"   label="{{$episode_info->subtitle_language2?$episode_info->subtitle_language2:'English'}}">
									@endif
									@if($episode_info->subtitle_url3)
										<track kind="captions" src="{{$episode_info->subtitle_url3}}"    label="{{$episode_info->subtitle_language3?$episode_info->subtitle_language3:'English'}}">
									@endif
								@endif
								<!-- worning text if needed -->
								<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
							</video>
						</div>

						@elseif($episode_info->video_type=="DASH")

						<div id="container">
							<video id="v_player" class="video-js vjs-big-play-centered skin-blue vjs-16-9" controls preload="auto" playsinline crossorigin="anonymous" width="640" height="450" poster="{{($episode_info->video_image)}}" data-setup="{}" @if(get_player_cong('autoplay')=="true")autoplay="true"@endif>

							  	<!-- video source -->
							  	<source src="{{$episode_info->video_url}}" type="application/dash+xml" />
  								@if($episode_info->subtitle_on_off)
  								<!-- video subtitle -->
								@if($episode_info->subtitle_url1)
									<track kind="captions" src="{{$episode_info->subtitle_url1}}"   label="{{$episode_info->subtitle_language1?$episode_info->subtitle_language1:'English'}}" default>
								@endif
								@if($episode_info->subtitle_url2)
									<track kind="captions" src="{{$episode_info->subtitle_url2}}"   label="{{$episode_info->subtitle_language2?$episode_info->subtitle_language2:''}}">
								@endif
								@if($episode_info->subtitle_url3)
									<track kind="captions" src="{{$episode_info->subtitle_url3}}"    label="{{$episode_info->subtitle_language3?$episode_info->subtitle_language3:''}}">
								@endif
								@endif
								<!-- worning text if needed -->
								<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
							</video>
						</div>

						@elseif($episode_info->video_type=="URL")
						  <div id="container">
							<video id="v_player" class="video-js vjs-big-play-centered skin-blue vjs-16-9" controls preload="auto" playsinline width="640" height="450" poster="{{($episode_info->video_image)}}" data-setup="{}" @if(get_player_cong('autoplay')=="true")autoplay="true"@endif>

							  	<!-- video source -->
							  	<source src="{{$episode_info->video_url}}" type="video/mp4" label='Auto' res='360' default/>
							  	@if($episode_info->video_quality)
							  	@if($episode_info->video_url_480)
							   	<source src="{{$episode_info->video_url_480}}" type='video/mp4' label='480P' res='480'/>
							   	@endif

							   	@if($episode_info->video_url_720)
							    <source src="{{$episode_info->video_url_720}}" type='video/mp4' label='720P' res='720'/>
							    @endif

							    @if($episode_info->video_url_1080)
							    <source src="{{$episode_info->video_url_1080}}" type='video/mp4' label='1080P' res='1080'/>
							    @endif
							    @endif

								@if($episode_info->subtitle_on_off)
							    <!-- video subtitle -->
								@if($episode_info->subtitle_url1)
									<track kind="captions" src="{{$episode_info->subtitle_url1}}"   label="{{$episode_info->subtitle_language1?$episode_info->subtitle_language1:'English'}}" default>
								@endif
								@if($episode_info->subtitle_url2)
									<track kind="captions" src="{{$episode_info->subtitle_url2}}"   label="{{$episode_info->subtitle_language2?$episode_info->subtitle_language2:'English'}}">
								@endif
								@if($episode_info->subtitle_url3)
									<track kind="captions" src="{{$episode_info->subtitle_url3}}"    label="{{$episode_info->subtitle_language3?$episode_info->subtitle_language3:'English'}}">
								@endif
								@endif
								<!-- worning text if needed -->
								<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
							</video>

						</div>
						@else
						<div id="container">
						  <video id="v_player" class="video-js vjs-big-play-centered skin-blue vjs-16-9" controls preload="auto" playsinline width="640" height="450" poster="{{($episode_info->video_image)}}" data-setup="{}" @if(get_player_cong('autoplay')=="true")autoplay="true"@endif>

							<!-- video source -->
							<source src="{{URL::to('upload/source/'.$episode_info->video_url)}}" type="video/mp4" label='Auto' res='360' default/>
							@if($episode_info->video_quality)
								@if($episode_info->video_url_480)
								<source src="{{URL::to('upload/source/'.$episode_info->video_url_480)}}" type='video/mp4' label='480P' res='480'/>
								@endif

								@if($episode_info->video_url_720)
							    <source src="{{URL::to('upload/source/'.$episode_info->video_url_720)}}" type='video/mp4' label='720P' res='720'/>
							    @endif

							    @if($episode_info->video_url_1080)
							    <source src="{{URL::to('upload/source/'.$episode_info->video_url_1080)}}" type='video/mp4' label='1080P' res='1080'/>
								@endif
							@endif

							 @if($episode_info->subtitle_on_off)
							<!-- video subtitle -->
								@if($episode_info->subtitle_url1)
									<track kind="captions" src="{{$episode_info->subtitle_url1}}"  label="{{$episode_info->subtitle_language1?$episode_info->subtitle_language1:'English'}}" default>
								@endif
								@if($episode_info->subtitle_url2)
									<track kind="captions" src="{{$episode_info->subtitle_url2}}" label="{{$episode_info->subtitle_language2?$episode_info->subtitle_language2:'English'}}">
								@endif
								@if($episode_info->subtitle_url3)
									<track kind="captions" src="{{$episode_info->subtitle_url3}}"  label="{{$episode_info->subtitle_language3?$episode_info->subtitle_language3:'English'}}">
								@endif
							@endif
								<!-- worning text if needed -->
								<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
							</video>
						</div>
						@endif

					  </main>

					  <div id="theater_mode_width">

					  <h3 class="vfx_video_title">{{stripslashes($series_name)}}</h3>
					  <!--<p class="video-release-date"><i class="fa fa-calendar"></i> 12 Jan 2019</p>-->
					  <ul class="channel_content_info">
					  <li style="font-size: 16px;">{{stripslashes($episode_info->video_title)}}</li>
					  <li style="font-size: 16px;">{{App\Season::getSeasonInfo($episode_info->episode_season_id,'season_name')}}</li>
					  @if($episode_info->release_date)
						<li><i class="fa fa-calendar"></i> {{ isset($episode_info->release_date) ? date('M d Y',$episode_info->release_date) : null }}</li>
						@endif
					   @if($episode_info->duration)
					   <li><i class="fa fa-clock-o"></i> {{$episode_info->duration}}</li>
					   @endif
						 @if($episode_info->imdb_rating)
						 <li><img src="{{URL::to('site_assets/images/icons/imdb-logo.png')}}" alt="IMDb Rating"> &nbsp;<b>{{$episode_info->imdb_rating}}</b></li>
						 @endif

						 @if($episode_info->download_enable)
						 <li>
							<div class="video_download_btn">
						   <a href="{{$episode_info->download_url}}" target="_blank"><i class="fa fa-download"></i> {{trans('words.download')}}</a>

						  </div>
						 </li>
						 @endif
					  </ul>

					  <div class="video-attributes dtl_video">

					   <div class="single-footer">
							<div class="row">
								<div class="col-md-5 col-xs-12">
									<div class="news-share">
										<label>{{trans('words.share_text')}}: </label>
										<div class="share-social">
											<a href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" target="_blank"><i class="fa fa-facebook"></i></a>
											<a href="https://twitter.com/intent/tweet?text={{$episode_info->video_title}}&amp;url={{url()->current()}}"><i class="fa fa-twitter"></i></a>
											<a href="http://pinterest.com/pin/create/button/?url={{url()->current()}}&media={{($episode_info->video_image)}}&description={{$episode_info->video_title}}"><i class="fa fa-pinterest"></i></a>
											<a href="whatsapp://send?text={{url()->current()}}" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i></a>

										</div>
									</div>
								</div>
								<div class="col-md-7 col-xs-12">
									<div class="news-tag">
										 @foreach(explode(',',$series_genres_ids) as $genres_ids)
										<p><b><a href="{{ URL::to('genres/series/'.App\Genres::getGenresInfo($genres_ids,'genre_slug')) }}">{{App\Genres::getGenresInfo($genres_ids,'genre_name')}}</a></b></p>
										@endforeach
										<p><a href="{{ URL::to('language/series/'.App\Language::getLanguageInfo($series_lang_id,'language_slug')) }}"><b>{{App\Language::getLanguageInfo($series_lang_id,'language_name')}}</b></a></p>
									</div>
								</div>
							</div>
						</div>
					  </div>

						<div class="clearfix"></div>
						@if(get_ads('episode_single_ad_top')->status!=0)
						<div class="add_banner_section">
						  <div class="row">
							<div class="col-md-12">
							  {!!get_ads('episode_single_ad_top')->ad_code!!}
							</div>
						  </div>
						</div>
						@endif

					  <div class="single-section video-entry mr-top-20" id="episodes_all">
						  <h3 class="single-vfx_section_title">{{trans('words.description')}}</h3>
						  <div class="section-content">
							@if(strlen($episode_info->video_description) > 500)
							<div class="listing-section">
							  <div class="show-more">
							  <div class="pricing-list-container">
								 {!!stripslashes($episode_info->video_description)!!}
							  </div>
							  </div>
							  <a href="#" class="show-more-button" data-more-title="Show More" data-less-title="Show Less"><i class="fa fa-angle-down"></i></a>
							</div>
							@else
							{!!stripslashes($episode_info->video_description)!!}
							@endif
						  </div>
						</div>

					 </div>

					</div>
					<div class="col-md-4 col-sm-12 col-xs-12" id="right_sidebar_hide">
					  <div class="sidebar_playlist series_sidebar_list">
						<div class="caption">
						   <div class="left"> <a href="#"><i class="fa fa-arrow-down"></i> {{trans('words.up_next')}}</a> </div>
						   <div class="right"> <a href="#episodes_all">{{trans('words.view_all')}}</a> </div>
						  <div class="clearfix"></div>
						</div>
						@foreach($episode_up_next_list as $episode_up_next_data)
						<div class="playlist_item">
						  <a href="{{ URL::to('series/'.$series_slug.'/'.$episode_up_next_data->video_slug.'/'.$episode_up_next_data->id) }}">
							<div class="sidebar_movie_item">
								<img src="{{URL::to('upload/source/'.$episode_up_next_data->video_image)}}" alt="show" />
							</div>
							<div class="playlist_content">
							  <h3>{{Str::limit(stripslashes($episode_up_next_data->video_title),25)}}</h3>
                <p class="vfx_video_length">{!!Str::limit(stripslashes(strip_tags($episode_up_next_data->video_description)),50)!!}
                </p>
							 </div>
						  </a>
						</div>
						@endforeach

					  </div>

            @if(get_ads('episode_single_ad_sidebar')->status!=0)
            <div class="add_banner_section">
              <div class="row">
                <div class="col-md-12">
                  {!!get_ads('episode_single_ad_sidebar')->ad_code!!}
                </div>
              </div>
            </div>
            @endif

					</div>
				  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section section-padding top-padding-normal vfx_movie_section_block">

    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <div class="vfx_section_header">
            <h2 class="vfx_section_title">{{trans('words.episodes_text')}}</h2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="owl-carousel video-carousel" id="video-carousel">

          @foreach($episode_list as $episode_data)

          @if(Auth::check())
              <a href="{{ URL::to('series/'.$series_slug.'/'.$episode_data->video_slug.'/'.$episode_data->id) }}">
          @else
             @if($episode_data->video_access=='Paid')
              <a class="icon" href="Javascript::void();" data-toggle="modal" data-target="#loginAlertModal">
             @else
              <a href="{{ URL::to('series/'.$series_slug.'/'.$episode_data->video_slug.'/'.$episode_data->id) }}">
             @endif
          @endif
          <div class="vfx_video_item">
            <div class="vfx_upcomming_item_block">
              <img src="{{URL::to('upload/source/'.$episode_data->video_image)}}" alt="{{$episode_data->video_title}}">
              @if($episode_data->video_access=='Paid')<span class="premium_video"><i class="fa fa-lock"></i>Premium</span>@endif
            </div>
            <div class="vfx_video_detail">
              <h4 class="vfx_video_title">{{Str::limit(stripslashes($episode_data->video_title),10)}}</h4>
             </div>
          </div>
          </a>
          @endforeach

        </div>
      </div>
    </div>
  </div>

  <div class="section section-padding vfx_movie_section_block">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <div class="vfx_section_header">
            <h2 class="vfx_section_title">{{trans('words.seasons_text')}}</h2>
          </div>
        </div>
      </div>
      <div class="row">

        <div class="owl-carousel video-carousel vfx_tvshow_carousel" id="vfx_tvshow_carousel">
        @foreach($season_list as $season_data)
            <a href="{{ URL::to('series/'.$series_slug.'/seasons/'.$season_data->season_slug.'/'.$season_data->id) }}">
             <div class="vfx_video_item">
                <div class="vfx_upcomming_item_block">
					<img class="img-responsive" src="{{($season_data->season_poster)}}" alt="{{$season_data->season_name}}">
				</div>
                <div class="vfx_video_detail">
                  <h4 class="vfx_video_title">{{Str::limit(stripslashes($season_data->season_name),25)}}</h4>
                </div>
             </div>
            </a>
        @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@if(get_ads('episode_single_ad_bottom')->status!=0)
<div class="add_banner_section">
  <div class="row">
    <div class="col-md-12">
      {!!get_ads('episode_single_ad_bottom')->ad_code!!}
    </div>
  </div>
</div>
@endif

@if($episode_info->video_type!="Embed")

<script src="{{ URL::asset('site_assets/videojs_player/js/videojs.min.js') }}"></script>

<script src="{{ URL::asset('site_assets/videojs_player/plugins/videojs.pip.js') }}"></script>

<script src="{{ URL::asset('site_assets/videojs_player/plugins/videojs-chromecast.min.js') }}"></script>

<script>
        var player = videojs('v_player',{techOrder:['chromecast','html5']});

        player.viavi({
            shareMenu: false,

            @if(get_player_cong('player_watermark')=="ON")
            logo: "{{ get_player_cong('player_logo')? URL::asset('upload/source/'.get_player_cong('player_logo')) : URL::asset('upload/source/'.getcong('site_logo')) }}",
            logoposition: '{{get_player_cong('player_logo_position')}}',
            logourl: '{{ get_player_cong('player_url')?get_player_cong('player_url'):URL::to('/') }}',
            @endif

            video_id: 'episode{{$episode_info->id}}',
            resume: true,
            contextMenu: false,
            @if(get_player_cong('rewind_forward')=="ON")
            buttonRewind: true,
            buttonForward: true,
        	@else
        	buttonRewind: false,
        	buttonForward: false,
        	@endif
            mousedisplay:true,
            textTrackSettings: false,
            @if(get_player_cong('theater_mode')=="ON")
            theaterButton: true
        	@else
        	theaterButton: false
        	@endif

        });

        player.pip();

        player.chromecast({ metatitle: '{{stripslashes($episode_info->video_title)}}', metasubtitle: 'Release : {{ isset($episode_info->release_date) ? date('M d Y',$episode_info->release_date) : null }}' });


        @if(get_player_cong('player_ad_on_off')=="ON")
        player.vroll({src:"{{get_player_cong('ad_video_url')}}",type:"video/mp4",href:"{{get_player_cong('ad_web_url')}}",offset:"{{get_player_cong('ad_offset')}}",skip:"{{get_player_cong('ad_skip')}}",id:1});
        @endif

         player.on('mode',function(event,mode) {
			if(mode=='large'){
				document.querySelector("#left_video_player").style.width='100%';
				document.querySelector("#right_sidebar_hide").style.display='none';
				document.querySelector("#theater_mode_width").style.width='66%';

			}else{
				document.querySelector("#left_video_player").style.width='';
				document.querySelector("#right_sidebar_hide").style.display='block';
				document.querySelector("#theater_mode_width").style.width='100%';
			}
		});

        /*limit: 20, //Video will stop playing after 20 seconds
            limiturl: "#",
            limitimage: "http://localhost/laravel_video_script_update/upload/source/logo.png"*/
    </script>



    <!-- hotkeys -->
    <script src="{{ URL::asset('site_assets/videojs_player/plugins/hotkeys/videojs.hotkeys.min.js') }}"></script>
    <script>
      player.ready(function() {
        this.hotkeys({
            volumeStep: 0.1,
			seekStep: 5,
			alwaysCaptureHotkeys: true
        });
      });
    </script>
    <!-- End hotkeys -->
 @endif

@endsection
