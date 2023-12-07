@extends("admin.admin_app")

@section("content")

<style>
    ul.sortableItems{
      padding-left: 0;
      display: inline !important;
      
    }
    li.sortableItem,
    .ui-state-highlight {
      display: inline-block !important;
      text-align: center;
      font-size: 13px;
      line-height: 18px;
      background-color: #eb1536;
      
      border-radius: 5px;
      color: white;
      /* border: 1px solid #e65858; */
      padding: 0.5em 0.75em;
      margin: 5px 5px;
        box-sizing: border-box;
        height: 2.5em;
        max-width: 100%;
      
    }
    
    .ui-state-highlight{
        display: inline-block !important;
        background-color: #999;
    }
    </style>

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

    .dnone {
        display: none;
    }


    .no-search .select2-search {
        display: none
    }

    /* span#select2-selectOptions-container {
        display: none;
    } */



    ul#select2-selectOptionsBOX-results {
        display: none;
    }

    span#select2-selectOptions-container {
        display: none;
    }

    span.select2-selection.select2-selection--multiple {
        min-height: 250px;
    }


    /* Disable text selection and cursor interaction for Select2 multiple selection */
    span.select2-selection.select2-selection--multiple {
        user-select: none;
        /* Disable text selection */
        pointer-events: none;
        /* Disable cursor interaction */
    }

    /* Enable cursor interaction for Select2 multiple selection's close button */
    span.select2-selection__choice__remove {
        pointer-events: auto;
        /* Enable cursor interaction for the close button */
    }

    .select2-results__option[aria-selected=true] {
        display: none;
    }

    /* li.select2-results__option--highlighted {
        display: none;
    } */

    .pointer{
        pointer-events: auto;
        user-select: none;
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
                                 <a href="{{ URL::to('admin/home-section') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
                            </div>
                          </div>

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
                        <form action="{{route('home-section.store')}}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id}}">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('words.title')}}*</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" value="{{ $item->title}}" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('words.type')}}*</label>
                                <div class="col-sm-8 row">
                                    <div class="col-sm-6 selectOptionsRow" style="float: right;">
                                        <select class="form-control select2 no-search " name="type" id="content-type" {{ !empty($item->id) ? 'disabled' : '' }} data-minimum-results-for-search="Infinity" >
                                            <option disabled selected>Select Type</option>
                                            @foreach(config('data.content_type') as $type )
                                            <option value="{{$type}}" {{$item->type == $type ? 'selected' : ''}}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="col-sm-6  SearchType text-left  @if($item->type != 'Custom'  &&  $item->type != 'Slider') dnone @endif" style="position: relative;left:8px;padding: 0px;">

                                        <select class="form-control select2" name="sub_type" onchange="fetchData(this.value)" id="GenreValue" data-minimum-results-for-search="Infinity" data-placeholder="Select Item type">
                                            <option value="" disabled selected>Select</option>
                                            <option value="Movie">Movie</option>
                                            <option value="Series">Series</option>
                                            <option value="LiveTv">LiveTv</option>
                                        </select>
                                    </div>



                                    <div class="col-sm-6  SearchGenre text-left @if($item->type != 'Genres') dnone @endif" style="position: relative;left:8px;padding: 0px;">

                                        <select class="form-control select2" name="Genre_id" id="SearchGenre" data-placeholder="Select Genre" onchange="Genrecheckbox()" data-minimum-results-for-search="Infinity" {{ !empty($item->genre_id) ? 'disabled' : '' }} >


                                            @foreach($genres_list as $genres_list )
                                            <option value="{{$genres_list['id']}}" {{$genres_list['id'] == $item->genre_id ? 'selected' : ''}}>{{$genres_list['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row  Genres  dnone ">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-8">

                                    <input type="radio" id="Series" name="sub_type" value="Series" onclick="GetGenreData('Series')">
                                    <label for="Series">Series</label>

                                     <input type="radio" id="Movie" name="sub_type" value="Movie" onclick="GetGenreData('Movie')">

                                      <label for="Movie">Movie</label><br>
                                     
                                </div>
                            </div>


                            <div class="form-group row search  dnone @if(count($movies_list) == 0 && count($series_list) == 0) dnone @endif">
                                <label class="col-sm-3 col-form-label l-title">*</label>
                                <div class="col-sm-8">


                                    <select class="select2 selectOptions" data-placeholder="Search Items..." id="selectOptions" aria-placeholder="select" name="selectOptions[]" >
                                        <option disabled selected>Search Items</option>
                                      @foreach($movies_list as $movies_list )
                                        <option value="{{$movies_list['id']}}">{{$movies_list['name']}}</option>
                                        @endforeach

                                        @foreach($series_list as $series_list )
                                        <option value="{{$series_list['id']}}">{{$series_list['name']}}</option>
                                        @endforeach


                                    </select>


                                </div>
                            </div>



                            <div class="form-group row result @if(empty($item->slug)) dnone @endif">
                                <label class="col-sm-3 col-form-label">{{trans('words.items')}}*</label>
                                <div class="col-sm-8">

                                    <ul id="mySortable" class="sortableItems">
                                    </ul>

                                     
                                </div>
                            </div>











                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('Is Highlight')}}</label>
                                <div class="col-sm-8">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="is_highlight" type="checkbox" id="flexSwitchCheckDefault" {{$item->is_highlight ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('words.status')}}*</label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="status" required>
                                        <option value="0" {{$item->status != 1 ? 'selected' : ''}}>Inactive</option>
                                        <option value="1" {{$item->status == 1 ? 'selected' : ''}}>Active</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"> {{trans('words.save')}} </button>
                                </div>
                            </div>

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("admin.copyright")
</div>

<!--  Poster -->
<div id="model_poster" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="iframe-container">
                    <iframe loading="lazy" src="{{URL::to('responsive_filemanager/filemanager/dialog.php?type=2&field_id=slider_image&relative_url=1')}}" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@push('scripts')


<script>
    $("#select2-selectOptions-container").css("display", "none");
    $('.select').select2();

    $('#content-type').off('change').on('change', function() {
        select_type();
    });



    var count = 0;

    function select_type() {
        selectedOptionsObject = {};

        if (<?= !empty($item->slug) && count($item->slug) > 0 ? 'true' : 'false' ?>) {
            $(".result").removeClass("dnone");
        } else {
            $(".result").addClass("dnone");
        }

        // Check if $item->type is "Genres" and count is 0
        if (<?= $item->type === "Genres"  ? 'true' : 'false' ?>) {
            $(".Genres").removeClass("dnone");
        } else {
            $(".Genres").addClass("dnone");
        }

        count++;


        var selectedValue = $("#content-type").val();
        if (selectedValue == "Movie") {
            $(".l-title").text("Search Movie *");
        } else if (selectedValue == "Series") {
            $(".l-title").text("Search Series *");
        } else {
            $(".l-title").text("Search ");
        }

        $(".SearchType select").val([]);

        if (selectedValue == "Custom" || selectedValue == "Slider") {
            $(".search").addClass("dnone");
            $(".SearchType").removeClass("dnone");
            $(".SearchGenre").addClass("dnone");

        } else if (selectedValue == "Genres") {

            $(".SearchGenre").removeClass("dnone");
            $("#selectOptions").removeAttr('multiple');
            $(".SearchType").addClass("dnone");
            $('#SearchGenre').append($('<option>', {
                    value: "Loading... ",
                    text: "Loading... "
            }));
            fetchAllGenre();
        } else if(selectedValue =="Series" || selectedValue =="Movie") {
            //alert("ved");
            $(".search").removeClass("dnone");
            $(".SearchType").addClass("dnone");
            $(".SearchGenre").addClass("dnone");
            fetchData();

        }

        $('#selectOptions').attr('multiple', 'multiple');

    };




    function fetchAllGenre() {
        var selectedValue = $("#content-type").val();
        var GenreValue = $("#GenreValue").val();
        $('#SearchGenre').empty();
        $('#SearchGenre').append($('<option>', {
                value: "Loading... ",
                text: "Loading... "
        }));
        $.ajax({
            url: '/admin/get-options',
            type: 'GET',
            data: {
                selectedValue: selectedValue,
                GenreValue: GenreValue
            },
            dataType: 'json',
            success: function(data) {
                $('#SearchGenre').empty();

                $('#SearchGenre').append($('<option>', {
                    value: "",
                    text: "Select "
                }));
                $.each(data, function(index, option) {
                    $('#SearchGenre').append($('<option>', {
                        value: option.id,
                        text: option.name
                    }));
                });
            }
        });
        @if (!empty($item->genre_id))
            setTimeout(function() {
                $("#SearchGenre").val({{$item->genre_id}}).trigger("change");
            }, 500);
            @endif
    }



    function fetchData(selectedValue = null) {
        $('#selectOptions').append($('<option>', {
            value: "Loading...",
            text: "Loading..."
        }));
        if (selectedValue == "Movie") {
            $(".l-title").text("Search Movie *");
        } else if (selectedValue == "Series") {
            $(".l-title").text("Search Series *");
        } else if (selectedValue == "LiveTv") {
            $(".l-title").text("Search Live Tv *");
        }

        $(".search").removeClass("dnone");
        $(".selectOptionsRow").removeClass("dnone");
        var selectedValue = $("#content-type").val();
        var GenreValue = $("#GenreValue").val();
        $.ajax({
            url: '/admin/get-options',
            type: 'GET',
            data: {
                selectedValue: selectedValue,
                GenreValue: GenreValue
            },
            dataType: 'json',
            success: function(data) {
                $('#selectOptions').empty();
                $('#selectOptions').append($('<option>', {
                    value: "",
                    text: "Select "
                }));
             //  var  slugData={!! json_encode($item->slug) !!};
                $.each(data, function(index, option) {

                    if (!selectedOptionsObject.hasOwnProperty(option.id)) {
                        $('#selectOptions').append($('<option>', {
                            value: option.id,
                            text: option.name,
                           // selected: selected
                        }));
                    }

                });

            }
        });

    }

    function GetGenreData(val) {

        if (val == "Movie") {
            $(".l-title").text("Search Movie *");
        } else if (val == "Series") {
            $(".l-title").text("Search Series *");
        } else {
            $(".l-title").text("Search ");
        }

        $(".search").removeClass("dnone");
        $(".selectOptionsRow").removeClass("dnone");


        var GenreId = $("#SearchGenre").val();
        var GenreType = val;

        $.ajax({
            url: '/admin/get-Genre-data',
            type: 'GET',
            data: {
                GenreId: GenreId,
                GenreType: GenreType
            },
            dataType: 'json',
            success: function(data) {
                $('#selectOptions').empty();

                $('#selectOptions').append($('<option>', {
                    value: "",
                    text: "Select "
                }));
                $.each(data, function(index, option) {
                    if (!selectedOptionsObject.hasOwnProperty(option.id)) {
                        $('#selectOptions').append($('<option>', {
                            value: option.id,
                            text: option.name,

                        }));
                    }
                });
            }
        });
        // alert("ved");
        // $('#SearchGenre option[value="16"]').attr('selected', true)
       //  $("#SearchGenre").val(16).trigger("change");

    }
</script>




<script>
    $("#selectOptions").select2({
        placeholder: "Select a state",
        minimumResultsForSearch: -1

    });


    $('#selectOptionsBOX').on('select2:unselect', function(e) {
        var removedOptionValue = e.params.data.id;
        if (selectedOptionsObject.hasOwnProperty(removedOptionValue)) {
            delete selectedOptionsObject[removedOptionValue];
            $('#selectOptions').find('option[value="' + removedOptionValue + '"]').prop('selected', false);
            $('#selectOptions').trigger('change');
            // console.log('Option removed:', removedOptionValue);
        }
    });

    var checkboxcount = 0;
    function Genrecheckbox() {

        if (checkboxcount != 0) {
            $(".result").addClass("dnone");
            $("#selectOptionsBOX").empty();
            $(".search").addClass("dnone");
        }
        selectedOptionsObject = {};
        const highlightRadios = document.querySelectorAll('[name="sub_type"]');
        highlightRadios.forEach(radio => radio.checked = false);
        var content = $("#content-type").val();
        if (content == "Genres") {
            //  selectOptions
            $(".Genres").removeClass("dnone");
            // alert("ve");


        } else {
            $(".Genres").addClass("dnone");
        }
        checkboxcount++;
    }


    setTimeout(function() {
        select_type();
    }, 100);

    //         setTimeout(function() {
    //    $.each(  {!! json_encode($item->slug) !!}, function(index, option) {
    //                 if (!selectedOptionsObject.hasOwnProperty(option.id)) {
    //                     $('#selectOptions').append($('<option>', {
    //                         value: option.id,
    //                         text: option.name,
    //                         selected: true
    //                     }));
    //                 }
    //             });

    //             $('#selectOptions').trigger('change');

    //         }, 1000);







    $('#selectOptions').on('change', function(e) {

        var sl_value = $("#content-type").val();
        //$("#selectOptionsBOX").empty();
        $("#mySortable").empty();
        if (sl_value !== "Genres" && sl_value !== "Custom" && sl_value !== "Slider") {
            selectedOptionsObject = {};
        }



        $(".result").removeClass("dnone");
        const selectedValue = $(this).val() || [];
        //console.log(selectedValue);
        selectedValue.forEach(function(optionValue) {
            if (!selectedOptionsObject.hasOwnProperty(optionValue)) {
                const selectedText = $("#selectOptions option[value='" + optionValue + "']").text();
                selectedOptionsObject[optionValue] = {
                    value: optionValue,
                    text: selectedText
                };

            }

        });
        $.each(selectedOptionsObject, function(optionValue, optionData) {
            // var newState = new Option(optionData.text, optionData.value, true, true);
            var newLI = '<li draggable="true" class="sortableItem"><span style="float:left;font-size:15px;color:#fff; font-weight:bold !important;" id="close" onclick="$(this).parent().remove(); return false;"><i class="fa fa-times"></i></span>&nbsp;&nbsp;&nbsp; <strong>'+optionData.text+'</strong> <input type="hidden" name="ids[]" value="'+optionData.value+'"> </li>';
            //console.log(newState);
            $("#mySortable").append(newLI);
        });
    });
</script>

<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script>
    $(document).ready(function(){
        var list = $('#mySortable'),
            updatePosition = function() {
                list.children().each(function(i, e){
                //$(this).children('input[type="hidden"]').val(++i);
                });
            };

        list.sortable({
            placeholder: "ui-state-highlight",
            update: updatePosition
        });
    });
    @if(!empty($item->slug) && $item->type !="Movie" &&  $item->type !="Series" &&  $item->type !="Shows")
        setTimeout(function() {
            $.each({!! json_encode($item->slug) !!}, function(index, option) {
                if (!selectedOptionsObject.hasOwnProperty(option.id)) {
                    $('#selectOptions').append($('<option>', {
                        value: option.id,
                        text: option.name,
                        selected: true
                    }));
                }
            });

            $('#selectOptions').trigger('change');
        }, 1000);
    @endif

    @if(!empty($item->slug) && ($item->type == "Movie" || $item->type =="Series" || $item->type =="Shows"))
    setTimeout(function() {
        var selectedIds = {!! json_encode(array_column($item->slug, 'id')) !!};
        $('#selectOptions').val(selectedIds);
        $('#selectOptions').trigger('change');
    }, 1000);
@endif
</script>


@endpush
