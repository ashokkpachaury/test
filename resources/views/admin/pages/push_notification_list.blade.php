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
                                    <a href="{{URL::to('admin/push_notification/add_notification')}}"
                                       class="btn btn-success btn-md waves-effect waves-light m-b-20"
                                       data-toggle="tooltip" title="{{trans('words.add_push')}}"><i
                                            class="fa fa-plus"></i> {{trans('words.add_push')}}</a>
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
{{--                                        <th>{{trans('words.push_image')}}</th>--}}
                                        <th>{{trans('words.push_title')}}</th>
                                        <th>{{trans('words.push_message')}}</th>
                                        <th>{{trans('words.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($notification_list as $i => $notification)
                                        <tr>
{{--                                            <td>--}}
{{--                                                @if(isset($notification->image))--}}
{{--                                                    <img src="{{URL::to('upload/'.$notification->image)}}"--}}
{{--                                                         alt="push image" class="img-thumbnail" width="100">--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
                                            <td>{{ stripslashes($notification->name) }}</td>
                                            <td>{{ stripslashes($notification->message) }}</td>
                                            <td>
                                                <a href="{{ route('notification-push.resend', $notification->id)  }}"
                                                   class="btn btn-icon waves-effect waves-light btn-warning m-b-5"
                                                   onclick="return confirm('Are you sure to send this notification')"
                                                   data-toggle="tooltip" title="Resend Notification"> Resend </a>

                                                <a href="{{ url('admin/push_notification/delete/'.$notification->id) }}"
                                                   class="btn btn-icon waves-effect waves-light btn-danger m-b-5"
                                                   onclick="return confirm('{{trans('words.dlt_warning_text')}}')"
                                                   data-toggle="tooltip" title="{{trans('words.remove')}}"> <i
                                                        class="fa fa-remove"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <nav class="paging_simple_numbers">
                                @include('admin.pagination', ['paginator' => $notification_list])
                            </nav>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("admin.copyright")
    </div>
@endsection
