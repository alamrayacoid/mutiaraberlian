@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
		<h1 class="title">Kelola Absensi SDM</h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
			/ <span>Aktivitas SDM</span>
			/ <span class="text-primary" style="font-weight: bold;">Kelola Absensi SDM</span>
		</p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				<ul class="nav nav-pills mb-3" id="Tabs">
					<li class="nav-item">
						<a href="#dashboard" class="nav-link active" data-target="#dashboard" aria-controls="dashboard" data-toggle="tab" role="tab">Dashboard</a>
					</li>
					<li class="nav-item">
						<a href="#presensi" class="nav-link" data-target="#presensi" aria-controls="presensi" data-toggle="tab" role="tab">Daftar Presensi SDM</a>
					</li>
					<li class="nav-item">
						<a href="#kehadiran" class="nav-link" data-target="#kehadiran" aria-controls="kehadiran" data-toggle="tab" role="tab">Kelola Aturan Kehadiran</a>
					</li>
					<li class="nav-item">
						<a href="#cuti" class="nav-link" data-target="#cuti" aria-controls="cuti" data-toggle="tab" role="tab">Kelola Jenis Cuti</a>
					</li>
					<li class="nav-item">
						<a href="#harikerja" class="nav-link" data-target="#harikerja" aria-controls="harikerja" data-toggle="tab" role="tab">Kelola Hari Kerja dan Libur</a>
					</li>
				</ul>

				<div class="tab-content">
					@include('sdm.absensisdm.dashboard.index')
					@include('sdm.absensisdm.presensi.index')
				</div>
			</div>
		</div>
	</section>
</article>

@endsection
@section('extra_script')
<!-- script for 'Daftar Presensi SDM' -->
<!-- <script type="text/javascript">
	$(document).ready(function(){
		var cur_date = new Date();
		$('.dateNow').val(cur_date);
	});
</script> -->
@endsection
