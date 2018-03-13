@extends('layouts.app') @section('content')
<div class="container" id="app">
  <h4>@{{ factory.name }}</h4>
  <div class="card">
    <div class="card-header bg-red">
      Cutting
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="cwip"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="tvac"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="ceffi"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-light-blue">
      Sewing
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="seffi"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="mp"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="dp"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="swip"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-green">
      Finishing
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="fwip"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="fedvpkd"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-light-blue">
      Quality
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="dhu"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="" id="invfa"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-teal">
      General
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">

            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">

            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection @section('scripts')
  <script src="{{ asset('./js/highcharts.src.js')}}" charset="utf-8"></script>
  <script src="{{ asset('./plugins/momentjs/moment.js')}}" charset="utf-8"></script>

<script type="text/javascript">
  var app = new Vue({
    el: '#app',
    data(){
      return{
        factory: {
          name: '',
          id: ''
        },
        cutting: {},
        sewing: {},
        finishing: {},
        quality: {},
        dGeneral: {},
        temp: {},
        errors: {},
        production:{},
        id: {{$req}}
      }
    },

    created(){
      this.fetchReport()
    },

    methods:{
      fetchReport() {
        axios.post(`/admin/factory/reports/${this.id}`)
        .then((response) => {
          console.log(response.data)
          this.production = response.data.production
          this.factory.name = response.data.factory[0].name
          this.factory.id = response.data.factory[0].id
          this.cutting = response.data.reports.cutting
          this.sewing = response.data.reports.sewing
          this.finishing = response.data.reports.finishing
          this.quality = response.data.reports.quality
          this.dGeneral = response.data.reports.d_general
          console.log(this.dGeneral)

          var cwip = [];
          var swip = [];
          var dates = [];
          var actualC = [];
          var targetC = [];
          var effiCut = [];
          var effiSew = [];
          var monthlyProd = [];
          var months = [];
          var production = [];
          // Finishing Variables
          var feed = [];
          var pkd = [];
          var income = [];
          var fwip = [];
          // Quality Variables
          var dhu = [];
          var insp = [];
          var failed = [];
          var passed = [];


          // For initializing the Data objects for the Carts
          var j = 0;
          for(i = this.cutting.length-1; i >= 0; i--){

            dates.push(moment(new Date(this.cutting[i].created_at)).format("D-MMM"));
            actualC.push(this.cutting[i].pcut);
            targetC.push(this.cutting[i].cqty);
            kopr = this.sewing[i].kopr;
            sopr = this.sewing[i].sopr;
            sam = this.sewing[i].sam;
            prod = this.sewing[i].prod;
            effiCut.push(parseFloat((this.cutting[i].pcut / (((kopr + sopr) * 480) / sam) * 100).toFixed(2)));
            effiSew.push(parseFloat((((prod*sam)/((kopr + sopr)*480))*100).toFixed(2)));
            production.push(prod);
            if(j == 0){
              cwip.push(this.cutting[i].pcut - this.cutting[i].psew);
              swip.push(this.sewing[i].prod - this.sewing[i].outcome);
              fwip.push(this.finishing[i].income - this.finishing[i].pkd);
            } else {
              cwip.push(this.cutting[i].pcut - this.cutting[i].psew + cwip[j-1]);
              swip.push(this.sewing[i].prod - this.sewing[i].outcome + swip[j-1]);
              fwip.push(this.finishing[i].income - this.finishing[i].pkd + fwip[j-1]);
            }
              j++;
            income.push(this.finishing[i].income);
            pkd.push(this.finishing[i].pkd);
            dhu.push(parseFloat(((this.quality[i].failed/this.quality[i].inspected)*100).toFixed(2)));
            insp.push(this.quality[i].inspected)
            failed.push(this.quality[i].failed)
            passed.push(this.quality[i].inspected - this.quality[i].failed)

          }

          for(i=0; i < this.production.length; i++){
            monthlyProd.push(parseInt(this.production[i].t_prod))
            months.push(moment(this.production[i].month, 'MM').format('MMM'))
          }

          // Cutting WIP
          var cwip = Highcharts.chart('cwip', {
              title: {
                  text: 'Cutting WIP'
              },
              subtitle: {
                  text: 'WIP'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Number of Pieces'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  type: 'column',
                  name: 'Cutting WIP',
                  colorByPoint: true,
                  data: cwip,
                  showInLegend: false
              }]
          });
          // Cutting WIP

          // Cutting Target Vs Actual
          var tvac = Highcharts.chart('tvac', {
              chart: {
                  type: 'line'
              },
              title: {
                  text: 'Target Vs Actual'
              },
              subtitle: {
                  text: 'Target vs Actual Cutting Quantity'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Number of Pieces'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true

                  }
              },
              series: [{
                  name: 'Actual',
                  data: actualC
              }, {
                  name: 'Target',
                  data: targetC
              }]
          });
          // Cutting target Vs Actual

          // Cutting Efficiency
          var ceffi = Highcharts.chart('ceffi', {
              chart: {
                  type: 'line'
              },
              title: {
                  text: 'Efficiency'
              },
              subtitle: {
                  text: 'Cutting Efficiency w.r.t Sewing'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Efficiency(%)'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  name: 'Efficiency',
                  data: effiCut
              }]
          });
          // Cutting Efficiency

          // Sewing Efficiency
          var seffi = Highcharts.chart('seffi', {
              chart: {
                  type: 'line'
              },
              title: {
                  text: 'Efficiency'
              },
              subtitle: {
                  text: 'Sewing Efficiency'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Efficiency(%)'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  name: 'Efficiency',
                  data: effiSew
              }]
          });
          // Sewing Efficiency

          // Monthly production
          var mp = Highcharts.chart('mp', {
              title: {
                  text: 'Monthly Production'
              },
              xAxis: {
                  categories: months
              },
              yAxis: {
                  title: {
                      text: 'Pieces Produced'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  type: 'column',
                  name: 'Pieces Produced',
                  colorByPoint: true,
                  data: monthlyProd,
                  showInLegend: false
              }]
          });
          // Monthly Production

          // Daily production
          var dp = Highcharts.chart('dp', {
              title: {
                  text: 'Daily Production'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Pieces Produced'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  type: 'column',
                  name: 'Pieces Produced',
                  colorByPoint: true,
                  data: production,
                  showInLegend: false
              }]
          });
          // Daily Production

          // Daily Sewing WIP
          var swip = Highcharts.chart('swip', {
              title: {
                  text: 'Sewing WIP'
              },
              subtitle: {
                  text: 'WIP'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Number of Pieces'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  type: 'column',
                  name: 'Sewing WIP',
                  colorByPoint: true,
                  data: swip,
                  showInLegend: false
              }]
          });
          // Daily Sewing WIP

          // Finishing wip
          var fwip = Highcharts.chart('fwip', {
              title: {
                  text: 'Finishing WIP'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Pieces'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  type: 'column',
                  colorByPoint: true,
                  name: 'Finishing WIP',
                  data: fwip,
                  showInLegend: false
              }]
          });
          // Finishing wip

          // Finishing Income vs Finished Goods
          var fedvpkd = Highcharts.chart('fedvpkd', {
              chart: {
                  type: 'line'
              },
              title: {
                  text: 'Finishing Income vs Finished Goods'
              },
              subtitle: {
                  text: 'Pieces Fed into finishig and Packed Goods'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'Number of Pieces'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true


                  }
              },
              series: [{
                  name: 'Packed Goods',
                  data: pkd
              }, {
                  name: 'Fed into Finishing',
                  data: income
              }]
          });
          // Finishing Income vs Finished Goods

          // DHU
          var dhuchart = Highcharts.chart('dhu', {
              chart: {
                  type: 'line'
              },
              title: {
                  text: 'DHU'
              },
              subtitle: {
                  text: 'Daily DHU Report'
              },
              xAxis: {
                  categories: dates
              },
              yAxis: {
                  title: {
                      text: 'DHU(%)'
                  }
              },
              plotOptions: {
                  line: {
                      dataLabels: {
                          enabled: true
                      },
                      enableMouseTracking: true
                  }
              },
              series: [{
                  name: 'DHU',
                  data: dhu
              }]
          });
          // DHU

          // Failed vs passed
          var invfa = Highcharts.chart('invfa', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Failed Vs Passed'
                },
                xAxis: {
                    categories: dates
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Inspected Quantity'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'right',
                    x: -30,
                    verticalAlign: 'top',
                    y: 25,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                        }
                    }
                },
                series: [{
                    name: 'Failed Quantity',
                    data: failed
                }, {
                    name: 'Passed Quantity',
                    data: passed
                }]
          });
          // Failed vs passed








        })// Promise function
      }
    }

  });
</script>
@endsection
