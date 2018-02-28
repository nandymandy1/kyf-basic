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

  <div class="row">
    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Target Vs Actual Vs Sent for Washingor or Finishing
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Monthly Production
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Sewing Efficiency
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Cutting Department WIP
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
  t_income.push({{ $report->income }});
  t_sopr.push({{ $report->sopr }});
  t_kopr.push({{ $report->kopr }});
  t_prod.push({{ $report->prod }});
  t_target.push({{ $report->target }});
  t_actual.push({{ $report->actual }});
  t_outcome.push({{ $report->outcome }});
  t_sam.push({{ $report->sam }});
  t_date.push("{{ date('d-m-Y', strtotime($report->created_at)) }}");
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
  wip.push(t_wip[i]);
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
                      borderColor: 'rgba(52, 152, 219, 0.75)',
                      backgroundColor: 'rgba(93, 173, 226, 0.3)',
                      pointBorderColor: 'rgba(93, 173, 226, 0)',
                      pointBackgroundColor: 'rgba(93, 173, 226, 0.9)',
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
                  yAxes:[{ticks:{min:0}}]
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
                  yAxes:[{ticks:{min:0}}]
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
                  yAxes:[{ticks:{min:0}}]
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
                yAxes:[{ticks:{min:0}}]
              }
          }
        }
    }

    return config;

}

function removeDuplicates(arr){
    let unique_array = []
    for(let i = 0;i < arr.length; i++){
        if(unique_array.indexOf(arr[i]) == -1){
            unique_array.push(arr[i])
        }
    }
    return unique_array
}

  </script>

@endsection
