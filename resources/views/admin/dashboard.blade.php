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
              <div id="cwip" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div id="targetvActual" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div id="ceffi" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-red">
      Sewing
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div id="seffi" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div id="mp" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <!--<div id="ceffi" style="height: 250px; max-width: 250px; margin: 0px auto;"></div>-->
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

<script type="text/javascript">
  var app = new Vue({
    el: '#app',
    data() {
      return {
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
    created() {
      // console.log(this.id);
      this.fetchReport();
    },
    methods: {

      fetchReport() {
        axios.post(`/admin/factory/reports/${this.id}`).then((response) => {

          console.log(response.data)
          this.production = response.data.production
          this.factory.name = response.data.factory[0].name
          this.factory.id = response.data.factory[0].id
          this.cutting = response.data.reports.cutting
          this.sewing = response.data.reports.sewing
          console.log(this.sewing)
          this.finishing = response.data.reports.finishing
          console.log(this.finishing)
          this.quality = response.data.reports.quality
          console.log(this.quality)
          this.dGeneral = response.data.reports.d_general
          console.log(this.dGeneral)

          var cwip = [];
          var actualC = [];
          var targetC = [];
          var effiCut = [];
          var effiSew = [];
          var monthlyProd = [];
          var months = ['Jan', 'Feb', 'Mar', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];



          // LOOP FOR OBJECTS PREPARATION FOR THE CHARTS TO RENDER

          // For Monthly Production reports
          for (var j = 0; j < this.production.length; j++){
            monthlyProd.push({
              label: months[this.production[j].month - 1],
              y: parseInt(this.production[j].t_prod)
            });
          }

          // FOR DAILY REPORTS OBJECTS PREPARATION
          for (var i = 0; i < this.cutting.length; i++) {
            dates = new Date(this.cutting[i].created_at);
            cwip.push({
              x: dates,
              y: this.cutting[i].pcut - this.cutting[i].psew
            });
            actualC.push({
              x: dates,
              y: this.cutting[i].pcut
            });
            targetC.push({
              x: dates,
              y: this.cutting[i].cqty
            });

            kopr = this.sewing[i].kopr;
            sopr = this.sewing[i].sopr;
            sam = this.sewing[i].sam;
            prod = this.sewing[i].prod;

            effiCut.push({
              x: dates,
              y: parseFloat((this.cutting[i].pcut / (((kopr + sopr) * 480) / sam) * 100).toFixed(2))
            });

            effiSew.push({
              x: dates,
              y: parseFloat((((prod*sam)/((kopr + sopr)*480))*100).toFixed(2))
            });



          } // OBJECTS PREPARATION FOR THE CHARTS TO RENDER

          // CUTTING METHODS GOES HERE
          // For Avarage Efficiency

          // CUTTING
          avgEffiCut = 0;
          for (j = 0; j < effiCut.length; j++) {
            avgEffiCut += effiCut[j].y;
          }
          avgEffiCut = (avgEffiCut / effiCut.length).toFixed(2);
          // SEWING
          avgEffiSew = 0;
          for (j = 0; j < effiSew.length; j++) {
            avgEffiSew += effiSew[j].y;
          }
          avgEffiSew = (avgEffiSew / effiSew.length).toFixed(2);
          // Average Efficiency Calucation Ends Here


          // Daily Cutting WIP
          cwip = new CanvasJS.Chart("cwip", {
            animationEnabled: true,
            theme: "light2",
            title: {
              text: "Daily WIP"
            },
            axisY: {
              title: "WIP",
              titleFontSize: 15
            },
            axisX: {
              valueFormatString: "DD MMM"
            },
            data: [{
              type: "column",
              yValueFormatString: "#,### WIP",
              dataPoints: cwip
            }]
          })
          cwip.render();
          // Daily Cutting Target vs Actual
          var ctva = new CanvasJS.Chart("targetvActual", {
            animationEnabled: true,
            theme: "light2",
            title: {
              text: "Target vs Actual Cutting"
            },
            axisX: {
              valueFormatString: "DD MMM"
            },
            axisY: {
              title: "Pieces Cut",
              includeZero: true
            },
            toolTip: {
              shared: true
            },
            data: [{
                name: "Target",
                type: "spline",
                showInLegend: true,
                dataPoints: targetC
              },
              {
                name: "Actual",
                type: "spline",
                showInLegend: true,
                dataPoints: actualC
              }
            ]
          });
          ctva.render();
          // Daily Cutting Efficiency
          var ceffi = new CanvasJS.Chart("ceffi", {
            animationEnabled: true,
            theme: "light2",
            title: {
              text: "Efficiency"
            },
            axisY: {
              title: "Efficiency (%)",
              suffix: "%",
              stripLines: [{
                value: avgEffiCut,
                label: "Average Efficiency (%)"
              }]
            },
            axisX: {
              valueFormatString: "DD MMM"
            },
            data: [{
              type: "spline",
              dataPoints: effiCut
            }]
          });
          ceffi.render();

          // SEWING METHODS GOES HERE
          /* Sewing Efficiency*/

          var seffi = new CanvasJS.Chart("seffi", {
            animationEnabled: true,
            theme: "light2",
            title: {
              text: "Efficiency"
            },
            axisY: {
              title: "Efficiency (%)",
              suffix: "%",
              stripLines: [{
                value: avgEffiSew,
                label: "Average Efficiency (%)"
              }]
            },
            axisX: {
              valueFormatString: "DD MMM"
            },
            data: [{
              type: "spline",
              dataPoints: effiSew
            }]
          });
          seffi.render();

          // Monthly Production reports
          mp = new CanvasJS.Chart("mp", {
            animationEnabled: true,
          	theme: "light2", // "light1", "light2", "dark1", "dark2"
          	title:{
          		text: "Monthly Production"
          	},
          	axisY: {
          		title: "Production Qty."
          	},
          	data: [{
          		type: "column",
          		dataPoints: monthlyProd
          	}]
          })
          mp.render();
          /* Sewing Target vs Actual*/









        }) //axios Call
      } // fetch Factory Report


    } // Methods
    // Vue Instance
  });
</script>

<script type="text/javascript">
</script>


@endsection
