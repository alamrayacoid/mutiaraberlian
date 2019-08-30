@extends('main')

@section('content')

<article class="content">

    @include('sdm.absensisdm.harilibur.modal')
    @include('sdm.absensisdm.kehadiran.modal')
    @include('sdm.absensisdm.cuti.modal')

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
                    @include('sdm.absensisdm.kehadiran.index')
                    @include('sdm.absensisdm.harilibur.index')
                    @include('sdm.absensisdm.cuti.index')
				</div>
			</div>
		</div>
	</section>
</article>
@endsection
@section('extra_script')
<!-- public set time -->
<script type="text/javascript">
    var table_harilibur;
    var table_aturankehadiran;
    var table_jeniscuti;
		
		$(document).ready(function() {
		var cur_date = new Date();
		const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		// date for 'Index Daftar Presensi SDM'
		$('#filterDateFromPr').datepicker('setDate', first_day);
		$('#filterDateToPr').datepicker('setDate', last_day);
		// date for 'Create Daftar Presensi SDM'
		$('.dateNowPr').datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate()));

        table_harilibur = $('#table_harilibur').DataTable({
            ordering: false
        });

        setTimeout(function(){
            table_aturankehadiran = $('#table_aturankehadiran').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("absensisdm.getDataAturanKehadiran") }}',
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'ar_rules', name: 'ar_rules'},
                    {data: 'ar_punishment', name: 'ar_punishment'},
                    {data: 'ar_note', name: 'ar_note'},
                    {data: 'aksi', name: 'aksi'},
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        },1000)

        setTimeout(function(){
            table_jeniscuti = $('#table_kelolacuti').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("absensisdm.getDataJenisCuti") }}',
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'l_name', name: 'l_name'},
                    {data: 'l_longleave', name: 'l_longleave'},
                    {data: 'l_note', name: 'l_note'},
                    {data: 'aksi', name: 'aksi'},
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
            table_jeniscuti.columns.adjust();
        },800)

	});

    $('.suffixhari').inputmask("decimal", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        suffix: ' Hari',
        rightAlign: true,
        autoUnmask: true,
        // unmaskAsNumber: true,
        suffix:" Hari",
        definitions: {
            a: { validator: "" }
        },
        onBeforeMask: function (value, opts) {
            return value;
        }
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
	function showDetailPresence(id, p_date)
	{
		$.ajax({
			url: "{{ route('presensi.getDetailPresence') }}",
			data: {
				id: id,
				tanggal: p_date
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

// Hari Libur

    $("#tahun_libur").datepicker( {
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });

    $("#tanggal_libur").datepicker( {
        format: "dd-mm-yyyy",
        autoclose: true
    });

    function simpanHariLibur(){
        loadingShow();
        let tgl = $('#tanggal_libur').val();
        let note = $('#keterangan_libur').val();
        if (tgl == '' || tgl == null) {
            messageWarning("Perhatian", "Tanggal kosong");
            return false;
        }
        if (note == '' || note == null) {
            messageWarning("Perhatian", "Keterangan Kosong");
        }
        axios.post('{{ route("absensisdm.saveHariLibur") }}', {
            "_token": '{{ csrf_token() }}',
            "note": note,
            "tgl": tgl
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                $('#modal_createharilibur').modal('hide');
                cariHariLibur();
            } else if (response.data.status == 'gagal') {
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function cariHariLibur(){
        loadingShow();
        let bulan = $('#bulan_libur').val();
        let tahun = $('#tahun_libur').val();

        axios.get('{{ route("absensisdm.cariHariLibur") }}', {
            params: {
                bulan: bulan,
                tahun: tahun
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_harilibur.clear().draw();
            $.each(data, function(idx, val){
                table_harilibur.row.add([
                    val.tanggal,
                    val.hd_note,
                    '<center><button type="button" class="btn btn-primary btn-sm" onclick="editLibur('+val.hd_id+')">Edit</button>'+
                    '<button type="button" class="btn btn-warning btn-sm" onclick="hapusLibur('+val.hd_id+')">Hapus</button></center>'
                ]).draw(false);
                table_harilibur.columns.adjust();
            })
        }).catch(function(error){
            loadingHide();
            alert('error');
        });
    }

    function editLibur(id){
        loadingShow();
        axios.get('{{ route("absensisdm.getDetailHariLibur") }}', {
            params:{
                "id": id
            }
        }).then(function(response){
            loadingHide();
            $("#edittanggal_libur").val(response.data.hd_date);
            $('#editketerangan_libur').val(response.data.hd_note);
            $('#modal_editharilibur').modal('show');
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function updateHariLibur(){
        loadingShow();
        let tanggal = $('#edittanggal_libur').val();
        let note = $('#editketerangan_libur').val();
        axios.get('{{ route("absensisdm.updateDetailHariLibur") }}', {
            params:{
                "tanggal": tanggal,
                "note": note
            }
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil diperbarui");
                $('#modal_editharilibur').modal('hide');
                cariHariLibur();
            } else if (response.data.status == 'gagal') {
                messageDanger("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function hapusLibur(id){
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: "{{ route('absensisdm.hapusHariLibur') }}",
                            data: {
                                "id": id
                            },
                            success: function (response) {
                                if (response.status == 'sukses') {
                                    messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                    cariHariLibur();
                                } else if (response.status == 'gagal'){
                                    messageWarning('Perhatian', response.message);
                                }
                            },
                            error: function (e) {
                                messageFailed('Peringatan', e.message);
                            }
                        });
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

// Kelola aturan kehadiran
    function simpanAturanKehadiran(){
        loadingShow();
        let rule = $('#aturan_kehadiran').val();
        let punishment = $('#hukuman_kehadiran').val();
        let note = $('#note_kehadiran').val();

        if (rule == '' || rule == null) {
            messageWarning("Perhatian", "Aturan tidak boleh kosong");
            return false;
        }
        if (punishment == '' || punishment == null) {
            messageWarning("Perhatian", "Hukuman tidak boleh kosong");
            return false;
        }

        axios.post('{{ route("absensisdm.saveAturanKehadiran") }}', {
            rule: rule,
            punishment: punishment,
            note: note,
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                $('#modal_tambahaturan').modal('hide');
                table_aturankehadiran.ajax.reload();
            } else if (response.data.status == 'gagal') {
                messageFailed("Perhatian", "Data gagal disimpan");
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function editAturanKehadiran(id){
        loadingShow();
        axios.get('{{ route("absensisdm.getDetailAturanKehadiran") }}', {
            params:{
                id: id
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            $('#edit_idaturan').val(data.ar_id);
            $('#editaturan_kehadiran').val(data.ar_rules);
            $('#edithukuman_kehadiran').val(data.ar_punishment);
            $('#editnote_kehadiran').val(data.ar_note);
            $('#modal_editaturan').modal('show');
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function updateAturanKehadiran(){
        loadingShow();
        let rule = $('#editaturan_kehadiran').val();
        let punishment = $('#edithukuman_kehadiran').val();
        let note = $('#editnote_kehadiran').val();

        if (rule == '' || rule == null) {
            messageWarning("Perhatian", "Aturan tidak boleh kosong");
            return false;
        }
        if (punishment == '' || punishment == null) {
            messageWarning("Perhatian", "Hukuman tidak boleh kosong");
            return false;
        }

        axios.post('{{ route("absensisdm.updateAturanKehadiran") }}', {
            rule: rule,
            punishment: punishment,
            note: note,
            id: $('#edit_idaturan').val(),
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                $('#modal_editaturan').modal('hide');
                table_aturankehadiran.ajax.reload();
            } else if (response.data.status == 'gagal') {
                messageFailed("Perhatian", "Data gagal disimpan");
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function hapusAturanKehadiran(id){
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        return $.ajax({
                            type: "post",
                            url: "{{ route('absensisdm.hapusAturanKehadiran') }}",
                            data: {
                                "id": id,
                                "_token": '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                loadingHide();
                                if (response.status == 'sukses') {
                                    messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                    table_aturankehadiran.ajax.reload();
                                } else if (response.status == 'gagal'){
                                    messageWarning('Perhatian', response.message);
                                }
                            },
                            error: function (e) {
                                loadingHide();
                                messageFailed('Peringatan', e.message);
                            }
                        });
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

// Kelola jenis cuti
    function simpanJenisCuti(){
        loadingShow();
        let nama = $('#nama_cuti').val();
        let lama = $('#lama_cuti').val();
        let note = $('#note_cuti').val();
        if (nama == '' || nama == null) {
            messageWarning("Perhatian", "Form tidak boleh kosong");
            return false;
        }
        if (lama == '' || lama == null) {
            messageWarning("Perhatian", "Form tidak boleh kosong");
            return false;
        }

        axios.post('{{ route("absensisdm.saveDataJenisCuti") }}', {
            "nama": nama,
            "lama": lama,
            "note": note,
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess('Berhasil', 'Data berhasil disimpan');
                $('#modal_tambahcuti').modal('hide');
                table_jeniscuti.ajax.reload();
                table_jeniscuti.columns.adjust();
            } else if (response.data.status == 'gagal') {
                messageWarning('Gagal', response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function editJenisCuti(id){
        loadingShow();
        axios.get('{{ route("absensisdm.getDetailCuti") }}', {
            params:{
                "id": id
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            $('#editnama_cuti').val(data.l_name);
            $('#editlama_cuti').val(data.l_longleave);
            $('#editid_cuti').val(data.l_id);
            $('#editnote_cuti').val(data.l_note);
            $('#modal_editcuti').modal('show');
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function updateJenisCuti(){
        loadingShow();
        let nama = $('#editnama_cuti').val();
        let lama = $('#editlama_cuti').val();
        let note = $('#editnote_cuti').val();
        let id = $('#editid_cuti').val();
        if (nama == '' || nama == null) {
            messageWarning("Perhatian", "Form tidak boleh kosong");
            return false;
        }
        if (lama == '' || lama == null) {
            messageWarning("Perhatian", "Form tidak boleh kosong");
            return false;
        }

        axios.post('{{ route("absensisdm.updateJenisCuti") }}', {
            "nama": nama,
            "lama": lama,
            "id": id,
            "note": note,
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess('Berhasil', 'Data berhasil disimpan');
                $('#modal_editcuti').modal('hide');
                table_jeniscuti.ajax.reload();
                table_jeniscuti.columns.adjust();
            } else if (response.data.status == 'gagal') {
                messageWarning('Gagal', response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function hapusJenisCuti(id){
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        return $.ajax({
                            type: "post",
                            url: "{{ route('absensisdm.hapusJenisCuti') }}",
                            data: {
                                "id": id,
                                "_token": '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                loadingHide();
                                if (response.status == 'sukses') {
                                    messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                    table_jeniscuti.ajax.reload();
                                    table_jeniscuti.columns.adjust();
                                } else if (response.status == 'gagal'){
                                    messageWarning('Perhatian', response.message);
                                }
                            },
                            error: function (e) {
                                loadingHide();
                                messageFailed('Peringatan', e.message);
                            }
                        });
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

    $('#btnRefreshTable').on('click', function(){
        getPresenceSummary();
    });
</script>
@endsection
