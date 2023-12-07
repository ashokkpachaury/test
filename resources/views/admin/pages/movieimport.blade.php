@extends("admin.admin_app")
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
            <div class="table-responsive">
              <div class="m-t-20 card-box">
                <div class="text-center">
                  <h3 class="text-uppercase font-bold m-b-0">Import from feed</h3>

                  <div class="message">

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                      {{ session('error') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    @endif


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
                  </div>

                </div>
                <div class="row">
                  <div class="col-2"></div>
                  <div class="p-10 col-8">
                    <form id="addForm" role="form" class="form-horizontal" method="post"
                      action="{{ url('admin/upload_feed') }}" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                      <div class="form-body">
                        <div class="form-group">
                          <label class="control-label">Import Url : </label>
                          <input class="form-control" name="import_url" type="text">
                        </div>
                      </div>
                      <div class="form-group form-actions right1 text-center">
                        <button class="btn btn-block btn-primary" type="submit"><i
                            class="si si-envelope-open pull-right"></i> Submit</button>
                      </div>
                    </form>
                  </div>
                  <div class="col-2"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include("admin.copyright")
</div>
@endsection