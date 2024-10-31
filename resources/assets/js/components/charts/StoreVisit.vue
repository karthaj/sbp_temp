<template>

<highcharts :options="chartOptions"></highcharts>

</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
	.chart {
	  width: 100%;
	  height: 400px;
	}
</style>

<script>

require('../../highcharts');
import {Chart} from 'highcharts-vue'
var moment = require('moment');
	
export default {
	components: {
    	highcharts: Chart 
  	},
  	data () {
  		return {
  			chartOptions: {
		        xAxis: {
		            type: 'category',
		            labels: {
				        formatter: function() {
				            return moment(this.value).format('MMM DD');
				        }
				    }
		        },
		        series: [{
		        	data: []
		        }]
  			}
  		}
  	},
	mounted () {

		// axios.get('/analytics/store_visits')
  //       .then((response) => { 
  //           // this.chartOptions.series[0].data = response.data.data;
  //           this.chartOptions.series[0].data = response.data.data;
  //       }).catch((error) => { 
  //         console.log(error)
  //       });

  		var series_data = [];

  		axios.get('/analytics/store_visits')
        .then((response) => { 
            var data = response.data.data;
            for (var i = 0; i < data.length; i++){
                series_data.push([data[i].key, data[i].value]);
            }
            // console.log(processed_json)
            // this.chartOptions.series[0].data = processed_json;
            // console.log('first = ', this.chartOptions.series[0].data)
           // this.chartOptions.series[0].data = processed_json
           this.chartOptions.series[0].data = series_data;
           // console.log(this.chartOptions.series[0].data)
        }).catch((error) => { 
          console.log(error)
        });

        

        // console.log()
	}
}

</script>