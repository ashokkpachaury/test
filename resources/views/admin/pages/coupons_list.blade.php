@extends("admin.admin_app")
@section("content")
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card-box table-responsive">
                <div class="row">
                <div class="col-md-3">
                  <a href="{{URL::to('admin/coupons/add_coupon')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.add_coupon')}}"><i class="fa fa-plus"></i> {{trans('words.add_coupon')}}</a>
                </div>
              </div>

                @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
                @endif
                <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>{{trans('words.coupon_title')}}</th>
                      <th>{{trans('words.coupon_promo')}}</th>
                      <th>{{trans('words.coupon_amount')}}</th>
                      <th>{{trans('words.expiry_date')}}</th>  
                      <th>{{trans('words.status')}}</th>                      
                      <th>{{trans('words.action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($coupons_list as $i => $coupon_data)
                    <tr>
                      <td>{{ $coupon_data->title }}</td>
                      <td>{{ $coupon_data->promo_code }}</td>
                      <td>{{getcong('currency_code')}} {{ $coupon_data->amount }}</td>
                      <td>{{ date('m/d/Y',$coupon_data->expiry_date) }}</td>
                      <td>@if($coupon_data->status==1)<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger">{{trans('words.inactive')}}</span>@endif</td>                       
                      <td>
                      <a href="{{ url('admin/coupons/edit_coupon/'.$coupon_data->id) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>
                      <a href="{{ url('admin/coupons/delete/'.$coupon_data->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>           
                      </td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $coupons_list]) 
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>
@endsection