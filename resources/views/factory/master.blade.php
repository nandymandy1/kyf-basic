@extends('layouts.app')

@section('content')
<div class="container" id="app">
  <!--Info Cards-->
  <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="info-box-2 bg-pink">
              <div class="icon">
                  <i class="material-icons">content_cut</i>
              </div>
              <div class="content">
                  <div class="text">CUTTING</div>
                  <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">125</div>
              </div>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="info-box-2 bg-green">
              <div class="icon">
                  <i class="fa fa-steam"></i>
              </div>
              <div class="content">
                  <div class="text">SEWING</div>
                  <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">125</div>
              </div>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="info-box-2 bg-blue">
              <div class="icon">
                  <i class="material-icons">assignment_turned_in</i>
              </div>
              <div class="content">
                  <div class="text">FINISHING</div>
                  <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">125</div>
              </div>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="info-box-2 bg-deep-orange">
              <div class="icon">
                  <i class="fa fa-check-circle"></i>
              </div>
              <div class="content">
                  <div class="text">QUALITY</div>
                  <div class="number count-to" data-from="0" data-to="125" data-speed="1000" data-fresh-interval="20">125</div>
              </div>
          </div>
      </div>

    </div>
  <!--Info Cards-->
  <div class="row clearfix">
      <!--Cutting Crisp Reports-->
      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
          <div class="card">
              <div class="body bg-pink">
                <div class="m-b--35 font-bold">CUTTING</div>
                  <ul class="dashboard-stat-list">
                      <li>
                          YESTERDAY
                          <span class="pull-right"><b>@{{ cutting[0].psew }}</b> <small>PIECES</small></span>
                      </li>
                      <li>
                          WEEKLY AVERAGE
                          <span class="pull-right"><b></b> <small>PIECES</small></span>
                      </li>
                      <li>
                          LAST WEEK
                          <span class="pull-right"><b>26 582</b> <small>PIECES</small></span>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
      <!--Cutting Crisp Reports-->
      <!--Sewing Crisp Reports-->
      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
          <div class="card">
              <div class="body bg-green">
                <div class="m-b--35 font-bold">SEWING</div>
                  <ul class="dashboard-stat-list">
                      <li>
                          YESTERDAY
                          <span class="pull-right"><b>@{{ sewing[0].outcome }}</b> <small>PIECES</small></span>
                      </li>
                      <li>
                          WEEKLY AVERAGE
                          <span class="pull-right"><b>3 872</b> <small>PIECES</small></span>
                      </li>
                      <li>
                          LAST WEEK
                          <span class="pull-right"><b>26 582</b> <small>PIECES</small></span>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
      <!--Sewing Crisp Reports-->
      <!--Fining Crisp Reports-->
      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
          <div class="card">
              <div class="body bg-blue">
                <div class="m-b--35 font-bold">FINISHING</div>
                  <ul class="dashboard-stat-list">
                      <li>
                          YESTERDAY
                          <span class="pull-right"><b>1 200</b> <small>PIECES</small></span>
                      </li>
                      <li>
                          WEEKLY AVERAGE
                          <span class="pull-right"><b>3 872</b> <small>PIECES</small></span>
                      </li>
                      <li>
                          LAST WEEK
                          <span class="pull-right"><b>26 582</b> <small>PIECES</small></span>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
      <!--Fining Crisp Reports-->
      <!--Quality Crisp Reports-->
      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
          <div class="card">
              <div class="body bg-deep-orange">
                <div class="m-b--35 font-bold">QUALITY</div>
                  <ul class="dashboard-stat-list">
                      <li>
                          YESTERDAY
                          <span class="pull-right"><b>@{{ dhu }}</b> <small>%</small></span>
                      </li>
                      <li>
                          AVERAGE
                          <span class="pull-right"><b>@{{ avgDhu }}</b> <small>%</small></span>
                      </li>
                      <li>
                          LAST WEEK
                          <span class="pull-right"><b>26 582</b> <small>PIECES</small></span>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
      <!--Quality Crisp Reports-->
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('./js/highcharts.src.js')}}" charset="utf-8"></script>
  <script src="{{ asset('./js/moment.js')}}" charset="utf-8"></script>
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
        id: 1,
        cwip:0,
        swip:0,
        fwip:0,
        cavg:0,
        savg:0,
        avgDhu:0.0,
        dhu:0.0
      }
    },
    created(){
      this.fetchReport()
    },
    watch:{

    },
    methods:{
      fetchReport() {
        axios.post(`/admin/factory/reportsmaster/${this.id}`)
        .then((response) => {
          this.production = response.data.production
          this.factory.name = response.data.factory[0].name
          this.factory.id = response.data.factory[0].id
          this.cutting = response.data.reports.cutting
          this.sewing = response.data.reports.sewing
          this.finishing = response.data.reports.finishing
          this.quality = response.data.reports.quality
          this.dGeneral = response.data.reports.d_general
          console.log(this.dGeneral)

          console.log(this.cutting)

          // Cutting Variables
          var cwip = [];
          var dates = [];
          var actualC = [];
          var targetC = [];
          var effiCut = [];
          // Sewing Variables
          var swip = [];
          var effiSew = [];
          var monthlyProd = [];
          var production = [];
          var months = [];
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
          // General Data Variables

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

          // To fill the Monthly Production Objects for the Charts to Render
          for(i=0; i < this.production.length; i++){
            monthlyProd.push(parseInt(this.production[i].t_prod))
            months.push(moment(this.production[i].month, 'MM').format('MMM'))
          }

          this.dhu = dhu[dhu.length-1];
          var sum = dhu.reduce((a, b) => a + b, 0);
          this.avgDhu = sum/dhu.length




        })// Promise function
      }
    }
  });
</script>
@endsection