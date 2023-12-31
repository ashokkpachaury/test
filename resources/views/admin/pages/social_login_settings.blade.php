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
                

                 {!! Form::open(array('url' => array('admin/social_login_settings'),'class'=>'form-horizontal','name'=>'settings_form','id'=>'settings_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  <input type="hidden" name="id" value="{{ isset($settings->id) ? $settings->id : null }}">
                  <h5 class="m-b-5"><i class="fa fa-google"></i> <b>Google Settings</b></h5>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.google_login')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="google_login">                               
                                 
                                <option value="1" @if($settings->google_login=="1") selected @endif>ON</option>
                                <option value="0" @if($settings->google_login=="0") selected @endif>OFF</option>
                                              
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.google_client_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="google_client_id" value="{{ isset($settings->google_client_id) ? $settings->google_client_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.google_secret')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="google_client_secret" value="{{ isset($settings->google_client_secret) ? $settings->google_client_secret : null }}" class="form-control">
                    </div>
                  </div> 
                  <br>

                  <h5 class="m-b-5"><i class="fa fa-facebook"></i> <b>Facebook Settings</b></h5>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.facebook_login')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="facebook_login">                               
                                 
                                <option value="1" @if($settings->facebook_login=="1") selected @endif>ON</option>
                                <option value="0" @if($settings->facebook_login=="0") selected @endif>OFF</option>
                                              
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.facebook_app_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="facebook_app_id" value="{{ isset($settings->facebook_app_id) ? $settings->facebook_app_id : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.facebook_secret')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="facebook_client_secret" value="{{ isset($settings->facebook_client_secret) ? $settings->facebook_client_secret : null }}" class="form-control">
                    </div>
                  </div>
                  <br>
                  <h5 class="m-b-5"><i class="fa fa-send"></i> <b>Push Notification Settings</b></h5>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.fcm_server_key')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="fcm_server_key" value="{{ isset($settings->fcm_server_key) ? $settings->fcm_server_key : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.fcm_sender_id')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="fcm_sender_id" value="{{ isset($settings->fcm_sender_id) ? $settings->fcm_sender_id : null }}" class="form-control">
                    </div>
                  </div>
                  <br>
                  <h5 class="m-b-5"><i class="fa fa-send"></i> <b>Mailchimp Settings</b></h5>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Mailchimp Api Key</label>
                    <div class="col-sm-8">
                      <input type="text" name="mailchimp_apikey" value="{{ isset($settings->mailchimp_apikey) ? $settings->mailchimp_apikey : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Mailchimp List Id</label>
                    <div class="col-sm-8">
                      <input type="text" name="mailchimp_list_id" value="{{ isset($settings->mailchimp_list_id) ? $settings->mailchimp_list_id : null }}" class="form-control">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="offset-sm-3 col-sm-9">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"> {{trans('words.save_settings')}} </button>                      
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
@endsection