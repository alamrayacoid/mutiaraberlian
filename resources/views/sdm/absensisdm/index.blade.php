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
					@include('sdm.absensisdm.presensi.index')
					<!-- <div class="card">
						<div class="card-header bordered p-2">
							<div class="header-block">
								<h3 class="title">Kelola Absensi SDM</h3>
							</div>
							<div class=""></div>
						</div>
						<div class="card-block">
							<section>
								<div class="download-contoh mb-2">
									<button class="btn btn-primary">Download Contoh</button>
								</div>
								<div class="row">
									<div class="input-group mb-3">
										<div class="row">
											<div class="container col-12">
												<div class="file-upload col-9">
													<div class="custom-file">
														<input type="file" class="custom-file-input col-8">
														<label class="custom-file-label">Pilih File</label>
													</div>
													<div class="input-group-append">
														<span class="input-group-text btn-upload btn btn-primary" id="">Upload</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-1 col-sm-6 col-xs-12">
										<label>Periode</label>
									</div>

									<div class="col-md-2 col-sm-6 col-xs-12">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm datepicker" name="">
										</div>
									</div>

									<div class="col-md-1 col-sm-6 col-xs-12">
										<span>S/D</span>
									</div>

									<div class="col-md-2 col-sm-6 col-xs-12">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm datepicker" name="">
										</div>
									</div>

									<div class="col-md-2">
										<button type="button" class="btn btn-primary">Cari</button>
									</div>

									<div class="col-md-1 col-sm-6 col-xs-12">
										<label>Divisi</label>
									</div>

									<div class="col-md-3 col-sm-6 col-xs-12">
										<div class="form-group">
											<select type="text" class="form-control form-control-sm" name="">
												<option value="">HRD & GA</option>
												<option value="">Keuangan dan Akuntansi</option>
												<option value="">Sales</option>
											</select>
										</div>
									</div>
								</div>
								<hr>
								<div class="table-responsive">
									<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_scoreboard">
										<thead class="bg-primary">
											<tr>
												<th>Tanggal</th>
												<th>Kode - Nama Pegawai</th>
												<th>Jam Kerja</th>
												<th>Jam Masuk</th>
												<th>Jam Pulang</th>
												<th>Scan Masuk</th>
												<th>Scan Pulang</th>
												<th>Terlambat</th>
												<th>Total Kerja</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>01 sep 2018</td>
												<td>13r4d - Brad</td>
												<td>Normal</td>
												<td>08:00:00</td>
												<td>17:00:00</td>
												<td>07:30:00</td>
												<td>18:17:00</td>
												<td>-</td>
												<td>09:00:00</td>
											</tr>
										</tbody>
									</table>
								</div>

							</section>

						</div>
					</div>
					 -->
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
