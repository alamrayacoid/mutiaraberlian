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
<!-- public set time -->
<script type="text/javascript">
	$(document).ready(function() {
		var cur_date = new Date();
		const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		// date for 'Index Daftar Presensi SDM'
		$('#filterDateFromPr').datepicker('setDate', first_day);
		$('#filterDateToPr').datepicker('setDate', last_day);
		// date for 'Create Daftar Presensi SDM'
		$('.dateNowPr').datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate()));
	});
</script>

<script type="text/javascript">
	var month_years = new Date();
	const month_year = new Date(month_years.getFullYear(), month_years.getMonth());

	$("#filterByMonthYearDashbord").datepicker( {
    format: "mm-yyyy",
    viewMode: "months", 
    minViewMode: "months"
	});
</script>

<!-- <script type="text/javascript">
	var filter_years = new Date();
	const filter_year = new Date(filter_years.getFullYear());

	$("#filterByYearDashbord").datepicker( {
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years"
	});
</script> -->

<!-- script for 'Create Daftar Presensi SDM' -->
<script type="text/javascript">
	var idxRow = 0;
	var branchId = null;
	var listEmployeeCode = null;

	$(document).ready(function(){
		// get list presence
		$('.dateNowPr').datepicker().on('changeDate', function() {
			getPresence();
		});
		// get list branch
		getBranchPr();
		$('#branchPr').on('select2:select', function() {
			branchId = $(this).val();
			getPresence();
		});
		// get list division
		getDivisionPr();
		$('#divisionPr').on('select2:select', function() {
			getPresence();
		});

		getEventsReady();
		// store data
		$('#btnSimpanPresence').on('click', function() {
			storePr();
		});
		// reset form on hidden modal
		$('#modalCreate').on('hidden.bs.modal', function() {
			$('#presenceForm')[0].reset();
			getBranchPr();
			getDivisionPr();
			// $(this).find('#presenceForm')[0].reset();
			// $('#branchPr')[0].selectedIndex = 0;
			// $('#presenceForm').find('input[type="text"],input[type="email"],textarea,select').val('');
			$("#table_presence > tbody").find("tr:gt(0)").remove();
		});
		// reset form on hidden modal
		$('#modalCreate').on('shown.bs.modal', function() {
			// var cur_date = new Date();
			// const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            // const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
			// $('.dateNowPr').datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate()));
			$('#branchPr').selectedIndex = 0;
			$('.employeePrId').val('');
			// $('#branchPr').val($('#branchPr option:first').val());
		});

	});

	function getEventsReady()
	{
		$('.btnAddEmployee').off();
		$('.statusPr').off();
		// add more employee
		$('.btnAddEmployee').on('click', function() {
			let row = '';
	        row = `<tr><td class="pad-1">
				<input type="hidden" name="employeePrId[]" class="employeePrId" value="">
				<input type="text" name="employeePr[]" class="employeePr w-100">
			</td>
			<td class="pad-1">
				<select name="statusPr[]" class="statusPr w-100">
					<option value="H" selected="">Hadir</option>
					<option value="I">Ijin</option>
					<option value="T">Tidak Masuk</option>
					<option value="C">Cuti</option>
				</select>
			</td>
			<td class="pad-1">
				<input type="text" name="arriveTimePr[]" class="arriveTimePr w-100" value="">
			</td>
			<td class="pad-1">
				<input type="text" name="returnTimePr[]" class="returnTimePr w-100" value="">
			</td>
			<td class="pad-1">
				<textarea name="notePr[]" rows="1" class="w-100"></textarea>
			</td>
			<td class="pad-1 text-center">
				<button class="btn btn-danger btn-sm rounded-circle btnRemoveEmployee" style="color:white;" type="button">
					<i class="fa fa-remove" aria-hidden="true"></i>
				</button>
			</td></tr>`;

	        $('#table_presence tbody').append(row);
			getEventsReady();
		});
		// changed status
		$('.statusPr').on('click change', function() {
			idxRow = $('.statusPr').index(this);
			if ($('.statusPr').eq(idxRow).val() == 'T') {
				$('.arriveTimePr').eq(idxRow).val('');
				$('.arriveTimePr').eq(idxRow).attr('readonly', true);
				$('.returnTimePr').eq(idxRow).val('');
				$('.returnTimePr').eq(idxRow).attr('readonly', true);
			}
			else {
				$('.arriveTimePr').eq(idxRow).attr('readonly', false);
				$('.returnTimePr').eq(idxRow).attr('readonly', false);
			}
			console.log('asd');
		});

		// find employee
		$('.employeePr').on('click keyup', function(event) {
			idxRow = $('.employeePr').index(this);
			branchId = $('#branchPr').val();
			if (event.which == 8 || event.which == 46) {
				resetInputRow();
			}
			findEmployee();
		});
		// set time-picker
		$('.arriveTimePr').timepicker(
			{ 'timeFormat': 'H:i:s' }
		);
		$('.returnTimePr').timepicker(
			{ 'timeFormat': 'H:i:s' }
		);
		// remove an employee row
		$('.btnRemoveEmployee').on('click', function() {
			console.log('remove row !');
			$(this).parents('tr').remove();
		});
	}
	// reset input row
	function resetInputRow() {
		$('.employeePr').eq(idxRow).val('');
		$('.employeePrId').eq(idxRow).val('');
		$('.arriveTimePr').eq(idxRow).val('');
		$('.returnTimePr').eq(idxRow).val('');
		// $('.statusPr').eq(idxRow).selectedIndex = 0;
		$('.notePr').eq(idxRow).val('');
	}
	// get list branch
	function getBranchPr() {
		$.ajax({
			url: "{{ route('presensi.getBranch') }}",
			type: 'get',
			success: function(resp) {
				$('#branchPr').empty();
				$('#branchPr').append('<option value="" selected>Semua Cabang</option>');
				$.each(resp, function (idx, val) {
					$('#branchPr').append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
				});
			},
			error: function(e) {
				messageWarning('Error', 'getBranch error : ' + e.message);
			}
		})
	}
	// get list division
	function getDivisionPr() {
		$.ajax({
			url: "{{ route('presensi.getDivision') }}",
			type: 'get',
			success: function(resp) {
				$('#divisionPr').empty();
				$('#divisionPr').append('<option value="" selected>Semua Divisi</option>');
				$.each(resp, function (idx, val) {
					$('#divisionPr').append('<option value="'+val.m_id+'">'+val.m_name+'</option>');
				});
			},
			error: function(e) {
				messageWarning('Error', 'getBranch error : ' + e.message);
			}
		})
	}
	// get list presence in a date
	function getPresence() {
		let date = $('.dateNowPr').serialize();
		let branch = $('#branchPr').serialize();
		let division = $('#divisionPr').serialize();
		let data = date +'&'+ branch +'&'+ division;
		$.ajax({
			url: "{{ route('presensi.getPresence') }}",
			data: data,
			success: function(resp) {
				if (resp.length > 0) {
					$("#table_presence > tbody").find('tr').remove();
				}
				else {
					$("#table_presence > tbody").find('tr:gt(0)').remove();
				}
				$("#table_presence > tbody").find('input').val('');
				$.each(resp, function(key, val) {
			        let empId = `<td class="pad-1">
									<input type="hidden" name="employeePrId[]" class="employeePrId" value="`+ val.e_id +`">
									<input type="text" name="employeePr[]" class="employeePr w-100" value="`+ val.e_name + ' ('+ val.get_division.m_name +') / '+ val.e_id +`">
								</td>`;

					let h = `<option value="H">Hadir</option>`;
					let i = `<option value="I">Izin</option>`;
					let t = `<option value="T">Tidak Masuk</option>`;
					let c = `<option value="C">Cuti</option>`;
					let aTime = '';
					let rTime = '';
					let iNote = '';
					let action;

					if (val.get_presence.length > 0) {
						h = (val.get_presence[0].p_status == 'H') ? `<option value="H" selected>Hadir</option>` : `<option value="H">Hadir</option>`;
						i = (val.get_presence[0].p_status == 'I') ? `<option value="I" selected>Ijin</option>` : `<option value="I">Ijin</option>`;
						t = (val.get_presence[0].p_status == 'T') ? `<option value="T" selected>Tidak Masuk</option>` : `<option value="T">Tidak Masuk</option>`;
						c = (val.get_presence[0].p_status == 'C') ? `<option value="C" selected>Cuti</option>` : `<option value="C">Cuti</option>`;
						(val.get_presence[0].p_entry == null) ? aTime = '' : aTime = val.get_presence[0].p_entry;
						(val.get_presence[0].p_out == null) ? rTime = '' : rTime = val.get_presence[0].p_out;
						(val.get_presence[0].p_note == null) ? iNote = '' : iNote = val.get_presence[0].p_note;
					}
					let status = `<td class="pad-1"><select name="statusPr[]" class="statusPr w-100">` + h + i + t + c + `</select></td>`;
					let arriveTime = `<td class="pad-1"><input type="text" name="arriveTimePr[]" class="arriveTimePr w-100" value="`+ aTime +`"></td>`;
					let returnTime = `<td class="pad-1"><input type="text" name="returnTimePr[]" class="returnTimePr w-100" value="`+ rTime +`"></td>`;
					let note = `<td class="pad-1"><textarea name="notePr[]" rows="1" class="w-100">`+ iNote +`</textarea></td>`;
					if (key == 0) {
						action = `<td class="pad-1 text-center">
						<button class="btn btn-success btn-sm rounded-circle btnAddEmployee" style="color:white;" type="button">
						<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
						</td>`;
					}
					else {
						action = `<td class="pad-1 text-center">
						<button class="btn btn-danger btn-sm rounded-circle btnRemoveEmployee" style="color:white;" type="button">
						<i class="fa fa-remove" aria-hidden="true"></i>
						</button>
						</td>`;
					}

					let row = '<tr>'+ empId + status + arriveTime + returnTime + note + action +'</tr>'
			        $('#table_presence tbody').append(row);
					// get recently added item to update read-only for 'tidak masuk'
					if ($('.statusPr').filter(':last').val() == 'T') {
						let idxTemp = $('.statusPr').index(this);
						$('.arriveTimePr').eq(idxTemp).attr('readonly', true);
						$('.returnTimePr').eq(idxTemp).attr('readonly', true);
					}
				});
				getEventsReady();
			},
			error: function(e) {
				messageWarning('Error', 'Error getDataPresence: '+ e.message);
			}
		});
	}
	// find employee autocomplete
	function findEmployee() {
		// get list of employee-id
		let listEmpId = [];
		$.each($('.employeePrId'), function(key, value){
			listEmpId.push($('.employeePrId').eq(key).val());
		});

		$('.employeePr').eq(idxRow).autocomplete({
			appendTo: '#presenceForm',
			source: function( request, response ) {
				$.ajax({
					url: "{{ route('presensi.getEmployee') }}",
					data: {
						term: $('.employeePr').eq(idxRow).val(),
						branchId: branchId,
						listEmpId: listEmpId
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 1,
			select: function(event, data) {
				$('.employeePrId').eq(idxRow).val(data.item.id);
			}
		});
	}
	// store data to db
	function storePr() {
		console.log($('#presenceForm').serialize());
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Konfirmasi!',
			content: 'Apakah anda yakin akan menyimpan data presensi ini ?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text: 'Ya',
					action: function () {
						storePrAjax();
					}
				},
				cancel: {
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	}
	function storePrAjax() {
		data = $('#presenceForm').serialize();
		$.ajax({
			url: "{{ route('presensi.store') }}",
			data: data,
			type: 'post',
			success: function(resp) {
				if (resp.status == 'berhasil') {
					messageSuccess('Berhasil', 'Presensi berhasil disimpan !');
					getPresence();
				}
				else {
					messageWarning('Perhatian', 'Terjadi kesalahan : '+ resp.message);
				}
				console.log(resp);
			},
			error: function(e) {
				messageWarning('Error', 'Simpan presensi error: '+ e.message)
			}
		})
	}
</script>

<!-- script for 'Index Daftar Presensi SDM' -->
<script type="text/javascript">
	$(document).ready(function() {
		getFilterBranch();
		getFilterBranchDashboard();
		// $('#filterDateFromPr').datepicker('setDate', first_day);
		// $('#filterDateToPr').datepicker('setDate', last_day);
		$('#filterByMonthYearDashbord').on('change input-daterange', function() {
			getAbsenPegawai();
		});
		$('#filterByMonthDashbord').on('change select2:select', function() {
			getAbsenPegawai();
		});
		$('#filterByYearDashbord').on('change input-daterange', function() {
			getAbsenPegawai();
		});

		$('#filterByBranchDashbord').on('change select2:select', function() {
			getAbsenPegawai();
		});
		// call function when filter activated
		$('#filterByBranch').on('change select2:select', function() {
			getPresenceSummary();
		});
		$('#filterDateFromPr').on('change', function() {
			getPresenceSummary();
		});
		$('#filterDateToPr').on('change', function() {
			getPresenceSummary();
		});

	});
	// get list branch for filter-by-branch
	function getFilterBranchDashboard()
	{
		$.ajax({
			url: "{{ route('presensi.getBranch') }}",
			type: 'get',
			success: function(resp) {
				$('#filterByBranchDashbord').empty();
				$('#filterByBranchDashbord').append('<option value="" selected>Semua Cabang</option>');
				$.each(resp, function (idx, val) {
					$('#filterByBranchDashbord').append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
				});
			},
			error: function(e) {
				messageWarning('Error', 'getBranch error : ' + e.message);
			}
		})
	}

	function getFilterBranch()
	{
		$.ajax({
			url: "{{ route('presensi.getBranch') }}",
			type: 'get',
			success: function(resp) {
				$('#filterByBranch').empty();
				$('#filterByBranch').append('<option value="" selected>Semua Cabang</option>');
				$.each(resp, function (idx, val) {
					$('#filterByBranch').append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
				});
			},
			error: function(e) {
				messageWarning('Error', 'getBranch error : ' + e.message);
			}
		})
	}
	// get list summary presence
	
	function getAbsenPegawai()
	{
		// let dateFrom = $('#filterDateFromPr').serialize();
		// let dateTo = $('#filterDateToPr').serialize();
		// let filter_month = $('#filterByMonthDashbord').serialize();
		// let filter_year = $('#filterByYearDashbord').serialize();
		let month_year = $('#filterByMonthYearDashbord').serialize();
		let branchDashboard = $('#filterByBranchDashbord').serialize();
		let param = month_year +'&'+ branchDashboard;

		console.log(param);

		$('#table_presensi_sdm_dashboard').dataTable().fnDestroy();
		tb_listmpa = $('#table_presensi_sdm_dashboard').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('presensi.getAbsenPegawai') }}",
				type: 'get',
				data: {
					// filterByMonthDashbord: $('#filterByMonthDashbord').val(),
					// filterByYearDashbord: $('#filterByYearDashbord').val(),
					filterByMonthYearDashbord: $('#filterByMonthYearDashbord').val(),
					filterByBranchDashbord: $('#filterByBranchDashbord').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'employee'},
				{data: 'hadir'},
				{data: 'ijin'},
				{data: 'tidakMasuk'},
				{data: 'cuti'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function getPresenceSummary()
	{
		let dateFrom = $('#filterDateFromPr').serialize();
		let dateTo = $('#filterDateToPr').serialize();
		let branch = $('#filterByBranch').serialize();
		let param = dateFrom +'&'+ dateTo +'&'+ branch;

		console.log(param);

		$('#table_presensi_sdm').dataTable().fnDestroy();
		tb_listmpa = $('#table_presensi_sdm').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('presensi.getPresenceSummary') }}",
				type: 'get',
				data: {
					filterDateFromPr: $('#filterDateFromPr').val(),
					filterDateToPr: $('#filterDateToPr').val(),
					filterByBranch: $('#filterByBranch').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'date'},
				{data: 'hadir'},
				{data: 'ijin'},
				{data: 'tidakMasuk'},
				{data: 'cuti'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// show modal detail presence in dashboard
	function showDetailAbsenPegawai(id, p_emp)
	{
		// console.log(p_employee);
		$.ajax({
			url: "{{ route('presensi.getDetailAbsenPegawai') }}",
			data: {
				id: id,
				employee: p_emp
			},
			success: function(resp) {
				console.log(resp);
				if (resp.length > 0) {
					$("#table_detail_absen_pegawai > tbody").find('tr').remove();
				}
				else {
					$("#table_detail_absen_pegawai > tbody").find('tr:gt(0)').remove();
				}
				$('#emp_name').text(resp[0].e_name);
				// $("#table_detail_absen_pegawai > h4").find('input').val('');
				// let empId = `<input type="text" name="employeePr[]" class="form-control-plaintext employeePr onlyread w-100" value="`+ get_employee.e_name + ' ('+ get_employee.get_division.m_name +') / '+ p_employee +`">`;

				$("#table_detail_absen_pegawai > tbody").find('input').val('');
				$.each(resp, function(key, val) {
					let date = `<td class="pad-1">
									<input type="text" name="datePr[]" class="form-control-plaintext text-center datePr onlyread w-100" value="`+ val.p_date +`">
									</td>`;

					let aTime = null;
					(val.p_entry == null) ? aTime = '' : aTime = val.p_entry;
					let arriveTime = `<td class="pad-1">
										<input type="text" name="arriveTimePr[]" class="form-control-plaintext text-center arriveTimePr onlyread w-100" value="`+ aTime +`">
										</td>`;

					let rTime = null;
					(val.p_out == null) ? rTime = '' : rTime = val.p_out;
					let returnTime = `<td class="pad-1"><input type="text" name="returnTimePr[]" class="form-control-plaintext text-center returnTimePr onlyread w-100" value="`+ rTime +`"></td>`;

					let status
					if (val.p_status == 'H') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext text-center statusPr onlyread w-100" value="Hadir"></td>`;
					}
					else if (val.p_status =='I') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext text-center statusPr onlyread w-100" value="Ijin"></td>`;
					}
					else if (val.p_status == 'T') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext text-center statusPr onlyread w-100" value="Tidak Masuk"></td>`;
					}
					else if (val.p_status == 'C') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext text-center statusPr onlyread w-100" value="Cuti"></td>`;
					}

					let iNote = null;
					(val.p_note == null || val.p_note == '') ? iNote = '' : iNote = val.p_note;
					let note = `<td class="pad-1"><textarea name="notePr[]" rows="2" class="w-100" readonly>`+ iNote +`</textarea></td>`;

					let row = '<tr>'+ date + arriveTime + returnTime + status + note +'</tr>' 
			        $('#table_detail_absen_pegawai tbody').append(row);
				});
				$('#modalDetailDashboard').modal('show');
			},
			error: function(e) {
				messageWarning('Error', 'Error getDataPresence: '+ e.message);
			}
		});
	}

	// show modal detail presence
	function showDetailPresence(id)
	{
		$.ajax({
			url: "{{ route('presensi.getDetailPresence') }}",
			data: {
				id: id
			},
			success: function(resp) {
				console.log(resp);
				if (resp.length > 0) {
					$("#table_detail_presence > tbody").find('tr').remove();
				}
				else {
					$("#table_detail_presence > tbody").find('tr:gt(0)').remove();
				}
				$("#table_detail_presence > tbody").find('input').val('');
				$.each(resp, function(key, val) {
			        let empId = `<td class="pad-1">
									<input type="hidden" name="employeePrId[]" class="employeePrId" value="`+ val.get_employee.e_id +`">
									<input type="text" name="employeePr[]" class="form-control-plaintext employeePr onlyread w-100" value="`+ val.get_employee.e_name + ' ('+ val.get_employee.get_division.m_name +') / '+ val.p_employee +`">
								</td>`;

					let aTime = null;
					(val.p_entry == null) ? aTime = '' : aTime = val.p_entry;
					let arriveTime = `<td class="pad-1">
										<input type="text" name="arriveTimePr[]" class="form-control-plaintext arriveTimePr onlyread w-100" value="`+ aTime +`">
										</td>`;

					let rTime = null;
					(val.p_out == null) ? rTime = '' : rTime = val.p_out;
					let returnTime = `<td class="pad-1"><input type="text" name="returnTimePr[]" class="form-control-plaintext returnTimePr onlyread w-100" value="`+ rTime +`"></td>`;

					let status
					if (val.p_status == 'H') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext statusPr onlyread w-100" value="Hadir"></td>`;
					}
					else if (val.p_status =='I') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext statusPr onlyread w-100" value="Ijin"></td>`;
					}
					else if (val.p_status == 'T') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext statusPr onlyread w-100" value="Tidak Masuk"></td>`;
					}
					else if (val.p_status == 'C') {
						status = `<td class="pad-1"><input type="text" name="statusPr[]" class="form-control-plaintext statusPr onlyread w-100" value="Cuti"></td>`;
					}

					let iNote = null;
					(val.p_note == null || val.p_note == '') ? iNote = '' : iNote = val.p_note;
					let note = `<td class="pad-1"><textarea name="notePr[]" rows="2" class="w-100" readonly>`+ iNote +`</textarea></td>`;

					let row = '<tr>'+ empId + arriveTime + returnTime + status + note +'</tr>'
			        $('#table_detail_presence tbody').append(row);
					// get recently added item to update read-only for 'tidak masuk'
					if ($('.statusPr').filter(':last').val() == 'T') {
						let idxTemp = $('.statusPr').index(this);
						$('.arriveTimePr').eq(idxTemp).attr('readonly', true);
						$('.returnTimePr').eq(idxTemp).attr('readonly', true);
					}
				});
				$('#branchPrDetail').val(resp[0].get_employee.get_company.c_name);
				$('#dateNowPrDetail').val(resp[0].p_date);
				$('#modalDetail').modal('show');
			},
			error: function(e) {
				messageWarning('Error', 'Error getDataPresence: '+ e.message);
			}
		});
	}
</script>
@endsection
