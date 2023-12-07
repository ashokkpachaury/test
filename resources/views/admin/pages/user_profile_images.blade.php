@extends("admin.admin_app")

@section("content")

  
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card-box table-responsive">

                <div class="row">
                  <div class="col-sm-3">
                  </div>
                  <div class="col-md-3">
                     {!! Form::open(array('url' => 'admin/user_profile_images','class'=>'app-search','id'=>'search','role'=>'form','method'=>'get')) !!}   
                      <input type="text" name="s" placeholder="Search by name" class="form-control">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    {!! Form::close() !!}
                  </div>   
                
                <div class="col-md-3">
                    &nbsp;&nbsp;
                </div>          
                <div class="col-md-3">
                  <a href="{{route('add_user_profile_image')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="Add Profile Image"><i class="fa fa-plus"></i> Add Profile Image</a>
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
                      <th>{{trans('words.image')}}</th> 
                      <th>{{trans('words.default_word')}}</th>                        
                      <th>{{trans('words.action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($user_profile_images as $i => $user_profile_image)
                    <tr>
                      <td>{{ $user_profile_image->title }}</td>                      
                      <td>@if(isset($user_profile_image->url)) <img src="{{URL::to('upload/source/'.$user_profile_image->url)}}" alt="profile image" class="thumb-md bdr_radius"> @else <img src="{{URL::to('upload/profile.jpg')}}" alt="profile image" class="thumb-lg bdr_radius"> @endif</td>
                      
                      <td>@if($user_profile_image->is_default==1)<span class="badge badge-success">{{trans('words.default')}}</span> @else<span class="badge badge-danger">{{trans('words.not_default')}}</span>@endif</td>
                                             
                      <td>
                      <a href="{{ url('admin/edit_user_profile_image/'.$user_profile_image->id) }}" class="btn btn-icon waves-effect waves-light btn-primary m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-pencil"></i> </a>
                      <a href="{{ url('admin/delete_user_profile_image/'.$user_profile_image->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>           
                      </td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $user_profile_images]) 
                </nav>
           
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>

    

@endsection