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

  <div class="row">
    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Sewing vs Packed
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Fed into Finishing vs Finished Goods
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Finished Goods
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
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
  t_date.push("{{ date('d-m-Y', strtotime($report->created_at)) }}");
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
                  yAxes:[{ticks:{min:0}}]
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
                    label: "Pieces Pakced",
                    data: feed,
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
                }, {
                        label: "Pieces Packed",
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
                yAxes:[{ticks:{min:0}}]
              }
          }
        }
    }

    return config;
}

  </script>

@endsection
