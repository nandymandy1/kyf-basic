@extends('layouts.app')

@section('content')

<div class="container">
@if (Auth::user()->job != 'master')
  @if($today)
    <div class="row">
      <div class="col-md-6">
        <a href="/factory/finishinginput" class="btn btn-md btn-primary">Add Today's Data</a>
      </div>
    </div>
  @endif
@endif
  <br>
  <h2>Finishing Dashboard</h2>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-blue">
          Sewing vs Packed
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Fed into Finishing vs Finished Goods
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-light-green">
          Finished Goods
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-light-blue">
          Finishing WIP
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


var t_feed =[];
var t_pkd = [];
var t_income = [];
var t_wip = [];
var t_date = [];
var feed = [];
var pkd = [];
var date = [];
var income = [];
var wip = [];


  @foreach ($reports as $report)
  t_date.push("{{ date('d-M', strtotime($report->created_at)) }}");
  t_pkd.push({{ $report->pkd }});
  t_income.push({{ $report->income }});
  t_feed.push({{ $report->feed }});
  t_wip.push({{ $report->income- $report->pkd }})
  @endforeach

  for(var i = t_date.length-1; i >= 0; i--){
    date.push(t_date[i]);
    pkd.push(t_pkd[i]);
    income.push(t_income[i]);
    feed.push(t_feed[i]);
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
                    label: "Total Packed Quantity",
                    data: pkd,
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, {
                        label: "Pieces Recieved From Sewing",
                        data: income,
                        borderColor: 'rgba(233, 30, 99, 0.75)',
                        backgroundColor: 'rgba(233, 30, 99, 0.3)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
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
                    label: "Finshed Goods",
                    data: pkd,
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

    else if (type === 'bar') {
        config = {
            type: 'bar',
            data: {
                labels: date,
                datasets: [{
                    label: "Pieces fed into finishing",
                    data: feed,
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
                }, {
                        label: "Packed pieces",
                        data: pkd,
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

    else if (type === 'linewip') {
        config = {
          type: 'bar',
          data: {
              labels: date,
              datasets: [{
                  label: "Finishing WIP",
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
