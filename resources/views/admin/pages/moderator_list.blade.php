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
                     {!! Form::open(array('url' => 'admin/moderators','class'=>'app-search','id'=>'search','role'=>'form','method'=>'get')) !!}   
                      <input type="text" name="s" placeholder="{{trans('words.search_by_name_email')}}" class="form-control">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    {!! Form::close() !!}
                  </div>             
                <div class="col-md-3">
                  <a href="{{URL::to('admin/moderators/add_moderator')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.add_moderator')}}"><i class="fa fa-plus"></i> {{trans('words.add_moderator')}}</a>
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
                      <th>{{trans('words.name')}}</th>
                      <th>{{trans('words.email')}}</th>
                      <th>{{trans('words.phone')}}</th>
                      <th>{{trans('words.status')}}</th>                        
                      <th>{{trans('words.action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($moderator_list as $i => $moderator)
                    <tr>
                      <td>{{ $moderator->name }}</td>
                      <td>{{ $moderator->email }}</td>
                      <td>{{ $moderator->phone }}</td>
                      <td>@if($moderator->status==1)<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger">{{trans('words.inactive')}}</span>@endif</td>               
                      <td>
                      <a href="{{ url('admin/moderators/edit_moderator/'.$moderator->id) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>
                      <a href="{{ url('admin/moderators/delete/'.$moderator->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>           
                      </td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $moderator_list]) 
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>
@endsection