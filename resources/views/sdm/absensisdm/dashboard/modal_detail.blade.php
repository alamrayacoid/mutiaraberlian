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
<div id="modalDetailDashboard" class="modal fade" role="dialog">
	<div class="modal-dialog modal-x2">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Detail Data Presensi <span id="emp_name"></span> Selama Satu Bulan</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section>
					<div class="table-responsive">
						<table class="table table-sm table-hover table-bordered display" cellspacing="0" cellp style="width: 100%" id="table_detail_absen_pegawai">
							<thead class="bg-primary">
								<tr>
									<th style="width: 10%" class="text-center">Tanggal</th>
									<th style="width: 10%" class="text-center">Datang</th>
									<th style="width: 10%" class="text-center">Pulang</th>
									<th style="width: 10%" class="text-center">Status</th>
									<th style="width: 20%" class="text-center">Note</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</section>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
