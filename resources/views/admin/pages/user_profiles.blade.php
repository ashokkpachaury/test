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
                     {!! Form::open(array('url' => 'admin/user_profiles','class'=>'app-search','id'=>'search','role'=>'form','method'=>'get')) !!}   
                      <input type="text" name="s" placeholder="Search by name" class="form-control">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    {!! Form::close() !!}
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
                      <th>{{trans('words.users')}}</th>
                      <th>{{trans('words.name')}}</th>
                      <th>{{trans('words.image')}}</th> 
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($user_profiles as $i => $user_profile)
                    <tr>
                      <td>{{ App\User::getUserFullname($user_profile->user_id) }}</td>    
                      <td>{{ $user_profile->title }}</td>
                      <td>@if(isset($user_profile->image)) <img src="{{URL::to('upload/source/'.(App\ProfileImages::getprofileinfo($user_profile->image)->url))}}" alt="profile image" class="thumb-md bdr_radius"> @else <img src="{{URL::to('upload/profile.jpg')}}" alt="profile image" class="thumb-lg bdr_radius"> @endif</td>
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $user_profiles]) 
                </nav>
           
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>

    

@endsection