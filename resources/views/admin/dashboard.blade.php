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
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="seffi"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="mp"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="" id="dp"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
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
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

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
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

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

        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-4">

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
          console.log(this.production);
          this.factory.name = response.data.factory[0].name
          console.log(this.factory.name);
          this.factory.id = response.data.factory[0].id
          this.cutting = response.data.reports.cutting
          console.log(this.cutting);
          this.sewing = response.data.reports.sewing
          console.log(this.sewing)
          this.finishing = response.data.reports.finishing
          console.log(this.finishing)
          this.quality = response.data.reports.quality
          console.log(this.quality)
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

          // For initializing the Data objects for the Carts
          for(i = this.cutting.length-1; i >= 0; i--){

            dates.push(moment(new Date(this.cutting[i].created_at)).format("D-MMM"));
            cwip.push(this.cutting[i].pcut - this.cutting[i].psew);
            actualC.push(this.cutting[i].pcut);
            targetC.push(this.cutting[i].cqty);
            kopr = this.sewing[i].kopr;
            sopr = this.sewing[i].sopr;
            sam = this.sewing[i].sam;
            prod = this.sewing[i].prod;
            effiCut.push(parseFloat((this.cutting[i].pcut / (((kopr + sopr) * 480) / sam) * 100).toFixed(2)));
            effiSew.push(parseFloat((((prod*sam)/((kopr + sopr)*480))*100).toFixed(2)));
            production.push(prod);
            swip.push(this.sewing[i].prod - this.sewing[i].outcome);

          }

          for(i=0; i < this.production.length; i++){
            monthlyProd.push(parseInt(this.production[i].t_prod))
            months.push(moment(this.production[i].month, 'MM').format('MMM'))
          }
          console.log(monthlyProd)
          console.log(months)

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
                  colorByPoint: true,
                  data: swip,
                  showInLegend: false
              }]
          });
          // Daily Sewing WIP
          




        })// Promise function
      }
    }

  });
</script>

<script type="text/javascript">
</script>


@endsection
