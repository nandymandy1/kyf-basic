@extends('layouts.app')

@section('content')

<div class="container">
@if(Auth::user()->job != 'master')
  @if($today)
    <div class="row">
      <div class="col-md-6">
        <a href="/factory/cuttinginput" class="btn btn-md btn-primary">Add Today's Data</a>
      </div>
    </div>
  @endif
@endif
  <h2>Cutting Dashboard</h2>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Target Vs Actual
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-blue">
          Pieces Sent to Sewing or Embroidery
        </div>
        <div class="card-body">
          <canvas id="bar_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-green">
          Cutting Department Strength
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-light-blue">
          Cutting WIP
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

  var cqty = [];
  var date = [];
  var psew = [];
  var pemb = [];
  var fout = [];
  var pcut = [];
  var tpeople = [];
  var wip = [];

  var t_cqty = [];
  var t_date = [];
  var t_psew = [];
  var t_pemb = [];
  var t_fout = [];
  var t_pcut = [];
  var t_tpeople = [];
  var t_wip = [];



  @foreach ($reports as $report)
    t_cqty.push({{ $report->cqty }});
    t_date.push("{{ date('d-M', strtotime($report->created_at)) }}");
    t_psew.push({{ $report->psew }});
    t_pemb.push({{ $report->pemb }});
    t_fout.push({{ $report->fout }});
    t_pcut.push({{ $report->pcut }});
    t_tpeople.push({{ $report->tpeople }});
    t_wip.push({{ $report->pcut - $report->psew }});
  @endforeach

  for(var i= t_date.length-1 ; i >= 0; i--){
    cqty.push(t_cqty[i]);
    date.push(t_date[i]);
    psew.push(t_psew[i]);
    pemb.push(t_pemb[i]);
    fout.push(t_fout[i]);
    pcut.push(t_pcut[i]);
    tpeople.push(t_tpeople[i]);
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
                    label: "Total Cut Quantity",
                    data: cqty,
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, {
                        label: "Pieces Cut",
                        data: pcut,
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
                    label: "Cutting Department Strength",
                    data: tpeople,
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
                    ticks:{min:0}
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
                    label: "Pieces Sent for Sewing",
                    data: psew,
                    backgroundColor: 'rgba(0, 188, 212, 0.8)'
                }, {
                        label: "Pieces Sent for Embroidery",
                        data: pemb,
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
                  label: "Cutting WIP",
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
