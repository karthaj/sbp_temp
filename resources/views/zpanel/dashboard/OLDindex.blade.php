@extends('layouts.zpanel')


@section('content')
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB -->
<div id="app" class="row">
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
@if(auth()->user()->email === 'demo@aidantz.com') 
    <p>fsdfddsdsfdsf</p>
@endif
<!-- BEGIN PlACE PAGE CONTENT HERE -->

	<!-- ROW 1 -->
	<div class="row col-md-12">
		<div class="col-md-4">
			<h5>Welcome <strong>{{ auth()->user()->first_name.' '.auth()->user()->last_name }}</strong>,</h5>
			<p>
				Subscription End On: {{ session('store')->expiry_date->format("l". ", " . "d M Y" ) }}
				<a class="btn btn-xs btn-danger" href="{{ route('plan.change.index') }}"> Change Plan</a>
			</p>
			<br>
			@if(session('store')->setting->enable_password)
			<div class="row">
				<p class="col-md-12">Store front is password protected with <b>{{ session('store')->setting->password }}</b>.
					<a href="{{ route('store.preferences.index').'#passwordProtected' }}"><b>CONFIG</b></a>
				</p>
			</div>
			@endif
			
		</div>
		<!-- card Wrapper -->
		<div class="card card-transparent col-md-8">
			<!-- card-body Wrapper -->
            <div class="card-body row">
				<div class="card col-md-4 card-nobottom" data-step="{{ $items->count() + 2 }}" data-intro="Sold Products Count">
              	<div class="card-body text-center">
 					<h4>XXX,XXX,XXX</h4>
 					<h6 class="card-title">Sold Products Count</h6>
            	</div><!-- card-body -->
        	</div>
        	<div class="card col-md-4 card-nobottom">
              	<div class="card-body text-center">
 					<h4>XXX,XXX,XXX.00</h4>
 					<h6 class="card-title">Sales Revenue</h6>
            	</div><!-- card-body -->
        	</div>
        	<div class="card col-md-4 card-nobottom">
              	<div class="card-body text-center text-white">
 					<h4>XXX,XXX,XXX.00</h4>
 					<h6 class="card-title">ShopboxPay Balance</h6>
            	</div><!-- card-body -->
        	</div>
            </div><!-- END card-body Wrapper -->
        </div><!-- END card Wrapper -->
	</div><!-- END ROW 1 -->
	
	<!-- ROW 2 -->
	<div class="row">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1>50</h1>
                            <h6 class="card-title">Statics Count</h6>
                        </div><!-- card-body -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1>50</h1>
                            <h6 class="card-title">Statics Count</h6>
                        </div><!-- card-body -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1>23</h1>
                            <h6 class="card-title">Statics Count</h6>
                        </div><!-- card-body -->
                    </div>
                </div>
            </div>  
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1>32</h1>
                            <h6 class="card-title">Statics Count</h6>
                        </div><!-- card-body -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h1>14</h1>
                            <h6 class="card-title">Statics Count</h6>
                        </div><!-- card-body -->
                    </div>
                </div>
            </div>    
        </div>    

		<div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title">Bar Chart</h6>
              </div><!-- card-header -->
              	<div class="card-body">
              		<canvas id="barChart" width="400" height="150"></canvas>

              		</div>
							
            </div>
		</div>
		
		<div class="col-lg-4">
            <div class="card card-customer-overview">
              <div class="card-header">
                <h6 class="card-title">Line Chart</h6>
                
              </div><!-- card-header -->
              	<div class="card-body">
                	<canvas id="lineChart" width="400" height="150"></canvas>
            	</div><!-- card-body -->
        	</div>
		</div>   
	</div><!-- END ROW 2 -->

	<!-- ROW 3 -->
	<div class="row">
		<div class="col-lg-7">
            <div class="card card-customer-overview">
              <div class="card-header">
                <h6 class="card-title">PIE Chart</h6>
                
              </div><!-- card-header -->
              	<div class="card-body">
                	<canvas id="pieChart" width="400" height="200"></canvas>
            	</div><!-- card-body -->
        	</div>
		</div>
		
		
	</div><!-- END ROW 3 -->

	<!-- ROW X -->
	<div class="row">
		<div class="col-lg-7">
            <div class="card card-customer-overview">
              <div class="card-header">
                <h6 class="card-title">Sales Overviews</h6>
                <nav class="nav">
                  <a href="" class="nav-link active">Day</a>
                  <a href="" class="nav-link">Week</a>
                  <a href="" class="nav-link">Month</a>
                </nav>
              </div><!-- card-header -->
              	<div class="card-body">
                	<div id="flotArea1" class="ht-200 ht-sm-300"></div>
            	</div><!-- card-body -->
        	</div>
		</div>
	</div><!-- END ROW X -->
	
   </div>
	

	
<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->


@endsection

@section('page_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<script>

var ctx = document.getElementById("barChart").getContext('2d');
var barChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: '# of Orders',
            data: [12, 19, 41, 24, 64, 73, 23, 32, 41, 50, 85, 104],
            backgroundColor: [
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',	
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        },
        legend: {
            display: false, 
        }
    }
});

var ctx = document.getElementById("lineChart").getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: '# of Orders',
            data: [12, 19, 41, 24, 64, 73, 23, 32, 41, 50, 85, 104],
            backgroundColor: [
                '#fff',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',	
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)',
                'rgba(255, 99, 132, 0.9)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        },
        legend: {
            display: false, 
        }
    }
});

var ctx = document.getElementById('pieChart').getContext('2d');
var pieChart = new Chart(ctx, config);

var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        '2',
                        '4',
                        '5',
                        '8',
                        '23'
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.9)',
                        'rgba(255, 99, 132, 0.9)',
                        'rgba(255, 99, 132, 0.9)',
                        'rgba(255, 99, 132, 0.9)',
                        'rgba(255, 99, 132, 0.9)'
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'Red',
                    'Orange',
                    'Yellow',
                    'Green',
                    'Blue'
                ]
            },
            options: {
                responsive: true
            }
        };

function startTour()
  {
    var intro = introJs();
    intro.setOptions({
      showStepNumbers: false,
      exitOnEsc: false
    });
    intro.start();
  }

  $(document).on('click', '.guide-me', function(e){  
       startTour();
  });




</script>
@endsection
