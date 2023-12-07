@extends("moderator.moderator_app")
@section("content")
<style type="text/css">
  .iframe-container {
  overflow: hidden;
  padding-top: 56.25% !important;
  position: relative;
}

.iframe-container iframe {
   border: 0;
   height: 100%;
   left: 0;
   position: absolute;
   top: 0;
   width: 100%;
}
</style>

  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">

                <div class="row">
                 <div class="col-sm-6">
                      <a href="{{ URL::to('moderator/episodes') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
                 </div>
               </div>

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(Session::has('flash_message'))
                      <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                          {{ Session::get('flash_message') }}
                      </div>
                @endif

                @if(!isset($episode_info->id))
                <div id="result" class="m-t-15"></div>
                 <input type="hidden" id="from" name="from" value="episode">
                <hr/>
                @endif


                 {!! Form::open(array('url' => array('moderator/episodes/add_edit_episode'),'class'=>'form-horizontal','name'=>'episode_form','id'=>'episode_form','role'=>'form','enctype' => 'multipart/form-data')) !!}

                  <input type="hidden" name="id" value="{{ isset($episode_info->id) ? $episode_info->id : null }}">

                  <input type="hidden" name="imdb_id" id="imdb_id" value="">
                  <input type="hidden" name="imdb_votes" id="imdb_votes" value="">

                  <div class="row">

                    <div class="col-md-6">
                      <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.episode_info')}}</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.episode_access')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="video_access">
                                <option value="Paid" @if(isset($episode_info->video_access) AND $episode_info->video_access=='Paid') selected @endif>Paid</option>
                                <option value="Free" @if(isset($episode_info->video_access) AND $episode_info->video_access=='Free') selected @endif>Free</option>
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.shows_text')}}*</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="series" id="episode_series_id">
                                <option value="">{{trans('words.select_show')}}</option>
                                @foreach($series_list as $series_data)
                                  <option value="{{$series_data->id}}" @if(isset($episode_info->id) && $series_data->id==$episode_info->episode_series_id) selected @endif>{{$series_data->series_name}}</option>
                                @endforeach
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seasons_text')}}*</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="season" id="episode_season_id">
                                <option value="">Select Season</option>
                                @if(isset($episode_info->id))
                                  @foreach($season_list as $i => $season_data)
                                      <option value="{{ $season_data->id }}" @if($season_data->id==$episode_info->episode_season_id) selected @endif>{{ $season_data->season_name }}</option>
                                  @endforeach
                                @endif
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.episode_title')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="video_title" id="episode_title" value="{{ isset($episode_info->video_title) ? stripslashes($episode_info->video_title) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="webSite" class="col-sm-12 col-form-label">{{trans('words.description')}}</label>
                    <div class="col-sm-12">
                      <div class="card-box">
                      <textarea id="elm1" name="video_description">{{ isset($episode_info->video_description) ? stripslashes($episode_info->video_description) : null }}</textarea>
                    </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="control-label col-sm-3">{{trans('words.imdb_rating')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" id="imdb_rating" name="imdb_rating" value="{{ isset($episode_info->imdb_rating) ? $episode_info->imdb_rating : old('imdb_rating') }}" class="form-control" placeholder="">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="control-label col-sm-3">{{trans('words.release_date')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" id="datepicker-autoclose" name="release_date" value="{{ isset($episode_info->release_date) ? date('m/d/Y',$episode_info->release_date) : null }}" class="form-control" placeholder="mm/dd/yyyy">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.duration')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                      <input type="text" name="duration" id="duration" value="{{ isset($episode_info->duration) ? $episode_info->duration : null }}" class="form-control" placeholder="1h 35m 54s">
                      <div class="input-group-append">
                            <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                        </div>
                    </div>
                    </div>
                  </div>

                  <hr/>
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.seo')}}</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="seo_title" id="seo_title" value="{{ isset($episode_info->seo_title) ? stripslashes($episode_info->seo_title) : old('seo_title') }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_desc')}}</label>
                    <div class="col-sm-8">
                      <textarea name="seo_description" id="seo_description" class="form-control">{{ isset($episode_info->seo_description) ? stripslashes($episode_info->seo_description) : old('seo_description') }}</textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_keyword')}}</label>
                    <div class="col-sm-8">
                      <textarea name="seo_keyword" id="seo_keyword" class="form-control">{{ isset($episode_info->seo_keyword) ? stripslashes($episode_info->seo_keyword) : old('seo_keyword') }}</textarea>
                      <small id="emailHelp" class="form-text text-muted">{{trans('words.seo_keyword_note')}}</small>
                    </div>
                  </div>
                 </div>
                 <div class="col-md-6">
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.episode_poster_video')}}</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.episode_poster')}}* <small id="emailHelp" class="form-text text-muted">({{trans('words.recommended_resolution')}} : 650x350)</small></label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" name="video_image" id="video_image" value="{{ isset($episode_info->video_image) ? $episode_info->video_image : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_movie_poster">Select</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  @if(isset($episode_info->video_image))
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">
                      <img src="{{($episode_info->video_image)}}" alt="video image" class="img-thumbnail" width="250">
                    </div>
                  </div>
                  @endif

                  <br/>
                  <hr/>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.video_upload_type')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="video_type" id="video_type">
                                <option value="Local" @if(isset($episode_info->video_type) AND $episode_info->video_type=="Local") selected @endif>Local</option>
                                <option value="URL" @if(isset($episode_info->video_type) AND $episode_info->video_type=="URL") selected @endif>URL</option>
                                <option value="Embed" @if(isset($episode_info->video_type) AND $episode_info->video_type=="Embed") selected @endif>Embed Code</option>
                                <option value="HLS" @if(isset($episode_info->video_type) AND $episode_info->video_type=="HLS") selected @endif>HLS/m3u8</option>
                                <option value="DASH" @if(isset($episode_info->video_type) AND $episode_info->video_type=="DASH") selected @endif>MPEG-DASH</option>
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                  <label class="col-sm-3 col-form-label">{{trans('words.video_quality')}}<small id="emailHelp" class="form-text text-muted">(For Local and URL)</small></label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio3" value="1" name="video_quality" @if(isset($episode_info->video_quality) && $episode_info->video_quality==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio3"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio4" value="0" name="video_quality" @if(isset($episode_info->video_quality) && $episode_info->video_quality==0) {{ 'checked' }} @endif {{ isset($episode_info->id) ? '' : 'checked' }}>
                          <label for="inlineRadio4"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row" id="local_id" @if(isset($episode_info->video_type) AND $episode_info->video_type!="Local") style="display:none;" @endif>

                    <div class="col-sm-11">
                      <small id="emailHelp" class="form-text text-muted">(Supported : MP4, MKV, MOV, WEBM)</small></label><br>
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} <small id="emailHelp" class="form-text text-muted">(Defualt Player File)</small></label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="video_url_local" id="video_url" value="{{ isset($episode_info->video_url) ? $episode_info->video_url : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_video_url">Select</button>

                        </div>
                      </div>

                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 480P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="video_url_local_480" id="video_url_local_480" value="{{ isset($episode_info->video_url_480) ? $episode_info->video_url_480 : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_video_url480">Select</button>

                        </div>
                      </div>

                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 720P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="video_url_local_720" id="video_url_local_720" value="{{ isset($episode_info->video_url_720) ? $episode_info->video_url_720 : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_video_url720">Select</button>

                        </div>
                      </div>

                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_file')}} 1080P <small id="emailHelp" class="form-text text-muted"></small></label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="video_url_local_1080" id="video_url_local_1080" value="{{ isset($episode_info->video_url_1080) ? $episode_info->video_url_1080 : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_video_url1080">Select</button>

                        </div>
                      </div>

                    </div>

                  </div>

                  <div class="form-group row" id="url_id" @if(isset($episode_info->video_type) AND $episode_info->video_type!="URL") style="display:none;" @endif @if(!isset($episode_info->id)) style="display:none;" @endif>

                    <div class="col-sm-11">
                      <small id="emailHelp" class="form-text text-muted">(Supported : MP4, MKV, MOV, WEBM)</small></label><br>
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} <small id="emailHelp" class="form-text text-muted">(Defualt Player File)</small></label>
                     <div class="col-sm-8">
                      <input type="text" name="video_url" value="{{ isset($episode_info->video_url) ? $episode_info->video_url : null }}" class="form-control" placeholder="http://example.com/demo.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 480P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8">
                      <input type="text" name="video_url_480" value="{{ isset($episode_info->video_url_480) ? $episode_info->video_url_480 : null }}" class="form-control" placeholder="http://example.com/demo480.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 720P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8">
                      <input type="text" name="video_url_720" value="{{ isset($episode_info->video_url_720) ? $episode_info->video_url_720 : null }}" class="form-control" placeholder="http://example.com/demo720.mp4">
                    </div>

                    <label class="col-sm-3 col-form-label">{{trans('words.video_url')}} 1080P<small id="emailHelp" class="form-text text-muted"></small></label>
                     <div class="col-sm-8">
                      <input type="text" name="video_url_1080" value="{{ isset($episode_info->video_url_1080) ? $episode_info->video_url_1080 : null }}" class="form-control" placeholder="http://example.com/demo1080.mp4">
                    </div>

                  </div>

                  <div class="form-group row" id="embed_id" @if(isset($episode_info->video_type) AND $episode_info->video_type!="Embed") style="display:none;" @endif @if(!isset($episode_info->id)) style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.video_embed_code')}}</label>
                     <div class="col-sm-8">
                       <textarea class="form-control" name="video_embed_code">{{ isset($episode_info->video_url) ? $episode_info->video_url : null }}</textarea>
                    </div>
                  </div>

                  <div class="form-group row" id="hls_id" @if(isset($episode_info->video_type) AND $episode_info->video_type!="HLS") style="display:none;" @endif @if(!isset($episode_info->id)) style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.hls_streaming')}}</label>
                     <div class="col-sm-8">
                       <input type="text" name="video_url_hls" value="{{ isset($episode_info->video_url) ? $episode_info->video_url : null }}" class="form-control" placeholder="http://example.com/test.m3u8">
                    </div>
                  </div>
                  <div class="form-group row" id="dash_id" @if(isset($episode_info->video_type) AND  $episode_info->video_type!="DASH") style="display:none;" @endif @if(!isset($episode_info->id)) style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.mpeg_dash_streaming')}}</label>
                     <div class="col-sm-8">
                       <input type="text" name="video_url_dash" value="{{ isset($episode_info->video_url) ? $episode_info->video_url : null }}" class="form-control" placeholder="http://example.com/test.mpd">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.download')}}</label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio1" value="1" name="download_enable" @if(isset($episode_info->download_enable) && $episode_info->download_enable==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio1"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio2" value="0" name="download_enable" @if(isset($episode_info->download_enable) && $episode_info->download_enable==0) {{ 'checked' }} @endif @if(!isset($episode_info->id)) {{ 'checked' }} @endif>
                          <label for="inlineRadio2"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.download_url')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="download_url" id="download_url" value="{{ isset($episode_info->download_url) ? $episode_info->download_url : old('download_url') }}" class="form-control">
                    </div>
                  </div>

                  <br/><hr/>
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.subtitles')}}</h4>
                  <div class="col-sm-9">
                    <small id="emailHelp" class="form-text text-muted">(Supported : vtt file URL only. You Can Convert Subtitles to Vtt <a href="https://subtitletools.com/convert-to-vtt-online" target="_blank">Here</a>.)</small>
                  </div> <br/>

                  <div class="form-group row">
                  <label class="col-sm-3 col-form-label">{{trans('words.subtitles')}}</label>
                    <div class="col-sm-8">
                      <div class="radio radio-success form-check-inline"  style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio5" value="1" name="subtitle_on_off" @if(isset($episode_info->subtitle_on_off) && $episode_info->subtitle_on_off==1) {{ 'checked' }} @endif>
                          <label for="inlineRadio5"> {{trans('words.active')}} </label>
                      </div>
                      <div class="radio form-check-inline" style="margin-top: 8px;">
                          <input type="radio" id="inlineRadio6" value="0" name="subtitle_on_off" @if(isset($episode_info->subtitle_on_off) && $episode_info->subtitle_on_off==0) {{ 'checked' }} @endif {{ isset($episode_info->id) ? '' : 'checked' }}>
                          <label for="inlineRadio6"> {{trans('words.inactive')}} </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language1')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language1" id="subtitle_language1" value="{{ isset($episode_info->subtitle_language1) ? $episode_info->subtitle_language1 : old('subtitle_language') }}" placeholder="English" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url1')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url1" id="subtitle_url1" value="{{ isset($episode_info->subtitle_url1) ? $episode_info->subtitle_url1 : old('subtitle_url1') }}" class="form-control" placeholder="http://example.com/demo.vtt">

                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language2')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language2" id="subtitle_language2" value="{{ isset($episode_info->subtitle_language2) ? $episode_info->subtitle_language2 : old('subtitle_language2') }}" placeholder="French" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url2')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url2" id="subtitle_url2" value="{{ isset($episode_info->subtitle_url2) ? $episode_info->subtitle_url2 : old('subtitle_url2') }}" class="form-control" placeholder="http://example.com/demo.vtt">

                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_language3')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_language3" id="subtitle_language3" value="{{ isset($episode_info->subtitle_language3) ? $episode_info->subtitle_language3 : old('subtitle_language3') }}" placeholder="Spanish" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.subtitle_url3')}}
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </label>
                    <div class="col-sm-8">
                      <input type="text" name="subtitle_url3" id="subtitle_url3" value="{{ isset($episode_info->subtitle_url3) ? $episode_info->subtitle_url3 : old('subtitle_url3') }}" class="form-control" placeholder="http://example.com/demo.vtt">

                    </div>
                  </div>


                  <div class="form-group">
                  <div class="offset-sm-9 col-sm-9">
                    <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> {{trans('words.save')}} </button>
                  </div>
                </div>
                 </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("moderator.copyright")
    </div>
<!--  Movie Video file -->
<div id="model_video_url" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=video_url&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>

<div id="model_video_url480" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=video_url_local_480&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>

<div id="model_video_url720" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=video_url_local_720&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>


<div id="model_video_url1080" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=video_url_local_1080&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>

<!--  Movie Poster -->
<div id="model_movie_poster" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=video_image&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
