@extends('layouts.app')

@section('content')

<div class="container">
  @if(Auth::user()->job !='master')
    @if($today)
      <div class="row">
        <div class="col-md-6">
          <a href="/factory/sewinginput" class="btn btn-md btn-primary">Add Today's Data</a>
        </div>
      </div>
    @endif
  @endif
  <br>
  <h2>Sewing Dashboard</h2>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-blue">
          Target Vs Actual Vs Sent for Washing or Finishing
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Daily Production
        </div>
        <div class="card-body">
          <canvas id="bar_d" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-green">
          Monthly Production
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-teal">
          Sewing Efficiency
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Sewing Department WIP
        </div>
        <div class="card-body">
          <canvas id="pie_chart" height="150"></canvas>
        </div>
      </div>
    </div>

  </div>
</div>


@endsection

@section('scripts')
  <script src="{{ asset('./plugins/chartjs/Chart.min.js')}}" charset="utf-8"></script>
  <script type="text/javascript">
  $(function () {
      new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
      new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
      new Chart(document.getElementById("bar_d").getContext("2d"), getChartJs('bar_d'));
      new Chart(document.getElementById("linep").getContext("2d"), getChartJs('linep'));
      new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('linewip'));
  });

  var t_income = [];
  var t_sopr = [];
  var t_kopr = [];
  var t_prod = [];
  var t_target = [];
  var t_actual = [];
  var t_outcome = [];
  var t_sam = [];
  var t_date = [];
  var t_eff = [];
  var t_wip = [];

  var income = [];
  var sopr = [];
  var kopr = [];
  var prod = [];
  var target = [];
  var actual = [];
  var outcome = [];
  var sam = [];
  var date = [];
  var eff = [];
  var wip = [];
  var monthly = [];

  @foreach ($reports as $report)
  // To store all the data from the Controllers into the javascript objects
  t_income.push({{ $report->income }});
  t_sopr.push({{ $report->sopr }});
  t_kopr.push({{ $report->kopr }});
  t_prod.push({{ $report->prod }});
  t_target.push({{ $report->target }});
  t_actual.push({{ $report->actual }});
  t_outcome.push({{ $report->outcome }});
  t_sam.push({{ $report->sam }});
  t_date.push("{{ date('d-M', strtotime($report->created_at)) }}");
  t_eff.push((({{ (($report->prod)*($report->sam))/(($report->kopr + $report->sopr)*480) }})*100).toFixed(2));
  t_wip.push({{ $report->prod - $report->outcome }})
  @endforeach
  var months = ['0','Jan', 'Feb','Mar', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
  var month = [];
  var t_prod = [];
@foreach ($production as $month)
  month.push(months[{{ $month->month }}])
  t_prod.push({{$month->t_prod }});
@endforeach

for(var i= t_date.length-1 ; i >= 0; i--){
  // To store all the data from the Controllers into the javascript objects
  income.push(t_income[i]);
  sopr.push(t_sopr[i]);
  kopr.push(t_kopr[i]);
  prod.push(t_prod[i]);
  target.push(t_target[i]);
  actual.push(t_actual[i]);
  outcome.push(t_outcome[i]);
  sam.push(t_sam[i]);
  date.push(t_date[i]);
  eff.push(t_eff[i]);
}

var k = 0;
for(j = t_wip.length-1; j >= 0; j--){
  if(k == 0){
    wip.push(t_wip[j]);
  } else {
    wip.push(t_wip[j] + wip[k-1]);
  }
  k++;
}

  function getChartJs(type) {
    var config = null;

    if (type === 'line') {
        config = {
            type: 'line',
            data: {
                labels: date,
                datasets: [{
                    label: "Actual",
                    data: actual,
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, {
                        label: "Target",
                        data: target,
                        borderColor: 'rgba(233, 30, 99, 0.75)',
                        backgroundColor: 'rgba(233, 30, 99, 0.3)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
                        pointBorderWidth: 1
                    },
                    {
                      label: "Sent for Finishing or Washing",
                      data: outcome,
                      borderColor: 'rgba(34, 153, 84, 0.75)',
                      backgroundColor: 'rgba(82, 190, 128, 0.3)',
                      pointBorderColor: 'rgba(82, 190, 128, 0)',
                      pointBackgroundColor: 'rgba(34, 153, 84, 0.9)',
                      pointBorderWidth: 1
                    }
                  ]
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
    else if (type === 'linep') {
        config = {
            type: 'line',
            data: {
                labels: date,
                datasets: [{
                    label: "Efficiency",
                    data: eff,
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
                      labelString:'Efficiency%'
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
                labels: month,
                datasets: [{
                    label: "Monthly Production",
                    data: t_prod,
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
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

    else if (type === 'bar_d') {
        config = {
            type: 'bar',
            data: {
                labels: date,
                datasets: [{
                    label: "Daily Production",
                    data: actual,
                    backgroundColor: 'rgba(160, 150, 212, 0.8)'
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

    else if (type === 'linewip') {
        config = {
          type: 'bar',
          data: {
              labels: date,
              datasets: [{
                  label: "Sewing WIP",
                  data: wip,
                  backgroundColor: 'rgba(0, 188, 212, 0.8)'
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
