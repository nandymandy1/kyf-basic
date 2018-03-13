@extends('layouts.app')

@section('content')

<div class="container">
  @if (Auth::user()->job != 'master')
    @if($today)
      <div class="row">
        <div class="col-md-6">
          <a href="/factory/sewinginput" class="btn btn-md btn-primary">Add Today's Data</a>
        </div>
      </div>
    @endif
  @endif
  <br>
  <h2>General Dashboard</h2>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-blue">
          Overtime In Departments
        </div>
        <div class="card-body">
          <canvas id="line_chart" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-red">
          Absenteeism
        </div>
        <div class="card-body">
          <canvas id="abs" height="150"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-light-green">
          Man Machine Ratio
        </div>
        <div class="card-body">
          <canvas id="linep" height="150"></canvas>
        </div>
      </div>
    </div>

    <!--<div class="col-md-6 mb-5">
      <div class="card">
        <div class="card-header">
          Cutting Department WIP
        </div>
        <div class="card-body">
          <canvas id="pie_chart" height="150"></canvas>
        </div>
      </div>
    </div>-->

  </div>
</div>


@endsection

@section('scripts')
  <script src="{{ asset('./plugins/chartjs/Chart.min.js')}}" charset="utf-8"></script>
  <script type="text/javascript">
  $(function () {
      new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
      // new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
      new Chart(document.getElementById("linep").getContext("2d"), getChartJs('linep'));
      // new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('linewip'));
      new Chart(document.getElementById("abs").getContext("2d"), getChartJs('abs'));
  });
  var t_payrole = [];
  var t_ppeople = [];
  var t_cpeople = [];
  var t_ocut = [];
  var t_osew = [];
  var t_ofin = [];
  var t_date = [];
  var t_abs = [];
  var t_twf = [];
  var payrole = [];
  var ppeople = [];
  var cpeople = [];
  var ocut = [];
  var osew = [];
  var ofin = [];
  var date = [];
  var abs = [];
  var twf = [];
  var abse = [];
  var tabse = [];
  var mmr = [];
  var kopr = [];
  var tkopr = [];
  var sopr = [];
  var tsopr = [];
  var topr = [];
  var opr = [];
  var tdm = [];
  var dm = [];
  @foreach ($reports as $report)
  t_payrole.push({{ $report->payrole }});
  t_ppeople.push({{ $report->ppeople }});
  t_cpeople.push({{ $report->cpeople }});
  t_ocut.push({{ $report->ocut }});
  t_osew.push({{ $report->osew }});
  t_ofin.push({{ $report->ofin }});
  t_abs.push({{ $report->abs }});
  t_twf.push({{ $report->twf }});
  tabse.push((({{ $report->abs }})/({{ $report->twf }})*100).toFixed(2));
  t_date.push("{{ date('d-M', strtotime($report->created_at)) }}");
  @endforeach
  @foreach ($mmr as $m)
  tdm.push("{{ date('d-M', strtotime($m->created_at)) }}");
  tkopr.push({{ $m->kopr }});
  tsopr.push({{ $m->sopr }});
  topr.push({{ $m->kopr + $m->sopr }});
  @endforeach
for(var i= t_date.length-1 ; i >= 0; i--){
  date.push(t_date[i]);
  payrole.push(t_payrole[i]);
  ppeople.push(t_ppeople[i]);
  cpeople.push(t_cpeople[i]);
  twf.push(t_twf[i]);
  ocut.push(t_ocut[i]);
  osew.push(t_osew[i]);
  ofin.push(t_ofin[i]);
  abs.push(t_abs[i]);
  abse.push(tabse[i]);
}
for(var j=tdm.length-1; j >=0; j--){
  kopr.push(tkopr[j]);
  dm.push(tdm[j]);
  sopr.push(tsopr[j]);
  opr.push(topr[j]);
}

 for(var k = twf.length-1; k >= 0; k--){
   mmr.push(((twf[k]/(kopr[k]+ sopr[k]))).toFixed(2));
 }
 

  function getChartJs(type) {
    var config = null;
    if (type === 'line') {
        config = {
            type: 'bar',
            data: {
                labels: date,
                datasets: [{
                    label: "Cutting",
                    data: ocut,
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, {
                        label: "Sewing",
                        data: osew,
                        borderColor: 'rgba(233, 30, 99, 0.75)',
                        backgroundColor: 'rgba(233, 30, 99, 0.3)',
                        pointBorderColor: 'rgba(233, 30, 99, 0)',
                        pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
                        pointBorderWidth: 1
                    },
                    {
                      label: "Finishing",
                      data: ofin,
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
                  yAxes:[{
                    ticks:{min:0},
                    scaleLabel:{
                      display:true,
                      labelString:'Overtime in Hrs.'
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
                labels: dm,
                datasets: [{
                    label: "Man Machine Ratio",
                    data: mmr,
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
                      labelString:'MMR'
                    }
                  }]
                }
            }
        }
    }
    else if (type === 'abs') {
        config = {
            type: 'line',
            data: {
                labels: date,
                datasets: [{
                    label: "Absentism",
                    data: abse,
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
                      labelString:'Absenteeism%'
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
    /*
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
    */
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
