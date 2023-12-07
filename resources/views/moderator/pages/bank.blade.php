@extends("moderator.moderator_app")
@section("content")
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-xl-8">
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

                {!! Form::open(array('url' => 'moderator/bank','class'=>'form-horizontal','name'=>'profile_form','id'=>'profile_form','role'=>'form','enctype' => 'multipart/form-data')) !!}
                  <input type="hidden" name="id" value="{{ isset($bank['0']->id) ? $bank['0']->id : null }}">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.bank_name')}} *</label>
                    <div class="col-sm-8">
                       <input type="text" name="name" value="{{ isset($bank['0']->name) ? stripslashes($bank['0']->name) : null }}" class="form-control">
                    </div>
                  </div>                   
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.bank_account')}} *</label>
                    <div class="col-sm-8">
                       <input type="text" name="account" value="{{ isset($bank['0']->account) ? $bank['0']->account : null }}" class="form-control" value="">
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
      @include("moderator.copyright") 
    </div>
@endsection