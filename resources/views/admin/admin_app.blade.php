<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{getcong('site_name')}} Admin">
  <meta name="author" content="ReDiscover Tech, LLC">
  <link rel="shortcut icon" href="{{ URL::asset('upload/source/'.getcong('site_favicon')) }}">
  <title>{{getcong('site_name')}} Admin</title>


  @if(getcong('external_css_js')=="CDN")

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
     <link href="{{ URL::asset('admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

     <script src="{{ URL::asset('admin_assets/js/modernizr.min.js') }}"></script>

  @else
     <link href="{{ URL::asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ URL::asset('admin_assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
     <link href="{{ URL::asset('admin_assets/css/style.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ URL::asset('admin_assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

     <script src="{{ URL::asset('admin_assets/js/modernizr.min.js') }}"></script>
  @endif
  @yield('styles')
  <!-- App css -->




</head>
  <body class="fixed-left">
    <div id="wrapper">

    @include("admin.topbar")

    @include("admin.sidebar")

    @yield("content")

    </div>

  <!-- jQuery  -->

  @if(getcong('external_css_js')=="CDN")
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="{{ URL::asset('admin_assets/js/popper.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js"></script>

  @else
  <script src="{{ URL::asset('admin_assets/js/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/popper.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/bootstrap.min.js') }}"></script>
  @endif


  <script src="{{ URL::asset('admin_assets/js/detect.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/fastclick.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/jquery.blockUI.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/waves.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/jquery.nicescroll.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/jquery.slimscroll.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/js/jquery.scrollTo.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/plugins/tinymce/tinymce.min.js') }}"></script>


  <script src="{{ URL::asset('admin_assets/plugins/jquery-knob/jquery.knob.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

  <script type="text/javascript" src="{{ URL::asset('admin_assets/plugins/multiselect/js/jquery.multi-select.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

  @if(classActivePath('dashboard'))
  <!-- Counter Up  -->
  <script src="{{ URL::asset('admin_assets/plugins/waypoints/jquery.waypoints.min.js') }}"></script>
  <script src="{{ URL::asset('admin_assets/plugins/counterup/jquery.counterup.min.js') }}"></script>
  @endif

  <!-- App js -->
   <script src="{{ URL::asset('admin_assets/js/jquery.core.js') }}"></script>
   <script src="{{ URL::asset('admin_assets/js/jquery.app.js') }}"></script>

    @stack('scripts')
