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
                     {!! Form::open(array('url' => 'admin/movies','class'=>'app-search','id'=>'search','role'=>'form','method'=>'get')) !!}
                      <input type="text" name="s" placeholder="{{trans('words.search_by_title')}}" class="form-control">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    {!! Form::close() !!}
                  </div>
                <div class="col-md-3">
                  <a href="{{URL::to('admin/movies/add_movie')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.add_movie')}}"><i class="fa fa-plus"></i> {{trans('words.add_movie')}}</a>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3">
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
                      <th>{{trans('words.movie_name')}}</th>
                      <th>{{trans('words.movie_poster')}}</th>
                      <th>{{trans('words.movie_access')}}</th>
                      <th>{{trans('words.status')}}</th>
                      <th>{{trans('words.action')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($movies_list as $i => $movies)
                    <tr>
                      <td>{{ stripslashes($movies->video_title) }}</td>
                      <td>@if(isset($movies->video_image_thumb)) <img src="{{$movies->video_image_thumb}}" alt="video image" class="thumb-lg bdr_radius"> @endif</td>
                      <td>{{ $movies->video_access }}</td>
                      <td>@if($movies->status==1)<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger">{{trans('words.inactive')}}</span>@endif</td>
                      <td>
                      <a href="{{ url('admin/movies/edit_movie/'.$movies->id) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>
                      <a href="{{ url('admin/movies/delete/'.$movies->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>
                      </td>
                    </tr>
                   @endforeach



                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $movies_list])
                </nav>

              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright")
    </div>



@endsection
