@section('extra_style')
<style type="text/css">
	.arriveTimePr:read-only {
		background-color: #dddddd;
		pointer-events:none;
	}
	.returnTimePr:read-only {
		background-color: #dddddd;
		pointer-events:none;
	}
	.onlyread {
		pointer-events:none;
		word-wrap: break-word;
		word-break: break-all;
	}
</style>
@endsection

<div class="tab-pane fade in show active" id="dashboard">

	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Dashboard Presensi</h3>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<!-- Chart -->
					<div class="chart-container col-md-6 col-sm-12">
						<div class="row">
							<div class=" col-md-6 col-sm-12">
								<label for="chart_filter">Tampilkan : </label>
								<select class="form-control form-control-sm" id="chart_filter" name="">
									<option value="TH">Per Tahun</option>
									<option value="BL">Per Bulan dalam 1 Tahun</option>
									<option value="PK">Per Pekan</option>
									<option value="HR" selected>Per Hari dalam 1 Bulan</option>
								</select>
							</div>
						</div>
						<hr>
						<div class="row col-md-12 col-sm-12">
							<canvas id="myChart" width="200" height="200"></canvas>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>


<!-- public set time -->
<!-- <script type="text/javascript">
	$(document).ready(function() {
		// var cur_date = new Date();
		// const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		// const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		// // date for 'Index Daftar Presensi SDM'
		// $('#filterDateFromPr').datepicker('setDate', first_day);
		// $('#filterDateToPr').datepicker('setDate', last_day);
	});
</script> -->

<!-- script for 'Index Daftar Presensi SDM' -->
<!-- <script type="text/javascript">
	$(document).ready(function() {
		// // draw and update chart
		// drawChart();
		// $('#chart_filter').on('change', function() {
		// 	updateChart();
		// });
	});

	// Draw chart using chart.js
	function drawChart()
	{
		updateChart();
		let list_qty = 0;
		chartElm = $('#myChart');
		myChart = new Chart(chartElm, {
			type: 'line',
			data: {
				labels: [],
				datasets: []
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							fontColor: 'rgb(255, 255, 255)',
							beginAtZero: true
						}
					}],
					xAxes: [{
						ticks: {
							fontColor: 'rgb(150, 255, 100)',
							beginAtZero: true
						}
					}]
				},
				legend: {
					display: true,
					labels: {
						fontColor: 'rgb(255, 255, 255)'
					}
				},
				title: {
					display: true,
					position: 'bottom',
					text: 'Kurs rerata Riyal',
					fontSize: 24,
					fontColor: 'rgb(255, 255, 255)'
				},
				elements: {
					line: {
						tension: 0
					},
				},
			}
		});
	}
	// add new dataset to chart
	function addDataChart(chart, datasetIndex, xaxis, data, label)
	{
		let listColor = ['blue', 'white', 'green', 'yellow', 'red', 'violet', 'grey']
		let newDataset = {
			label: label,
			data: data,
			backgroundColor: listColor[datasetIndex],
			borderWidth: 1,
			borderColor: listColor[datasetIndex],
			fill: false,
			pointRadius: 3
		};
		chart.data.labels = xaxis;
		chart.data.datasets.push(newDataset);
		chart.update();
	}
	// remove current dataset inside chart
	function removeAllDatasets(chart)
	{
		chart.data.datasets = [];
		chart.update();
	}
	// update chart
	function updateChart()
	{
		filter_val = $('#chart_filter').val();

		$.ajax({
			url: "{{ route('presensiDash.getPresence') }}",
			data: {
				filter: filter_val
			},
			type: "get",
			success: function(response) {
				console.log(response);
				removeAllDatasets(myChart);
				$.each(response.data, function(index, val) {
					addDataChart(myChart, index, response.xaxis, val, response.label[index]);
				});
			},
			error: function(xhr, status, error) {
				let err = JSON.parse(xhr.responseText);
				console.log(err.message);
			}
		});
	}
</script> -->
