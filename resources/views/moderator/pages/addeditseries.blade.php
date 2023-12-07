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
                      <a href="{{ URL::to('moderator/series') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
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

                @if(!isset($series_info->id))
                <div id="result" class="m-t-15"></div>

                 <input type="hidden" id="from" name="from" value="series">

                <hr/>
                @endif

                 {!! Form::open(array('url' => array('moderator/series/add_edit_series'),'class'=>'form-horizontal','name'=>'series_form','id'=>'series_form','role'=>'form','enctype' => 'multipart/form-data')) !!}

                  <input type="hidden" name="id" value="{{ isset($series_info->id) ? $series_info->id : null }}">

                  <input type="hidden" name="imdb_id" id="imdb_id" value="">
                  <input type="hidden" name="imdb_votes" id="imdb_votes" value="">

                  <div class="row">

                    <div class="col-md-6">
                      <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.show_info')}}</h4>

                  <div class="form-group row" style="display: none;">
                    <input type="text" name="language" id="show_language" value="2" class="form-control">
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.genres_text')}}*</label>
                      <div class="col-sm-8">
                            <select name="series_genres[]" class="select2 select2-multiple" multiple="multiple" multiple id="show_genre_id" data-placeholder="{{trans('words.select_genres')}}">
                                 @foreach($genre_list as $genre_data)
                                  <option value="{{$genre_data->id}}" @if(isset($series_info->id) && in_array($genre_data->id, explode(",",$series_info->series_genres))) selected @endif>{{$genre_data->genre_name}}</option>
                                @endforeach
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.show_name')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="series_name" id="show_name" value="{{ isset($series_info->series_name) ? stripslashes($series_info->series_name) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.show_sort_info')}}</label>
                    <div class="col-sm-8">
                       <textarea name="series_info" id="show_info" class="form-control">{{ isset($series_info->series_info) ? stripslashes($series_info->series_info) : null }}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="control-label col-sm-3">{{trans('words.imdb_rating')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" id="imdb_rating" name="imdb_rating" value="{{ isset($series_info->imdb_rating) ? $series_info->imdb_rating : old('imdb_rating') }}" class="form-control" placeholder="">

                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.show_poster')}}* <small id="emailHelp" class="form-text text-muted">({{trans('words.recommended_resolution')}} : 1200x500)</small></label>
                    <div class="col-sm-8">
                      <div class="input-group">

                        <input type="text" name="series_poster" id="series_poster" value="{{ isset($series_info->series_poster) ? $series_info->series_poster : null }}" class="form-control" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-dark waves-effect waves-light" data-toggle="modal" data-target="#model_poster">Select</button>

                        </div>
                      </div>

                    </div>
                  </div>

                  @if(isset($series_info->series_poster))
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">

                           <img src="{{($series_info->series_poster)}}" alt="video image" class="img-thumbnail" width="250">

                    </div>
                  </div>
                  @endif

                  </div>
                  <div class="col-md-6">
                    <h4 class="m-t-0 m-b-30 header-title" style="font-size: 20px;">{{trans('words.seo')}}</h4>

                    <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="seo_title" id="seo_title" value="{{ isset($series_info->seo_title) ? stripslashes($series_info->seo_title) : old('seo_title') }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_desc')}}</label>
                    <div class="col-sm-8">
                      <textarea name="seo_description" id="seo_description" class="form-control">{{ isset($series_info->seo_description) ? stripslashes($series_info->seo_description) : old('seo_description') }}</textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.seo_keyword')}}</label>
                    <div class="col-sm-8">
                      <textarea name="seo_keyword" id="seo_keyword" class="form-control">{{ isset($series_info->seo_keyword) ? stripslashes($series_info->seo_keyword) : old('seo_keyword') }}</textarea>
                      <small id="emailHelp" class="form-text text-muted">{{trans('words.seo_keyword_note')}}</small>
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

<!--  Poster -->
<div id="model_poster" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
               <div class="iframe-container">
               <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&sort_by=date&field_id=series_poster&relative_url=1')}}" frameborder="0"></iframe>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
