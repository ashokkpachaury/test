@extends("admin.admin_app")

@section("styles")
<style>
    .drag-handle {
        cursor: grab;

    }

    .align {
        text-align: end;
    }

    .color {
        background-color: #e5d8da;
    }

    .dnone {
        display: none;
    }

    td {
        vertical-align: middle !important;
    }

    input[type="text"] {
        width: 40px;
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
                        <div class="row">
                            <div class="col-md-9">
                                <a href="{{route('home-section.create')}}" class="btn btn-success btn-md waves-effect waves-light m-b-20" data-toggle="tooltip" title="{{trans('words.section_add')}}"><i class="fa fa-plus"></i> {{trans('words.section_add')}}</a>
                            </div>


                            <div class="col-md-3 align">
                                <a href="#" class="btn btn-secondary btn-md waves-effect waves-light m-b-20" title="{{trans('words.reorder_sections')}}" id="reorderButton"> {{trans('words.reorder_sections')}}</a>
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
                            <table class="table table-bordered" id="sortable-table">
                                <thead>
                                    <tr>
                                        <th class=" order dnone">Order</th>
                                        <th>{{trans('words.title')}}</th>
                                        <th>{{trans('words.type')}}</th>

                                        <th>{{trans('words.status')}}</th>
                                        <th>{{trans('words.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $i => $item)
                                    <tr data-item-id="{{ $item->id }}" draggable="true" @if($item->is_highlight =="Yes") class="color" @endif>
                                        <td style="width: 10px;" class="order dnone">
                                            <!-- <span class="drag-handle"><i class="fa fa-ellipsis-v" aria-hidden="true"></i>&nbsp;<i class="fa fa-ellipsis-v" aria-hidden="true"></i></span> -->
                                            <input type="text" name="order[{{$item->id}}]" value="{{$item->order_index}}"  oninput="validateNumericInput(this)">
                                        </td>




                                        <td>{{ stripslashes($item->title) }}</td>
                                        <td>{{ stripslashes($item->type) }}</td>

                                        <td>@if($item->status==1)
                                            <span class="badge badge-success">{{trans('words.active')}}</span>
                                            @else
                                            <span class="badge badge-danger">{{trans('words.inactive')}}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('home-section.create', ['id' => $item->id]) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.edit')}}"> <i class="fa fa-edit"></i> </a>

                                            <a href="{{ route('home-section.dublicate', ['id' => $item->id]) }}" class="btn btn-icon waves-effect waves-light btn-success m-b-5 m-r-5" data-toggle="tooltip" title="{{trans('words.dublicate')}}"> <i class="fa fa-clone"></i> </a>

                                            <a href="{{ route('home-section.destroy',$item->id) }}" class="btn btn-icon waves-effect waves-light btn-danger m-b-5" onclick="return confirm('{{trans('words.dlt_warning_text')}}')" data-toggle="tooltip" title="{{trans('words.remove')}}"> <i class="fa fa-remove"></i> </a>


                                        </td>

                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>

                        <nav class="paging_simple_numbers">
                            @include('admin.pagination', ['paginator' => $items])
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("admin.copyright")
</div>

@endsection
