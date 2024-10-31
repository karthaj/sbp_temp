<template>

<div class="card card-default">
  <div class="card-header ">
    <div class="card-title">Store Visits</div>
    <h2 v-if="!loading">{{ visits }}</h2>
  </div>
  <div class="card-block vld-parent">
  	<div v-if="error" class="alert alert-info bordered mt-3" role="alert">
		<p><i class="aapl-notification-circle"></i> Unable to load chart data.</p>
	</div>
    <div :class="dimension" ref="chartdiv"></div>
    <loading :active.sync="loading" :is-full-page="false"></loading>
  </div>
</div>

</template>

<script>

import bus from '../../bus'
import Loading from 'vue-loading-overlay';
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

var moment = require('moment');
	
export default {
	props: {
		dimension: {
			type: String,
			required: true
		}
	},
	components: {
        Loading
	},
	data () {
		return {
			error: false,
			loading: true,
			params: {
				for: moment().subtract(7, 'days').format('YYYY-MM-DD') + ',' + moment().subtract(1, 'days').format('YYYY-MM-DD'),
				by: 'day'
			},
			visits: 0
		}
	},
	methods: {
		getParams() {
            return queryString.stringify({
                ...this.params
            })
        }, 
		loadMetrics() {
			axios.post('/merchant/analytics/store_visits', this.params).then((response) => { 
		        this.chart.data = response.data.metrics;
		        this.visits = response.data.meta.visits;
		        this.valueAxis = _.maxBy(response.data.metrics, 'visits').visits + 1;
		        this.loading = false;
		        this.error = false
		    }).catch((error) => {  
		    	this.loading = false;
                this.error = true;
            });
		}
	},
	mounted() {

	    let chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);

		// Create axes
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "date";

		// Configure axis label
		var label = categoryAxis.renderer.labels.template;
		label.truncate = true;
		label.maxWidth = 100;

		// Create value axis
		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.min = 0;
	  	valueAxis.renderer.minGridDistance = 50;

		// Create series
		var series = chart.series.push(new am4charts.LineSeries());
		series.dataFields.valueY = "visits";
		series.dataFields.categoryX = "date";
		series.strokeWidth = 3;
		series.tensionX = 0.77;

		var bullet = series.bullets.push(new am4charts.CircleBullet());
		bullet.circle.strokeWidth = 2;
		bullet.circle.radius = 4;
		bullet.circle.fill = am4core.color("#fff");

		var bullethover = bullet.states.create("hover");
		bullethover.properties.scale = 1.3;

		// Make a panning cursor
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.xAxis = categoryAxis;
		chart.cursor.snapToSeries = series;

	    this.chart = chart;

	    this.loadMetrics();

	    bus.$on('daterange.apply', (data) => {
			this.params.for = data.range;
			this.params.by = data.period;
			this.loading = true;
			this.loadMetrics();
	  	});
  	},
  	beforeDestroy() {
	    if (this.chart) {
	      this.chart.dispose();
	    }
  	}
}

</script>