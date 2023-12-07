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
                       <a href="{{ URL::to('admin/coupons') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
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
                

                 {!! Form::open(array('url' => array('admin/coupons/add_edit_coupon'),'class'=>'form-horizontal','name'=>'slider_form','id'=>'slider_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($coupon_info->id) ? $coupon_info->id : null }}">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_title')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="title" value="{{ isset($coupon_info->title) ? $coupon_info->title : null }}" class="form-control" placeholder="Enter Coupon Title">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_promo')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="promo_code" value="{{ isset($coupon_info->promo_code) ? $coupon_info->promo_code : null }}" class="form-control" placeholder="Promo Code Atleast 5 to 10 with Letters,Numbers,Uppercase only without space">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_type')}}</label>
                    <div class="col-sm-8">
                        <select name="amount_type" class="form-control">
                         <option value="0" @if(isset($coupon_info->amount_type) AND $coupon_info->amount_type=='0') selected @endif>Percentage Amount</option>
                         <option value="1" @if(isset($coupon_info->amount_type) AND $coupon_info->amount_type=='1') selected @endif>Absolute Amount</option>
                        </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_amount')}}* </label>
                    <div class="col-sm-8">
                      <input type="text" name="amount" value="{{ isset($coupon_info->amount) ? $coupon_info->amount : null }}" class="form-control" placeholder="9.99">
                    </div>
                  </div>  
                  <div class="form-group row">
                    <label class="control-label col-sm-3">{{trans('words.expiry_date')}}</label>
                    <div class="col-sm-8">
                      <div class="input-group"> 
                        <input type="text" id="datepicker-autoclose" name="expiry_date" value="{{ isset($coupon_info->expiry_date) ? date('m/d/Y',$coupon_info->expiry_date) : null }}" class="form-control" placeholder="mm/dd/yyyy">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ti-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_users_limit')}} </label>
                    <div class="col-sm-8">
                      <input type="text" name="users_limit" value="{{ isset($coupon_info->users_limit) ? $coupon_info->users_limit : null }}" class="form-control" placeholder="No of Users Limit">
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.pre_users_limit')}} </label>
                    <div class="col-sm-8">
                      <input type="text" name="per_users_limit" value="{{ isset($coupon_info->per_users_limit) ? $coupon_info->per_users_limit : null }}" class="form-control" placeholder="Per Users Limit">
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.coupon_description')}} </label>
                    <div class="col-sm-8">
                      <textarea name="description" class="form-control" max="255">{{ isset($coupon_info->description) ? $coupon_info->description : null }}</textarea>
                    </div>
                  </div> 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.status')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="status">                               
                                <option value="1" @if(isset($coupon_info->status) AND $coupon_info->status==1) selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if(isset($coupon_info->status) AND $coupon_info->status==0) selected @endif>{{trans('words.inactive')}}</option>                            
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                     
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