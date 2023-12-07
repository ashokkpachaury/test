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
                

                 {!! Form::open(array('url' => array('admin/save_user_profile_image'),'class'=>'form-horizontal','name'=>'slider_form','id'=>'slider_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($profile_image->id) ? $profile_image->id : null }}">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.name')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="name" value="{{ isset($profile_image->title) ? $profile_image->title : null }}" class="form-control" placeholder="Enter Avtar Title">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.image')}}*</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="hidden" name="thumb_link" id="thumb_link" value="">
                        <input type="text" name="url" id="url" value="{{ isset($profile_image->url) ? $profile_image->url : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_profile_image_url">Select</button>

                        </div>
                      </div>

                    </div>
                  </div>
                  @if(isset($profile_image->url) AND $profile_image->url!='')
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">

                           <img src="{{URL::to('upload/source/'.$profile_image->url)}}" alt="Profile image" class="img-thumbnail" width="150">

                    </div>
                  </div>
                  @endif
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.default_word')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="is_default">  
                                <option value="0" @if(isset($profile_image->is_default) AND $profile_image->is_default==0) selected @endif>{{trans('words.not_default')}}</option>                            
                                <option value="1" @if(isset($profile_image->is_default) AND $profile_image->is_default==1) selected @endif>{{trans('words.default')}}</option>
                           </select>
                      </div>
                  </div>

                  <div class="form-group">
                    <div class="offset-sm-3 col-sm-9">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"> {{trans('words.save')}} </button>                      
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
    <div id="model_profile_image_url" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg" style="max-width: 900px;">
            <div class="modal-content">
                <div class="modal-body">
                <div class="iframe-container">
                <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=url&relative_url=1')}}" frameborder="0"></iframe>
                </div>
                </div>
            </div>
        </div>
    </div>
 

@endsection