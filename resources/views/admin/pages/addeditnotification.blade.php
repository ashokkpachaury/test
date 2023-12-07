@extends("admin.admin_app")

@section("content")

    <style type="text/css">
        .iframe-container {
            overflow: hidden;
            padding-top: 56.25% !important;
            position: relative;
        }

        .iframe-container iframe {
            border: 0;
            height: 100%;
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-6">
                                     <a href="{{ URL::to('admin/add_notification') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
                                </div>
                              </div>

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(Session::has('flash_message'))
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    {{ Session::get('flash_message') }}
                                </div>
                            @endif

                            {!! Form::open(array('url' => array('admin/push_notification/add_edit_notification'),'class'=>'form-horizontal','name'=>'category_form','id'=>'category_form','role'=>'form','enctype' => 'multipart/form-data')) !!}

                            <input type="hidden" name="id"
                                   value="{{ isset($notification->id) ? $notification->id : null }}">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('words.push_title')}}*</label>
                                <div class="col-sm-8">
                                    <input type="text" name="push_title"
                                           value="{{ isset($notification->name) ? $notification->name : null }}"
                                           class="form-control" placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('words.push_message')}}*</label>
                                <div class="col-sm-8">
                                    <textarea name="push_message"
                                              class="form-control">{{ isset($notification->message) ? $notification->message : null }}</textarea>
                                </div>
                            </div>

{{--                            <div class="form-group row">--}}
{{--                                <label class="col-sm-3 col-form-label">{{trans('words.push_image')}}</label>--}}
{{--                                <div class="col-sm-8">--}}
{{--                                    <input type="file" name="push_image" class="form-control">--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            @if(isset($notification->image))
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-sm-3 col-form-label">&nbsp;</label>--}}
{{--                                    <div class="col-sm-8">--}}
{{--                                        <img src="{{URL::to('upload/'.$notification->image)}}" alt="push image"--}}
{{--                                             class="img-thumbnail" width="250">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            @endif

                            <div class="form-group">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit"
                                            class="btn btn-primary waves-effect waves-light"> {{trans('words.send')}} </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include("admin.copyright")
    </div>
@endsection
