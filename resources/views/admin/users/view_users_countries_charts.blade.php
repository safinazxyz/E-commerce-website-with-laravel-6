@extends('layouts.adminLayout.admin_design')
@section('content')
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Registered Users Countries Count"
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    yValueFormatString: "##0.00\"%\"",
                    indexLabel: "{label} {y}",
                    dataPoints: [
                        <?php $totalcount= 0;?>
                       @foreach( $getUserCountries as $key => $country)
                            <?php $totalcount= $totalcount + $country['count'];?>
                       @endforeach
                        @foreach( $getUserCountries as $key => $country)
                        {y: ({{$country['count'] }}/{{$totalcount}})*100, label:" {{$country['country']}}" },
                        @endforeach

                    ]
                }]
            });
            chart.render();

        }
    </script>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i>
                    Home</a> <a href="#">Users Countries Charts</a> <a href="#" class="current">View Users Countries Chart</a></div>
            <h1>Users Countries Reporting</h1>
        </div>
        @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div>
        @endif
        @if(Session::has('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{!! session('flash_message_success') !!}</strong>
            </div>
        @endif
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>Users Countries Reporting</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('js/backend_js/canvasjs.min.js') }}"></script>
@endsection
