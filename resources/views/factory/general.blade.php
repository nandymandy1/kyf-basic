@extends('layouts.app')

@section('content')

<div class="container">
  @if (Auth::user()->job != 'master')
    @if($today)
      <div class="row">
        <div class="col-md-6">
          <a href="/factory/generalinput" class="btn btn-md btn-primary">Add Today's Data</a>
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
          <canvas id="overtime" height="150"></canvas>
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
          <canvas id="mmr" height="150"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
  <script src="{{ asset('./js/moment.js')}}" charset="utf-8"></script>
  <script type="text/javascript">
    var app = new Vue({
      el: '#app',
      data(){
        return {
          factory_id: {{Auth::user()->factory_id}},
          report:{},
          mmr:{},
          temp:{},
          errors:{}
        }
      },
      methods:{
        fetchReports(){
          axios.get(`/reports/general/${this.factory_id}`)
          .then(
            (response) => {
              this.report = this.temp = response.data.reports
              this.mmr = response.data.mmr
              console.log(this.report);
              console.log(this.mmr);
              osew = [];
              ofin = [];
              ocut = [];
              dates = [];
              absr = [];
              payrole = [];
              ppeople = [];
              twf = [];
              sopr = [];
              kopr = [];
              mmratio = [];
              for(i = this.report.length -1; i >=0 ; i--){
                dates.push(moment(new Date(this.report[i].created_at)).format("D-MMM"));
                osew.push(parseFloat(this.report[i].osew));
                ofin.push(parseFloat(this.report[i].ofin));
                ocut.push(parseFloat(this.report[i].ocut));
                abs = parseInt(this.report[i].abs);
                twf1 = parseInt(this.report[i].twf);
                arate = ((abs/twf1)*100).toFixed(2);
                absr.push(arate)
                twf.push(parseInt(this.report[i].twf))
                payrole.push(parseFloat(this.report[i].payrole));
                ppeople.push(parseFloat(this.report[i].ppeople));
              }

              for(i = this.mmr.length -1; i >=0 ; i--){
                sopr.push(parseInt(this.mmr[i].kopr));
                kopr.push(parseInt(this.mmr[i].sopr));
              }
              for(i = 0; i < sopr.length; i++){
                mmratio.push((twf[i]/(sopr[i]+kopr[i])).toFixed(2));
              }

              var mmrChart = document.getElementById("mmr").getContext('2d');
              var myMmr = new Chart(mmrChart, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: "Man Machine Ratio",
                        data: mmratio,
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
              });

              var absChart = document.getElementById("abs").getContext('2d');
              var myAbs = new Chart(absChart, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: "Absentism",
                        data: absr,
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
              })

              var overChart = document.getElementById("overtime").getContext('2d');
              var myOver = new Chart(overChart, {
                type: 'bar',
                data: {
                    labels: dates,
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
              })


            });
        }
      },
      created(){
        this.fetchReports();
      }
    });
  </script>
@endsection
