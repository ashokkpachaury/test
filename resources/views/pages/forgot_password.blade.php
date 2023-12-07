<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{getcong('site_name')}} Admin">
  <meta name="author" content="ReDiscover Tech, LLC">
  <link rel="shortcut icon" href="{{ URL::asset('upload/source/'.getcong('site_favicon')) }}">
  <title>{{trans('words.forgot_password').' | '.getcong('site_name')}}</title>
  <!-- App css -->
  @if(getcong('external_css_js')=="CDN")
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('admin_assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <script src="{{ URL::asset('admin_assets/js/modernizr.min.js') }}"></script>
  @else
  <link href="{{ URL::asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('admin_assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::asset('admin_assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
  <script src="{{ URL::asset('admin_assets/js/modernizr.min.js') }}"></script>
  @endif
</head>
<body>
  <div class="account-pages"></div>
  <div class="clearfix"></div>
  <div class="wrapper-page">
    <div class="text-center">
      @if(getcong('site_logo'))
        <a class="navbar-brand" href="{{ URL::to('/') }}" target="_blank"> <img src="{{ URL::asset('upload/source/'.getcong('site_logo')) }}" alt="Site Logo" style="width: 158px;"> </a> 
      @else
        <a class="navbar-brand" href="{{ URL::to('/') }}" target="_blank"> <img src="{{ URL::asset('site_assets/images/template/logo.png') }}" alt="Site Logo" style="width: 158px;"> </a>          
      @endif
     
    </div>
    <div class="m-t-20 card-box">
      <div class="text-center">
        <h3 class="text-uppercase font-bold m-b-0">{{trans('words.forgot_password')}}</h3>
        <div class="message">                                                 
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
            @if(Session::has('error_flash_message'))
                      <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                          {{ Session::get('error_flash_message') }}
                      </div>
            @endif             
        </div>

      </div>
      <div class="p-10">
        {!! Form::open(array('url' => 'password/email','class'=>'js-validation-login form-horizontal push-30-t push-50','id'=>'passwordform','role'=>'form')) !!} 
            <div class="form-group">
                <label for="email">{{trans('words.email')}}</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="{{trans('words.email')}}">
            </div>
            <div class="form-group">
                <button class="btn btn-block btn-primary" type="submit"><i class="si si-envelope-open pull-right"></i>{{trans('words.reset_password')}}</button>
            </div>
        {!! Form::close() !!} 
      </div>
    </div>
  </div>
  @if(getcong('external_css_js')=="CDN")
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="{{ URL::asset('admin_assets/js/popper.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script> 
  @else  
  <script src="{{ URL::asset('admin_assets/js/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/popper.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/bootstrap.min.js') }}"></script>  
  @endif
  <!-- App js -->
  <script src="{{ URL::asset('admin_assets/js/jquery.core.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/jquery.app.js') }}"></script>
</body>
</html>