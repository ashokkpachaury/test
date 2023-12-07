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

                <div class="row">
                     <div class="col-sm-6">
                          <a href="{{ URL::to('admin/ads_list') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> Back</h4></a>
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

                 {!! Form::open(array('url' => array('admin/ads_list/add_ads_edit'),'class'=>'form-horizontal','name'=>'ads_form','id'=>'ads_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($ad_info->id) ? $ad_info->id : null }}">

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.ad_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="ad_title" value="{{ isset($ad_info->ad_title) ? $ad_info->ad_title : null }}" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.image')}}</label>
                    <div class="col-sm-8">
                      <input type="file" name="ad_image" class="form-control">                     
                    </div>
                  </div>

                  @if(!empty($ad_info->ad_image)) 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">                                             
                      <img src="{{URL::to('upload/'.$ad_info->ad_image)}}"  class="img-thumbnail" width="250">                        
                    </div>
                  </div>
                  @endif
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.status')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="status">                               
                                <option value="1" @if(isset($ad_info->status) AND $ad_info->status==1) selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if(isset($ad_info->status) AND $ad_info->status==0) selected @endif>{{trans('words.inactive')}}</option>                            
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

  

@endsection