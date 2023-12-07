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
                  <a href="{{URL::to('admin/ads_list/add_ads')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.add_ad')}}"><i class="fa fa-plus"></i> {{trans('words.add_ad')}}</a>
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
                      <th>{{trans('words.ad_title')}}</th>
                      <th>{{trans('words.image')}}</th>
                      <th>{{trans('words.status')}}</th>                        
                      <th>{{trans('words.action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($ads_list as $i => $ads_info)
                    <tr>
                      <td>{{ $ads_info->ad_title }}</td>
                      <td>
                        @if(!empty($ads_info->ad_image))                                         
                            <img src="{{URL::to('upload/'.$ads_info->ad_image)}}" alt="ad image" class="img-thumbnail" width="100">                         
                        @endif
                      </td>
                      <td>@if($ads_info->status==1)<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger">{{trans('words.inactive')}}</span>@endif</td>                       
                      <td>
                      <a href="{{ url('admin/ads_list/ads_edit/'.$ads_info->id) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>                            
                      <a href="{{ url('admin/ads_list/delete/'.$ads_info->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>           
                      </td>
                    </tr>
                   @endforeach
                     
                     
                     
                  </tbody>
                </table>
              </div>                 
              <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $ads_list]) 
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>

    

@endsection