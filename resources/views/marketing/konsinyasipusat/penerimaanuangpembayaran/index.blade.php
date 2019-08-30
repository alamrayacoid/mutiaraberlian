<div class="tab-pane animated fadeIn show" id="penerimaanuangpembayaran">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Penerimaan Uang Pembayaran</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<fieldset>
				<form id="formAddPP" action="#">
					<div class="row">
						<div class="section1 col-md-7 col-sm-12">
							<div class="row">
								<div class="col-md-4 col-sm-12">
									<label for="">Pilih konsigner</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="input-group">
										<input type="text" class="form-control form-control-sm" placeholder="Cari konsigner" id="filter_agent_name_pp" autocomplete="off">
										<input type="hidden" id="filter_agent_code_pp">
										<button class="btn btn-secondary btn-md" title="Cari konsigner" style="border-left:none;" data-toggle="modal" data-target="#modalSearchAgentPP"><i class="fa fa-search"></i></button>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<label for="">Pilih Nota</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<select name="" id="listNotaPP" class="form-control form-control-sm mb-3 select2">
											<option value="">Pilih Nota</option>
										</select>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<label for="">Jenis Penerimaan</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<select name="" id="paymentTypePP" class="form-control form-control-sm mb-3 select2">
											<option value="TUNAI">Tunai</option>
											<option value="TRANSFER">Transfer</option>
										</select>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<label for="">Pilih Akun Kas</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<select name="" id="cashAccountPP" class="form-control form-control-sm mb-3 select2">
											<option value="">== Pilih Akun ==</option>
											@foreach($paymentMethod as $pm)
												<option value="{{ $pm->pm_id }}">{{ $pm->getAkun->ak_nomor }} - {{ $pm->pm_name }}</option>
											@endforeach
											<!-- <option value="KB" selected>Kas Besar</option>
											<option value="KC">Kas Kecil</option>
											<option value="KP">Kas Penjualan Tunai</option> -->
										</select>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<label for="">Keterangan</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<input type="text" id="infoPP" class="form-control form-control-sm mb-3">
								</div>

								<div class="col-md-4 col-sm-12">
									<label for="">Nominal Penerimaan</label>
								</div>
								<div class="col-md-6 col-sm-12">
									<input type="text" id="paymentValPP" class="form-control form-control-sm rupiah">
								</div>
							</div>
						</div>
						<div class="section2 col-md-4 col-sm-12 ml-1">
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<label for="">Total Tagihan</label>
								</div>
								<div class="col-md-7 col-sm-12">
									<input type="text" id="totalBillPP" class="form-control form-control-sm rupiah mb-3" readonly="">
								</div>

								<div class="col-md-5 col-sm-12">
									<label for="">Sudah Dibayar</label>
								</div>
								<div class="col-md-7 col-sm-12">
									<input type="text" id="paidBillPP" class="form-control form-control-sm rupiah mb-3" readonly="">
								</div>

								<div class="col-md-5 col-sm-12">
									<label for="">Sisa Tagihan</label>
								</div>
								<div class="col-md-7 col-sm-12">
									<input type="text" id="restBillPP" class="form-control form-control-sm rupiah mb-3" readonly="">
								</div>
							</div>
						</div>
					</div>
				</form>
			</fieldset>
		</div>
		<div class="card-footer">
			<div class="pull-right">
				<button class="btn btn-primary btn-submit" id="btn_simpanPP">Simpan</button>
			</div>
		</div>
	</div>
</div>
