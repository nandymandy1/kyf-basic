@extends('layouts.app')

@section('content')

<div class="container">
  @if(Auth::user()->job !='master')
    @if($today)
      <div class="row">
        <div class="col-md-6">
          <a href="/factory/qualityinput" class="btn btn-md btn-primary">Add Today's Data</a>
        </div>
      </div>
    @endif
  @endif
  <br>
  <h2>Quality Dashboard</h2>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-blue">
          Daily DHU
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Inspected Vs Failed
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')
  <script type="text/javascript">
  $(function () {
      new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
      new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
  });


var t_inspected =[];
var t_failed = [];
var t_dhu = [];
var t_date = [];
var inspected = [];
var failed = [];
var date = [];
var dhu = [];


  @foreach ($reports as $report)
    t_date.push("{{ date('d-M', strtotime($report->created_at)) }}");
    t_inspected.push({{ $report->inspected }});
    t_failed.push({{ $report->failed }});
    t_dhu.push(({{ (($report->failed)/($report->inspected))*100 }}).toFixed(2));
  @endforeach

  for(var i = t_date.length-1; i >= 0; i--){
    date.push(t_date[i]);
    inspected.push(t_inspected[i]);
    failed.push(t_failed[i]);
    dhu.push(t_dhu[i]);
  }

  function getChartJs(type) {
    var config = null;

    if (type === 'line') {
        config = {
            type: 'line',
            data: {
                labels: date,
                datasets: [{
                    label: "DHU",
                    data: dhu,
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                  display:true,
                  position:'bottom'
                },
                scales: {
                  yAxes:[{
                    ticks:{min:0},
                    scaleLabel:{
                      display:true,
                      labelString:'DHU%'
                    }
                  }],
                  xAxes:[{
                    scaleLabel:{
                      display:true,
                      labelString:'Dates'
                    }
                  }]
                }
            }
        }
    }

    else if (type === 'bar') {
        config = {
            type: 'bar',
            data: {
                labels: date,
                datasets: [{
                    label: "Inspected Quantity",
                    data: inspected,
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
                }, {
                        label: "Failed Quantity",
                        data: failed,
                        backgroundColor: 'rgba(233, 30, 99, 0.8)'
                    }]
            },
            options: {
                responsive: true,
                legend: {
                  display:true,
                  position:'bottom'
                },
                scales: {
                  yAxes:[{
                    ticks:{min:0},
                    scaleLabel:{
                      display:true,
                      labelString:'Pieces'
                    }
                  }],
                  xAxes:[{
                    scaleLabel:{
                      display:true,
                      labelString:'Dates'
                    }
                  }]
                }
            }
        }
    }

    return config;
}

  </script>

@endsection
