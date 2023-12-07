@extends("admin.admin_app")
@section("content")
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card-box table-responsive">
                <div class="row">
                  <div class="col-md-3 m-b-20">
                    
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
                      <th>{{trans('words.image')}}</th>  
                      <th>{{trans('words.name')}}</th>
                      <th>{{trans('words.email')}}</th>
                      <th>{{trans('words.phone')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($user_profile_list as $i => $user_data)
                    <tr>
                      <td>@if(isset($user_data->user_image)) <img src="{{URL::to('upload/'.$user_data->user_image)}}" alt="profile image" class="thumb-lg bdr_radius"> @else <img src="{{URL::to('upload/profile.jpg')}}" alt="profile image" class="thumb-lg bdr_radius"> @endif</td>
                      <td>{{ $user_data->name }}</td>
                      <td>{{ $user_data->email }}</td>
                      <td>{{ $user_data->phone }}</td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $user_profile_list]) 
                </nav>
           
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>
@endsection