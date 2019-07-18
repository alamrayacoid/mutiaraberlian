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
<div id="modalDetail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Detail Data Presensi</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section>
					<div class="row col-md-12 col-sm-12">
						<div class="col-md-2 col-sm-12">
							<label for="">Cabang</label>
						</div>
						<div class="col-md-3 col-sm-12">
							<div class="form-group">
								<input type="text" class="form-control-plaintext border-bottom onlyread" id="branchPrDetail">
							</div>
						</div>

						<div class="col-md-2 col-sm-12"></div>

						<div class="col-md-2 col-sm-12">
							<label for="">Tanggal</label>
						</div>
						<div class="col-md-3 col-sm-12">
							<div class="form-group">
								<input type="text" class="form-control-plaintext border-bottom onlyread" id="dateNowPrDetail">
							</div>
						</div>
					</div>
					<!-- <div class="row col-md-12 col-sm-12">
					</div> -->
				</section>
				<section>
					<div class="table-responsive">
						<table class="table table-sm table-hover table-bordered display" cellspacing="0" cellp style="width: 100%" id="table_detail_presence">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">Nama</th>
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
