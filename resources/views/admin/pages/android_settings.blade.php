@extends("admin.admin_app")

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


                 {!! Form::open(array('url' => array('admin/android_settings'),'class'=>'form-horizontal','name'=>'settings_form','id'=>'settings_form','role'=>'form','enctype' => 'multipart/form-data')) !!}

                  <input type="hidden" name="id" value="{{ isset($settings->id) ? $settings->id : null }}">

                <div class="row">

                 <div class="col-md-12">

                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.android_app_settings')}}</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_name')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_name" value="{{ isset($settings->app_name) ? stripslashes($settings->app_name) : null }}" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_logo')}}*</label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="app_logo" id="app_logo" value="{{ isset($settings->app_logo) ? $settings->app_logo : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_poster">Select</button>

                        </div>
                      </div>

                    </div>
                  </div>

                  @if(isset($settings->app_logo))
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">

                           <img src="{{URL::to('upload/source/'.$settings->app_logo)}}" alt="video image" class="img-thumbnail" width="250">

                    </div>
                  </div>
                  @endif


                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_email')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_email" value="{{ isset($settings->app_email) ? $settings->app_email : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_company')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_company" value="{{ isset($settings->app_company) ? $settings->app_company : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_website')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_website" value="{{ isset($settings->app_website) ? $settings->app_website : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_contact')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_contact" value="{{ isset($settings->app_contact) ? $settings->app_contact : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_app_version')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="app_version" value="{{ isset($settings->app_version) ? $settings->app_version : null }}" class="form-control">
                    </div>
                  </div>

                  <hr>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Facebook URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_fb_link" value="{{ isset($settings1->footer_fb_link) ? stripslashes($settings1->footer_fb_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Twitter URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_twitter_link" value="{{ isset($settings1->footer_twitter_link) ? stripslashes($settings1->footer_twitter_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Instagram URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_instagram_link" value="{{ isset($settings1->footer_instagram_link) ? stripslashes($settings1->footer_instagram_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Google Play URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_google_play_link" value="{{ isset($settings1->footer_google_play_link) ? stripslashes($settings1->footer_google_play_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Apple Store URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_apple_store_link" value="{{ isset($settings1->footer_apple_store_link) ? stripslashes($settings1->footer_apple_store_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <hr>
                </div>
                <div class="col-md-12">
                  <!-- <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.android_oneSignal_settings')}}</h4>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_oneSignal_app_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="onesignal_app_id" value="{{ isset($settings->onesignal_app_id) ? $settings->onesignal_app_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_oneSignal_rest_key')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="onesignal_rest_key" value="{{ isset($settings->onesignal_rest_key) ? $settings->onesignal_rest_key : null }}" class="form-control">
                    </div>
                  </div>
                  <hr/> -->


                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.android_admob_settings')}}</h4>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_publisher_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="publisher_id" value="{{ isset($settings->publisher_id) ? $settings->publisher_id : null }}" class="form-control">
                    </div>
                  </div>
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 18px;">{{trans('words.android_banner_ads')}}</h4>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Banner Ad Type</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="banner_ad_type" id="banner_ad_type">
                                <option value="Admob" @if(isset($settings->banner_ad_type) AND $settings->banner_ad_type=='Admob') selected @endif>Admob</option>
                                <option value="Facebook" @if(isset($settings->banner_ad_type) AND $settings->banner_ad_type=='Facebook') selected @endif>Facebook</option>
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_banner_ad')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="banner_ad">
                                <option value="true" @if(isset($settings->banner_ad) AND $settings->banner_ad=='true') selected @endif>True</option>
                                <option value="false" @if(isset($settings->banner_ad) AND $settings->banner_ad=='false') selected @endif>False</option>
                            </select>
                      </div>
                  </div>
                  <div class="form-group row" id="admob_banner_id" @if($settings->banner_ad_type!="Admob") style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.android_banner_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="banner_ad_id" value="{{ isset($settings->banner_ad_id) ? $settings->banner_ad_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row" id="fb_banner_id" @if($settings->banner_ad_type!="Facebook") style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.android_banner_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="fb_banner_id" value="{{ isset($settings->fb_banner_id) ? $settings->fb_banner_id : null }}" class="form-control">
                    </div>
                  </div>
                  <h4 class="m-t-0 m-b-30 header-title" style="font-size: 18px;">{{trans('words.android_interstitial_ads')}}</h4>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Interstitial Ad Type</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="interstitial_ad_type" id="interstitial_ad_type">
                                <option value="Admob" @if(isset($settings->interstitial_ad_type) AND $settings->interstitial_ad_type=='Admob') selected @endif>Admob</option>
                                <option value="Facebook" @if(isset($settings->interstitial_ad_type) AND $settings->interstitial_ad_type=='Facebook') selected @endif>Facebook</option>
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_interstitial')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="interstital_ad">
                                <option value="true" @if(isset($settings->interstital_ad) AND $settings->interstital_ad=='true') selected @endif>True</option>
                                <option value="false" @if(isset($settings->interstital_ad) AND $settings->interstital_ad=='false') selected @endif>False</option>
                            </select>
                      </div>
                  </div>
                  <div class="form-group row" id="admob_interstitial_id" @if($settings->interstitial_ad_type!="Admob") style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.android_interstitial_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="interstital_ad_id" value="{{ isset($settings->interstital_ad_id) ? $settings->interstital_ad_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row" id="fb1_interstitial_id" @if($settings->interstitial_ad_type!="Facebook") style="display:none;" @endif>
                    <label class="col-sm-3 col-form-label">{{trans('words.android_interstitial_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="fb_interstitial_id" value="{{ isset($settings->fb_interstitial_id) ? $settings->fb_interstitial_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.android_interstitial_clicks')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="interstital_ad_click" value="{{ isset($settings->interstital_ad_click) ? $settings->interstital_ad_click : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="offset-sm-8 col-sm-9">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> {{trans('words.save_settings')}} </button>
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
      @include("admin.copyright")
    </div>

<!--  Logo -->
<div id="model_poster" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&field_id=app_logo&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>


@endsection
