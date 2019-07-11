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

<div class="tab-pane fade in show" id="presensi">
	@include('sdm.absensisdm.presensi.modal_create')
	@include('sdm.absensisdm.presensi.modal_detail')

	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Daftar Presensi SDM</h3>
			</div>
			<div class="header-block pull-right">
				<button class="btn btn-primary" id="btnCreate" data-toggle="modal" data-target="#modalCreate"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="filterDateFromPr" name="filterDateFromPr">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="filterDateToPr" name="filterDateToPr">
							<div class="input-group-append">
								<button class="btn btn-primary" type="button" id="brnRefreshDatePr"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-1 col-sm-12">
						<!-- empty -->
					</div>
					<div class="col-md-5 col-sm-6">
						<div class="row col-md-12 col-sm-12">
							<div class="col-md-3 col-sm-12">
								<label for="">Cabang</label>
							</div>
							<div class="col-md-9 col-sm-12">
								<div class="form-group">
									<select name="filterByBranch" id="filterByBranch" class="form-control form-control-sm select2">
										<option value="" selected>Semua Cabang</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover data-table table-striped table-bordered display nowrap" cellspacing="0" style="width: 100%" id="table_presensi_sdm">
						<thead class="bg-primary">
							<tr>
								<th class="text-center" rowspan="2">No</th>
								<th class="text-center" rowspan="2">Tanggal</th>
								<th class="text-center" colspan="4">Status</th>
								<th class="text-center" rowspan="2">Aksi</th>
							</tr>
							<tr>
								<th class="text-center">Hadir</th>
								<th class="text-center">Ijin</th>
								<th class="text-center">Tidak masuk</th>
								<th class="text-center">Cuti</th>
							</tr>
						</thead>
						<!-- <thead class="bg-primary">
						</thead> -->
						<tbody>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>

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
			$("#table_presence > tbody").find("tr:gt(0)").remove();
		});
		// reset form on hidden modal
		$('#modalCreate').on('shown.bs.modal', function() {
			// var cur_date = new Date();
			// const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            // const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
			// $('.dateNowPr').datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate()));
			$('#branchPr').selectedIndex = 0;
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
				<input type="text" name="arriveTimePr[]" class="arriveTimePr w-100" value="">
			</td>
			<td class="pad-1">
				<input type="text" name="returnTimePr[]" class="returnTimePr w-100" value="">
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

					let aTime = '';
					let rTime = '';
					let h = `<option value="H">Hadir</option>`;
					let i = `<option value="I">Izin</option>`;
					let t = `<option value="T">Tidak Masuk</option>`;
					let c = `<option value="C">Cuti</option>`;
					let iNote = '';
					let action;

					if (val.get_presence.length > 0) {
						(val.get_presence[0].p_entry == null) ? aTime = '' : aTime = val.get_presence[0].p_entry;
						(val.get_presence[0].p_out == null) ? rTime = '' : rTime = val.get_presence[0].p_out;
						h = (val.get_presence[0].p_status == 'H') ? `<option value="H" selected>Hadir</option>` : `<option value="H">Hadir</option>`;
						i = (val.get_presence[0].p_status == 'I') ? `<option value="I" selected>Ijin</option>` : `<option value="I">Ijin</option>`;
						t = (val.get_presence[0].p_status == 'T') ? `<option value="T" selected>Tidak Masuk</option>` : `<option value="T">Tidak Masuk</option>`;
						c = (val.get_presence[0].p_status == 'C') ? `<option value="C" selected>Cuti</option>` : `<option value="C">Cuti</option>`;
						(val.get_presence[0].p_note == null) ? iNote = '' : iNote = val.get_presence[0].p_note;
					}
					let arriveTime = `<td class="pad-1"><input type="text" name="arriveTimePr[]" class="arriveTimePr w-100" value="`+ aTime +`"></td>`;
					let returnTime = `<td class="pad-1"><input type="text" name="returnTimePr[]" class="returnTimePr w-100" value="`+ rTime +`"></td>`;
					let status = `<td class="pad-1"><select name="statusPr[]" class="statusPr w-100">` + h + i + t + c + `</select></td>`;
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

					let row = '<tr>'+ empId + arriveTime + returnTime + status + note + action +'</tr>'
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
		// $('#filterDateFromPr').datepicker('setDate', first_day);
		// $('#filterDateToPr').datepicker('setDate', last_day);

		// call function when filter activated
		$('#filterByBranch').on('change select2:select', function() {
			getPresenceSummary();
		});
		$('#filterDateFromPr').on('change', function() {
			getPresenceSummary();
		});
		$('#filterDateToPr').on('change', function() {
			getPresenceSummary();
		})
	});
	// get list branch for filter-by-branch
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
