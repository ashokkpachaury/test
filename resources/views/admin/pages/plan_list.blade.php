@extends("admin.admin_app")
@section('styles')
<!-- Your specific CSS styles for this view -->
<style>
    /* Custom CSS for hover effect */
    .btn.btn-success:hover {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }

    /* Custom CSS for button spacing */
    .btn-container {
        margin: 10px;
    }

    /* Custom background style */
    .background-style {
        background-color: #f2f2f2;
        padding: 15px;
        border-radius: 5px;
    }

    /* Custom CSS for anchor tags within the foreach loop */
    .platform {
        width: 150px;
        /* Set your desired fixed width */
        margin-right: 10px;
        /* Set the desired spacing between buttons */
        margin-bottom: 10px;
        /* Optional: add margin at the bottom of each button */
    }

    .end {
        text-align: end;
    }
</style>
@endsection
@section("content")


<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card-box table-responsive">

                        @if(Session::has('flash_message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            {{ Session::get('flash_message') }}
                        </div>
                        @endif

                        @if(!empty($platform_list))
                        <div class="background-style">


                            <div class="row btn-container">
                                @foreach($platform_list as $i => $platform_data)
                                <a href="{{ URL::to('admin/subscription_plan/' . $platform_data->name) }}" class="btn btn-success btn-md waves-effect waves-light m-b-20 p-2 platform">{{ $platform_data->name }}</a>

                                @endforeach
                            </div>


                            <div class="row btn-container">
                                <a href="{{URL::to('admin/platform/add_platform')}}" class="btn btn-secondary btn-md waves-effect waves-light platform"><i class="fa fa-plus"></i>{{trans('words.add_platform')}}</a>
                            </div>
                        </div>
                        @endif




                        @if(!empty($plan_list))

                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{URL::to('admin/subscription_plan/add_plan/' . $platform)}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.add_plan')}}"><i class="fa fa-plus"></i> {{trans('words.add_plan')}}</a>
                            </div>

                            <!-- Move the following <div> to the next line using offset class -->

                            <div class="col-md-3 col-md-offset-3 end">
                                <!-- This is the button that was moved -->
                                <a href="{{URL::to('admin/plateform/delete/' . $platform)}}" class="btn btn-danger btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.delete_platform')}}"><i class="fa fa-remove"></i> {{trans('words.delete_platform')}}</a>
                            </div>
                        </div>



                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{trans('words.plan_name')}}</th>
                                        <th>{{trans('words.duration')}}</th>
                                        <th>{{trans('words.price')}}</th>
                                        <th>{{trans('words.productId')}}</th>
                                        <th>{{trans('words.status')}}</th>
                                        <th>{{trans('words.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plan_list as $i => $plan_data)
                                    <tr>
                                        <td>{{ $plan_data->plan_name }}</td>
                                        <td>{{ App\SubscriptionPlan::getPlanDuration($plan_data->id) }}</td>
                                        <td>{{getcong('currency_code')}} {{ $plan_data->plan_price }}</td>
                                        <td> {{ $plan_data->productId }}</td>
                                        <td>@if($plan_data->status==1)<span class="badge badge-success">{{trans('words.active')}}</span> @else<span class="badge badge-danger">{{trans('words.inactive')}}</span>@endif</td>
                                        <td>
                                            <a href="{{ url('admin/subscription_plan/edit_plan/'.$plan_data->id) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>
                                            <a href="{{ url('admin/subscription_plan/delete/'.$plan_data->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>
                                        </td>
                                    </tr>
                                    @endforeach



                                </tbody>
                            </table>
                        </div>
                        <nav class="paging_simple_numbers">
                            @include('admin.pagination', ['paginator' => $plan_list])
                        </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("admin.copyright")
</div>



@endsection
