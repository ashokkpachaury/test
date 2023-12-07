@extends("admin.admin_app")



@section("content")



<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">

      google.charts.load('current', {'packages':['bar']});

      google.charts.setOnLoadCallback(drawChart);



      function drawChart() {

        var data = google.visualization.arrayToDataTable([

          ["{{trans('words.this_year')}}", @foreach($plan_list as $plan_data) '{{$plan_data->plan_name}}', @endforeach],

          

          <?php for ($i = 1; $i <= 12; $i++)

            {

                //$month_name =date("M", strtotime("$i/12/10"));

                $month_name_full =date("F", strtotime("$i/12/10"));
                

                ?>

            

            ['<?php echo $month_name_full;?>', @foreach($plan_list as $plan_data_obj) <?php echo plan_count_by_month($plan_data_obj->productId,$month_name_full);?>,@endforeach],

            

            <?php  }?>

                     

        ]);



        var options = {

          chart: {

            title: "{{trans('words.users_plan_statastics')}}",

            subtitle: "{{trans('words.current_year')}}",

          }

        };



        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));



        chart.draw(data, google.charts.Bar.convertOptions(options));

      }

    </script>



    



<div class="content-page">

      <div class="content">

        <div class="container-fluid">
            
          

          @if(Auth::User()->usertype=="Admin")  

          <div class="row">
                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                            <h2 class="text-custom" >$<span data-plugin="counterup">{{$daily_amount}}</span></h2>

                                <h5>{{trans('words.daily_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                            <h2 class="text-custom" >$<span data-plugin="counterup">{{$weekly_amount}}</span></h2>

                                <h5>{{trans('words.weekly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                            <h2 class="text-custom" >$<span data-plugin="counterup">{{$monthly_amount}}</span></h2>

                                <h5>{{trans('words.monthly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                            <h2 class="text-custom" >$<span data-plugin="counterup">{{$yearly_amount}}</span></h2>

                                <h5>{{trans('words.yearly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>
          </div>

          <div class="card-box table-responsive">

                 <div class="row">

                    <div class="col-xl-12" id="columnchart_material" style="height: 400px;"></div>

                </div> 

          </div>

          @else
                <div class="row">

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                                <h2 class="text-custom" data-plugin="counterup">{{$daily_amount}}</h2>

                                <h5>{{trans('words.daily_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                                <h2 class="text-pink" data-plugin="counterup">{{$weekly_amount}}</h2>

                                <h5>{{trans('words.weekly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                                <h2 class="text-warning" data-plugin="counterup">{{$monthly_amount}}</h2>

                                <h5>{{trans('words.monthly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-3 col-md-6">

                        <div class="card-box widget-user">

                            <div class="text-center">

                                <h2 class="text-success" data-plugin="counterup">{{$yearly_amount}}</h2>

                                <h5>{{trans('words.yearly_revenue')}}</h5>

                            </div>

                        </div>

                    </div>

                </div>    
          @endif 
        </div>
      </div>
      @include("admin.copyright") 
    </div>
@endsection