<script type="text/javascript">
    $(document).ready(function () {
      if ($("#elm1").length > 0) {
        tinymce.init({
          selector: "textarea#elm1",
          theme: "modern",
          height: 300,
          plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor",
          style_formats: [
            { title: 'Bold text', inline: 'b' },
            { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
            { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
            { title: 'Example 1', inline: 'span', classes: 'example1' },
            { title: 'Example 2', inline: 'span', classes: 'example2' },
            { title: 'Table styles' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
          ]
        });
      }
    });
  </script>
<script>
jQuery(document).ready(function () {
  $(".select2, .select3, .select4, .select5, .select6, .select7, .select8, .select9").select2();
  $(".select2-limiting, .select3-limiting, .select4-limiting, .select5-limiting, .select6-limiting, .select7-limiting, .select8-limiting").select2({
    maximumSelectionLength: 2
  });
});

jQuery('#datepicker-autoclose').datepicker({
                autoclose: true,
                todayHighlight: true
            });
</script>

<script>
 $(function(){
    // bind change event to select
    $('#movie_language_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Series
    $('#series_language_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Season
    $('#season_series_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Episodes
    $('#episodes_series_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Sports
    $('#sports_cat_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Live TV
    $('#tv_cat_id').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Users
    $('#plan_select').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Transactions
    $('#gateway_select').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Transactions
    $('#transactions_select').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    // subscription
    $('#subscription_select').on('change', function () {
        var url = $(this).val(); // get selected value

        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

  });

 //Add edit Movie
$("#video_type").change(function(){
   var type=$("#video_type").val();
      //alert(type);
       if(type=="URL")
       {
         $("#local_id").hide();
         $("#url_id").show();
         $("#embed_id").hide();
         $("#hls_id").hide();
         $("#dash_id").hide();
         $("#movie_poster_id").show();
       }
      else if(type=="Embed")
       {
         $("#local_id").hide();
         $("#url_id").hide();
         $("#embed_id").show();
         $("#hls_id").hide();
         $("#dash_id").hide();

         $("#movie_poster_id").hide();
       }
       else if(type=="HLS")
       {
         $("#local_id").hide();
         $("#url_id").hide();
         $("#embed_id").hide();
         $("#hls_id").show();
         $("#dash_id").hide();

         $("#movie_poster_id").show();
       }
       else if(type=="DASH")
       {
         $("#local_id").hide();
         $("#url_id").hide();
         $("#embed_id").hide();
         $("#hls_id").hide();
         $("#dash_id").show();

         $("#movie_poster_id").show();
       }
      else
      {
        $("#local_id").show();
        $("#url_id").hide();
        $("#embed_id").hide();
        $("#hls_id").hide();
        $("#dash_id").hide();

        $("#movie_poster_id").show();

        @if(isset($movie->id) OR isset($episode_info->id) OR isset($video_info->id))

          $("#video_url").val("");
          $("#video_url_local_480").val("");
          $("#video_url_local_720").val("");
          $("#video_url_local_1080").val("");

        @endif
      }

 });

$("#admin_usertype").change(function(){
   var type=$("#admin_usertype").val();

       if(type=="Admin")
       {
          $("#master_admin_id").show();
          $("#sub_admin_id").hide();
       }
       else
       {
          $("#master_admin_id").hide();
          $("#sub_admin_id").show();
       }

 });

$("#channel_url_type").change(function(){
   var type=$("#channel_url_type").val();

       if(type=="embed")
       {
          $("#hls_stream_id").hide();
          $("#dash_stream_id").hide();
          $("#embed_stream_id").show();
          $("#youtube_stream_id").hide();

          $("#live_url_id").hide();
          $("#live_embed_id").show();

          $("#channel_url_youtube_id").hide();
          $("#live_url_dash_id").hide();
       }
       else if(type=="youtube")
       {
          $("#hls_stream_id").hide();
          $("#dash_stream_id").hide();
          $("#embed_stream_id").hide();
          $("#youtube_stream_id").show();

          $("#live_url_id").hide();
          $("#live_embed_id").hide();

          $("#channel_url_youtube_id").show();
          $("#live_url_dash_id").hide();
       }
       else if(type=="dash")
       {
          $("#hls_stream_id").hide();
          $("#dash_stream_id").show();
          $("#embed_stream_id").hide();
          $("#youtube_stream_id").hide();

          $("#live_url_id").hide();
          $("#live_embed_id").hide();

          $("#channel_url_youtube_id").hide();
          $("#live_url_dash_id").show();
       }
       else
       {
          $("#hls_stream_id").show();
          $("#dash_stream_id").hide();
          $("#embed_stream_id").hide();
          $("#youtube_stream_id").hide();

          $("#live_url_id").show();
          $("#live_embed_id").hide();

          $("#channel_url_youtube_id").hide();
          $("#live_url_dash_id").hide();
       }

 });
</script>


<script type="text/javascript">
  $("#banner_ad_type").change(function(){
   var type=$("#banner_ad_type").val();
      //alert(type);
       if(type=="Facebook")
       {
         $("#admob_banner_id").hide();
         $("#fb_banner_id").show();
       }
       else
       {
          $("#admob_banner_id").show();
          $("#fb_banner_id").hide();
       }
  });

  $("#interstitial_ad_type").change(function(){
   var type=$("#interstitial_ad_type").val();
      //alert(type);
       if(type=="Facebook")
       {
         $("#admob_interstitial_id").hide();
         $("#fb1_interstitial_id").show();
       }
       else
       {
          $("#admob_interstitial_id").show();
          $("#fb1_interstitial_id").hide();
       }
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e) {

     $("#episode_series_id").change(function(){
         var series_id=$("#episode_series_id").val();
      $.ajax({
      type: "GET",
       url: "{{ URL::to('admin/ajax_get_season') }}/"+series_id,
       //data: "cat=" + cat,
       success: function(result){

           $("#episode_season_id option").remove();

           $("#episode_season_id").html(result);

        }
      });

         });
  });
</script>
<script type="text/javascript">

  //$("select").select2();

// $("select").on("select2:select", function (evt) {
//   var element = evt.params.data.element;
//   var $element = $(element);

//   $element.detach();
//   $(this).append($element);
//   $(this).trigger("change");
// });

</script>


<script type="text/javascript">
     jQuery(document).ready(function(){
    $(document).on('click', '#import_movie_btn', function() {
        //$('#result').html('');
        var from = $("#from").val();
        var id = $("#imdb_id_title").val();
        if (from != '' && id != '') {
            $.ajax({
                type: 'GET',
                url: "{{ URL::to('admin/find_imdb_movie') }}",
                data: "id=" + encodeURIComponent(id) + "&from=" + encodeURIComponent(from),
                dataType: 'json',
                beforeSend: function() {
                    $("#import_movie_btn").html('Fetching...');
                },
                success: function(response) {
                    var imdb_status     = response.imdb_status;
                    var imdbid          = response.imdbid;
                    var imdb_rating          = response.imdb_rating;
                    var imdb_votes          = response.imdb_votes;

                    var title           = response.title;
                    var plot           = response.plot;
                    var runtime           = response.runtime;
                    var language           = response.language;
                    var genre           = response.genre;
                    var released           = response.released;

                    var thumbnail           = response.thumbnail;
                    var thumbnail_name           = response.thumbnail_name;


                    if (imdb_status == 'success') {

                        $("#imdb_id").val(imdbid);
                        $("#imdb_rating").val(imdb_rating);
                        $("#imdb_votes").val(imdb_votes);

                        $("#movie_language").val(language).trigger('change');
                        $("#movie_genre_id").val(genre).trigger('change');

                        $("#video_title").val(title);
                        tinyMCE.activeEditor.setContent(plot);
                        $("#duration").val(runtime);
                        $("#datepicker-autoclose").val(released);

                        $('#display_thumb_img').show();
                        $('#video_image_thumb').val(thumbnail_name);
                        $('#thumb_link').val(thumbnail);
                        $('#imdb_thumb_image').attr('src', thumbnail);

                        $('#result').html('<div class="alert alert-success alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data imported successfully.</div>');
                        $('#import_movie_btn').html('{{trans('words.fetch')}}');

                    } else {

                        $('#result').html('<div class="alert alert-danger alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>No data found in IMDb database..</div>');
                        $('#import_movie_btn').html('{{trans('words.fetch')}}');
                    }
                }
            });
        }
        else {
            alert('Please input IMDb ID');
        }
    });
});
</script>

<script type="text/javascript">
     jQuery(document).ready(function(){
    $(document).on('click', '#import_show_btn', function() {
        //$('#result').html('');


        var from = $("#from").val();
        var id = $("#imdb_id_title").val();
        if (from != '' && id != '') {
            $.ajax({
                type: 'GET',
                url: "{{ URL::to('admin/find_imdb_show') }}",
                data: "id=" + encodeURIComponent(id) + "&from=" + encodeURIComponent(from),
                dataType: 'json',
                beforeSend: function() {
                    $("#import_show_btn").html('Fetching...');
                },
                success: function(response) {
                    var imdb_status     = response.imdb_status;
                    var imdbid          = response.imdbid;
                    var imdb_rating          = response.imdb_rating;
                    var imdb_votes          = response.imdb_votes;

                    var title           = response.title;
                    var plot           = response.plot;
                    var runtime           = response.runtime;
                    var language           = response.language;
                    var genre           = response.genre;
                    var released           = response.released;

                    var thumbnail           = response.thumbnail;
                    var thumbnail_name           = response.thumbnail_name;


                    if (imdb_status == 'success') {

                        $("#imdb_id").val(imdbid);
                        $("#imdb_rating").val(imdb_rating);
                        $("#imdb_votes").val(imdb_votes);

                        $("#show_language").val(language).trigger('change');
                        $("#show_genre_id").val(genre).trigger('change');

                        $("#show_name").val(title);
                        $("#show_info").val(plot);
                        //tinyMCE.activeEditor.setContent(plot);


                        $('#result').html('<div class="alert alert-success alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data imported successfully.</div>');
                        $('#import_show_btn').html('{{trans('words.fetch')}}');

                    } else {

                        $('#result').html('<div class="alert alert-danger alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>No data found in IMDb database..</div>');
                        $('#import_show_btn').html('{{trans('words.fetch')}}');
                    }
                }
            });
        }
        else {
            alert('Please input IMDb ID');
        }
    });
});
</script>

<script type="text/javascript">
     jQuery(document).ready(function(){
    $(document).on('click', '#import_episode_btn', function() {
        //$('#result').html('');


        var from = $("#from").val();
        var id = $("#imdb_id_title").val();
        if (from != '' && id != '') {
            $.ajax({
                type: 'GET',
                url: "{{ URL::to('admin/find_imdb_episode') }}",
                data: "id=" + encodeURIComponent(id) + "&from=" + encodeURIComponent(from),
                dataType: 'json',
                beforeSend: function() {
                    $("#import_episode_btn").html('Fetching...');
                },
                success: function(response) {
                    var imdb_status     = response.imdb_status;
                    var imdbid          = response.imdbid;
                    var imdb_rating          = response.imdb_rating;
                    var imdb_votes          = response.imdb_votes;

                    var title           = response.title;
                    var plot           = response.plot;
                    var runtime           = response.runtime;
                    var released           = response.released;


                    if (imdb_status == 'success') {

                        $("#imdb_id").val(imdbid);
                        $("#imdb_rating").val(imdb_rating);
                        $("#imdb_votes").val(imdb_votes);


                        $("#episode_title").val(title);
                        //$("#show_info").val(plot);
                        tinyMCE.activeEditor.setContent(plot);

                        $("#duration").val(runtime);
                        $("#datepicker-autoclose").val(released);


                        $('#result').html('<div class="alert alert-success alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Data imported successfully.</div>');
                        $('#import_episode_btn').html('{{trans('words.fetch')}}');

                    } else {

                        $('#result').html('<div class="alert alert-danger alert-dismissable m-t-15"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>No data found in IMDb database..</div>');
                        $('#import_episode_btn').html('{{trans('words.fetch')}}');
                    }
                }
            });
        }
        else {
            alert('Please input IMDb ID');
        }
    });
});
</script>
<script type="text/javascript">

$("#notification_type").change(function(){
   var type=$("#notification_type").val();

       if(type=="Movies")
       {
          $("#movie_list_id").show();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }
       else if(type=="Series" || type=="Shows")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").show();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }
       else if(type=="Sports")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").show();
          $("#live_tv_list_id").hide();
       }
       else if(type=="LiveTV")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").show();
       }
       else
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }

 });

</script>
<script type="text/javascript">

$("#slider_type").change(function(){
   var type=$("#slider_type").val();

       if(type=="Movies")
       {
          $("#movie_list_id").show();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }
       else if(type=="Series" || type=="Shows")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").show();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }
       else if(type=="Sports")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").show();
          $("#live_tv_list_id").hide();
       }
       else if(type=="LiveTV")
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").show();
       }
       else
       {
          $("#movie_list_id").hide();
          $("#show_list_id").hide();
          $("#sports_list_id").hide();
          $("#live_tv_list_id").hide();
       }

 });

</script>



<!-- Include jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
    jQuery(document).ready(function($) {
        $("#sortable-table tbody").sortable({
            items: "tr",
            cursor: "move",
            handle: ".drag-handle",
            opacity: 0.6,
           // update: function(event, ui) {



                // var sortedItemIds = {};
                // $(this).find("tr").each(function(index) {
                //     var itemId = $(this).data("item-id");
                //     sortedItemIds[itemId] = index + 1;
                // });


                // $.ajax({
                //     method: "GET",
                //     url: "home-section-update-order",
                //     data: { sortedItemIds: sortedItemIds },
                //     success: function(response) {
                //         console.log("Order updated successfully");
                //     },
                //     error: function(xhr, status, error) {
                //         console.error(error);
                //     }
                // });
           // }
        });






    });


        function displayReOrder() {
            $(".order").toggleClass("dnone");

        }

        function displayDone() {
            $(".order").toggleClass("dnone");
            $("#reorderButton").removeClass("done");
            sortedItemIds=$("input[name^='order']").serializeArray();
                $.ajax({
                    method: "GET",
                    url: "home-section-update-order",
                    data: sortedItemIds ,
                    success: function(response) {
                        console.log("Order saved successfully");
                        location.reload();

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

        }


        $("#reorderButton").on("click", function() {
            if ($(this).hasClass("done")) {
                displayDone(); // Call the displayDone function
            } else {
                displayReOrder(); // Call the displayReOrder function
            }
            $(this).toggleClass("done");
            $(this).text("Save Order");
            $(this).attr("data-original-title","Save Order");

        });

        function validateNumericInput(input) {
    // Remove any non-numeric characters
    input.value = input.value.replace(/[^0-9]/g, '');

    // You can add additional validation logic here, such as checking for a valid range.
}





</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js"></script>

<script>
function confirmClearFeed() {
    event.preventDefault(); // Prevent the default link behavior

    Swal.fire({
        title: 'Clear Feed',
        text: 'Are you sure you want to clear the feed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ URL::to('clear-feed') }}"; // Redirect if confirmed
        }
    });
}
</script>
    </body>
</html>
