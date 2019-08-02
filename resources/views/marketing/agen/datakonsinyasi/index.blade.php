@extends('main')

@section('extra_style')
    <style type="text/css">
        .txt-readonly {
            background-color: transparent;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Kelola Konsinyasi </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Kelola Konsinyasi </span>
            </p>
        </div>

		<section class="section">

			<div class="row">

				<div class="col-12">

					<div class="card">
						<div class="card-header bordered p-2">
							<div class="header-block">
								<h3 class="title"> Kelola Konsinyasi </h3>
							</div>
							<div class="header-block pull-right">
								<a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i
									class="fa fa-arrow-left"></i></a>
							</div>
						</div>

						<div class="card-block">
							<section>
								<div class="row filterBranch">
									<div class="col-md-2 col-sm-4 col-xs-12">
										<label>Area</label>
									</div>
									<div class="col-md-5 col-sm-4 col-xs-12">
										<div class="form-group">
											<select name="provinsi" id="provinsi" class="form-control form-control-sm select2 provIdxDK">
											</select>
										</div>
									</div>
									<div class="col-md-5 col-sm-4 col-xs-12">
										<div class="form-group">
											<select name="kota" id="kota" class="form-control form-control-sm select2 cityIdxDK" disabled>
											</select>
										</div>
									</div>

									<div class="col-md-2 col-sm-4 col-xs-12">
										<label>Cabang</label>
									</div>
									<div class="col-md-10 col-sm-8 col-xs-12">
										<div class="form-group">
											<input type="hidden" class="userType" value="{{ Auth::user()->getCompany->c_type }}">
											<input type="hidden" name="branchCode" id="branchCode">
											<select class="form-control select2" name="branch" id="branch" disabled>
											</select>
										</div>
									</div>
								</div>

								<div class="row mb-3 d-none">
									<div class="col-md-3"></div>
									<div class="col-md-6 col-sm-12">
										<div class="input-group input-group-sm input-daterange">
											<input type="text" class="form-control" id="date_from_dk">
											<span class="input-group-addon">-</span>
											<input type="text" class="form-control" id="date_to_dk">
											<div class="input-group-append">
												<button class="btn btn-secondary" type="button" id="btn_search_date_dk"><i class="fa fa-search"></i></button>
												<button class="btn btn-primary" type="button" id="btn_refresh_date_dk"><i class="fa fa-refresh"></i></button>
											</div>
										</div>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_konsinyasi">
										<thead class="bg-primary">
											<tr>
												<th class="text-center">Tanggal</th>
												<th class="text-center">Nota</th>
												<th class="text-center">Agen</th>
												<th class="text-center">Total</th>
												<th class="text-center">Aksi</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>

							</section>

						</div>
					</div>

				</div>
			</div>

		</section>

	</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function() {
		// check user is PUSAT or not
		let userType = $('.userType').val();
		if (userType === 'PUSAT') {
			console.log('\'pusat\' logged in !');
			$('.filterBranch').removeClass('d-none');
		}
		else {
			console.log('\'non-pusat\' logged in !');
			$('.filterBranch').addClass('d-none');
			$('#branchCode').val("{{ Auth::user()->getCompany->c_id }}");
			console.log($('#branchCode').val());
			// retrieve data-table
			setTimeout(function(){
				TableListDK();
			}, 1000);
		}
	});

	function TableListDK() {
		$('#table_konsinyasi').DataTable().clear().destroy();
		table_konsinyasi = $('#table_konsinyasi').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('konsinyasiAgen.getListDK') }}",
				type: "get",
				data: {
					branchCode: $('#branchCode').val()
				}
			},
			columns: [
				{data: 'date'},
				{data: 'sc_nota'},
				{data: 'agent'},
				{data: 'total'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function editDK() {
		messageFailed('Edit', 'On Development process . . .');
	}

	function deleteDK() {
		messageFailed('Delete', 'On Development process . . .');
	}
</script>
@endsection
