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
                     <select class="form-control" name="plan_select" id="plan_select">
                        <option value="">{{trans('words.filter_by_plan')}}</option>
                         @foreach(\App\SubscriptionPlan::orderBy('id')->get() as $plan_data)
                          <option value="?plan_id={{$plan_data->id}}">{{$plan_data->plan_name}}</option>
                         @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="subscription_select" id="subscription_select">
                        <option value="">Filter by Subscription</option>
                        <option value="?s=per-day">Per Day Subscription</option>
                        <option value="?s=per-month">Per Month Subscription</option>
                    </select>
                  </div>             
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                  <!-- <a href="{{URL::to('admin/users/export')}}" class="btn btn-info btn-md waves-effect waves-light m-b-20 pull-right" data-toggle="tooltip" title="{{trans('words.export_user')}}"><i class="fa fa-file-excel-o"></i> {{trans('words.export_user')}}</a> -->
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
                      <th>{{trans('words.plan')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach($users_subscription_list as $i => $user_data)
                    <tr>
                      <td>{{ $user_data->name }}</td>
                      <td>{{ $user_data->email }}</td>
                      <td>{{ $user_data->phone }}</td>
                      <td>
                        @foreach($plan_list as $j => $plan_data)
                          @if($plan_data->id == $user_data->plan_id)
                           {{ $plan_data->plan_name }}
                          @endif
                        @endforeach
                      </td> 
                    </tr>
                   @endforeach
                  </tbody>
                </table>
              </div>
                <nav class="paging_simple_numbers">
                @include('admin.pagination', ['paginator' => $users_subscription_list]) 
                </nav>
           
              </div>
            </div>
          </div>
        </div>
      </div>
      @include("admin.copyright") 
    </div>

    

@endsection