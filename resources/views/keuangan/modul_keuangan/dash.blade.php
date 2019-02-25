@extends('main')

@section('title', 'Dashboard')

@section(modulSetting()['extraStyles'])
	
@endsection


@section('content')
    <div class="col-md-12" style="background: none;">
    	<div class="row">
    		<div class="col-md-4">
    			<canvas id="canvas" height="200px;"></canvas>
    		</div>
    	</div>
    </div>
@endsection


@section(modulSetting()['extraScripts'])
	
	<script src="{{ asset('modul_keuangan/js/options.js') }}"></script>
	<script src="{{ asset('modul_keuangan/js/vendor/chart_js_2_7_3/Chart.bundle.min.js') }}"></script>

	<script type="text/javascript">

        var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var config = {
			type: 'line',
			data: {
				labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
				datasets: [{
					steppedLine: true,
					backgroundColor: primaryColor,
					borderColor: primaryColor,
					data: [8, 7, 9, 3, 2, 1, 10],
					fill: false,
					showLine: false,
					pointRadius: 4,
					pointHoverRadius: 8,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: true,
					text: 'Total Pendapatan (beta)'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Periode'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Nilai'
						},
						ticks: {
							suggestedMin: 0,
							suggestedMax: 15,
						}
					}]
				},
				elements: {
					point: {
						pointStyle: 'rectRounded'
					}
				},
				legend: {
					display: false
				},
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};
    </script>

@endsection