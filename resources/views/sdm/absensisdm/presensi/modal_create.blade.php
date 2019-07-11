<!-- Modal -->
@section('extra_style')
<style type="text/css">
.pad-1 {
	padding: 1px !important;
}
.w-100 {
	width: 100%;
}
</style>
@endsection
<div id="modalCreate" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Tambah Data Presensi</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form id="presenceForm">
					<section>
						<div class="row col-md-12 col-sm-12">
							<div class="col-md-2 col-sm-12">
								<label for="">Cabang</label>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<select name="branch" id="branchPr" class="form-control form-control-sm select2">
										<option value="" selected>Semua Cabang</option>
									</select>
								</div>
							</div>

							<div class="col-md-2"></div>

							<div class="col-md-2 col-sm-12">
								<label for="">Divisi</label>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<select name="division" id="divisionPr" class="form-control form-control-sm select2">
										<option value="" selected>Semua Divisi</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row col-md-12 col-sm-12">
							<div class="col-md-2 col-sm-12">
								<label for="">Tanggal</label>
							</div>
							<div class="col-md-3 col-sm-12">
								<div class="form-group">
									<input type="text" class="form-control form-control-sm datepicker dateNowPr" name="datePr">
								</div>
							</div>
						</div>
					</section>
					<section>
						<div class="table-responsive">
							<table class="table table-sm table-hover table-striped table-bordered display nowrap" cellspacing="0" cellp style="width: 100%" id="table_presence">
								<thead class="bg-primary">
									<tr>
										<th class="text-center">Nama</th>
										<th style="width: 10%" class="text-center">Datang</th>
										<th style="width: 10%" class="text-center">Pulang</th>
										<th style="width: 10%" class="text-center">Status</th>
										<th style="width: 20%" class="text-center">Note</th>
										<th style="width: 10%" class="text-center">aksi</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td class="pad-1">
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
											<button class="btn btn-success btn-sm rounded-circle btnAddEmployee" style="color:white;" type="button">
												<i class="fa fa-plus" aria-hidden="true"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="btnSimpanPresence">Simpan</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
