@extends("admin.admin_app")
@section("content")
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                <div class="row">
                  <div class="col-sm-6">
                       <a href="{{ URL::to('admin/moderators') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
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
                 {!! Form::open(array('url' => array('admin/moderators/add_edit_moderator'),'class'=>'form-horizontal','name'=>'user_form','id'=>'user_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  <input type="hidden" name="id" value="{{ isset($moderator->id) ? $moderator->id : null }}">
                  <input type="hidden" name="usertype" value="{{ isset($moderator->usertype) ? $moderator->usertype : 'Moderator' }}">

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.name')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="name" value="{{ isset($moderator->name) ? $moderator->name : null }}" class="form-control" placeholder="{{trans('words.name')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.email')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="email" value="{{ isset($moderator->email) ? $moderator->email : null }}" class="form-control" placeholder="{{trans('words.email')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.password')}}*</label>
                    <div class="col-sm-8">
                      <input type="password" name="password" value="" class="form-control" placeholder="{{trans('words.password')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.phone')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="phone" value="{{ isset($moderator->phone) ? $moderator->phone : null }}" class="form-control" placeholder="{{trans('words.phone')}}">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.address')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="user_address" value="{{ isset($moderator->user_address) ? $moderator->user_address : null }}" class="form-control" placeholder="{{trans('words.address')}}">
                    </div>
                  </div>
                   
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.image')}}</label>
                    <div class="col-sm-8">
                      <input type="file" name="user_image" class="form-control">                     
                    </div>
                  </div>

                  @if(isset($moderator->user_image) AND file_exists(public_path('upload/'.$moderator->user_image))) 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">                                                  
                      <img src="{{URL::to('upload/'.$moderator->user_image)}}" alt="video image" class="img-thumbnail" width="250">                        
                    </div>
                  </div>
                  @endif                  

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.status')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="status">                               
                                <option value="1" @if(isset($moderator->status) AND $moderator->status==1) selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if(isset($moderator->status) AND $moderator->status==0) selected @endif>{{trans('words.inactive')}}</option>                            
                            </select>
                      </div>
                  </div>
                  @if(!empty($bank['0'])) 
                    <input type="hidden" name="bank_id" value="{{ isset($bank['0']->id) ? $bank['0']->id : null }}" class="form-control">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">{{trans('words.bank_name')}}</label>
                      <div class="col-sm-8">
                        <input type="text" name="bank_name" value="{{ isset($bank['0']->name) ? $bank['0']->name : null }}" class="form-control" placeholder="{{trans('words.bank_name')}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">{{trans('words.bank_account')}}</label>
                      <div class="col-sm-8">
                        <input type="text" name="bank_account" value="{{ isset($bank['0']->account) ? $bank['0']->account : null }}" class="form-control" placeholder="{{trans('words.bank_account')}}">
                      </div>
                    </div>
                  @else  
                    <input type="hidden" name="bank_id" value="" class="form-control">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">{{trans('words.bank_name')}}</label>
                      <div class="col-sm-8">
                        <input type="text" name="bank_name" value="" class="form-control" placeholder="{{trans('words.bank_name')}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">{{trans('words.bank_account')}}</label>
                      <div class="col-sm-8">
                        <input type="text" name="bank_account" value="" class="form-control" placeholder="{{trans('words.bank_account')}}">
                      </div>
                    </div>
                  @endif
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