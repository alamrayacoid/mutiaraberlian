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
</style>
@endsection

<div class="tab-pane fade in show" id="presensi">
	@include('sdm.absensisdm.presensi.modal_create')

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
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" style="width: 100%" id="table_presensi_sdm">
						<thead class="bg-primary">
							<tr>
								<th class="w-5 text-center">No</th>
								<th class="w-35">Posisi</th>
								<th class="w-15">Tgl Mulai</th>
								<th class="w-15">Tgl Akhir</th>
								<th class="w-15 text-center">Status</th>
								<th class="w-15 text-center">Aksi</th>
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

@section('extra_script')
<!-- script for 'Daftar Presensi SDM' -->
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

		getEventsReady();
		// store data
		$('#btnSimpanPresence').on('click', function() {
			storePr();
		});
		// reset form on hidden modal
		$('#modalCreate').on('hidden.bs.modal', function() {
			$('#presenceForm')[0].reset();
			$("#table_presence > tbody").find("tr:gt(0)").remove();
			$('#branchPr')[0].selectedIndex = 0;
		});
		// reset form on hidden modal
		$('#modalCreate').on('show.bs.modal', function() {
			var cur_date = new Date();
			$('.dateNowPr').datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate()));
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
				<input type="text" name="notePr[]" value="" class="w-100">
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
				$('#branchPr').append('<option value="" selected disabled>=== Pilih Cabang ===</option>');
				$.each(resp, function (idx, val) {
					$('#branchPr').append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
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
		let data = date +'&'+ branch;
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
									<input type="hidden" name="employeePrId[]" class="employeePrId" value="`+ val.get_employee.e_id +`">
									<input type="text" name="employeePr[]" class="employeePr w-100" value="`+ val.get_employee.e_name + ' ('+ val.get_employee.get_division.m_name +') / '+ val.p_employee +`">
								</td>`;

					let aTime = null;
					(val.p_entry == null) ? aTime = '' : aTime = val.p_entry;
					let arriveTime = `<td class="pad-1">
										<input type="text" name="arriveTimePr[]" class="arriveTimePr w-100" value="`+ aTime +`">
										</td>`;

					let rTime = null;
					(val.p_out == null) ? rTime = '' : rTime = val.p_out;
					let returnTime = `<td class="pad-1">
										<input type="text" name="returnTimePr[]" class="returnTimePr w-100" value="`+ rTime +`">
									</td>`;

					let h = (val.p_status == 'H') ? `<option value="H" selected>Hadir</option>` : `<option value="H">Hadir</option>`;
					let i = (val.p_status == 'I') ? `<option value="I" selected>Ijin</option>` : `<option value="I">Ijin</option>`;
					let t = (val.p_status == 'T') ? `<option value="T" selected>Tidak Masuk</option>` : `<option value="T">Tidak Masuk</option>`;
					let c = (val.p_status == 'C') ? `<option value="C" selected>Cuti</option>` : `<option value="C">Cuti</option>`;
					let status = `<td class="pad-1"><select name="statusPr[]" class="statusPr w-100">` + h + i + t + c + `</select></td>`;

					let iNote = null;
					(val.p_note == null) ? iNote = '' : iNote = val.p_note;
					let note = `<td class="pad-1">
									<input type="text" name="notePr[]" value="`+ iNote +`" class="w-100">
								</td>`;

					let action= null;
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
@endsection
