                	<div class="tab-pane fade in active show" id="pos">
						<div class="card">
	                        <div class="card-header bordered p-2">
	                        	<div class="header-block">
		                            <h3 class="title"> Form Penjualan Project </h3>
		                        </div>
	                        </div>
		                    <div class="card-block">
		                        <section>
		                        	
		                        	<fieldset class="mb-3">
			                        	<div class="row">
			                        		<div class="col-md-9 col-sm-12">
			                        			<div class="row">
					                        		<div class="col-lg-12">
					                        			<label>Nama Customer</label>
					                        			<div class="form-group">
					                        				<select class="form-control form-control-sm select2">
					                        					<option value="">--Pilih--</option>
					                        				</select>
					                        			</div>
					                        		</div>

					                        		<div class="col-lg-12">
					                        			<label>Alamat</label>
					                        			<div class="form-group">
					                        				<input type="text" class="form-control form-control-sm" name="" readonly="">
					                        			</div>
					                        		</div>

					                        		<div class="col-lg-12">
					                        			<label>Nama Project</label>
					                        			<div class="form-group">
					                        				<input type="text" class="form-control form-control-sm" name="">
					                        			</div>
					                        		</div>

					                        		<div class="col-lg-12">
					                        			<label>Keterangan Project</label>
					                        			<div class="form-group">
					                        				<textarea class="form-control" name=""></textarea>
					                        			</div>
					                        		</div>
					                        	</div>
				                        	</div>
				                        	<div class="col-md-3 col-sm-12">
				                        		<div class="row">
					                        		<div class="col-lg-12">
					                        			<label>Tanggal Project</label>
					                        			<div class="form-group">
					                        				<div class="input-group">
						                        				<input type="text" class="form-control form-control-sm" value="{{date('d-m-Y')}}" name="">
						                        				<div class="input-group-append">
						                        					<button class="btn btn-primary btn-sm" type="button"><i class="fa fa-calendar"></i></button>
						                        				</div>
						                        			</div>
					                        			</div>
					                        		</div>

					                        		<div class="col-lg-12">
					                        			<label>Jatuh Tempo</label>
					                        			<div class="form-group">
					                        				<input type="text" class="form-control form-control-sm datepicker" name="">
					                        			</div>
					                        		</div>
					                        	</div>
				                        	</div>

			                        	</div>
			                        </fieldset>

			                        <fieldset class="mb-3">
			                        	<div class="row">

			                        		<div class="col-md-6 col-sm-12">
			                        			<label>Pilih Barang / Jasa</label>
			                        			<div class="form-group">
			                        				<select class="select2 form-control-sm form-control" id="barang">
			                        					<option value="">--Pilih--</option>
			                        					<optgroup label="Barang">
			                        						<option value="1">Semen</option>
			                        					</optgroup>
			                        					<optgroup label="Jasa">
			                        						<option value="2">Pasang</option>
			                        					</optgroup>
			                        				</select>
			                        			</div>
			                        		</div>

			                        		<div class="col-md-3 col-sm-12">
			                        			<label>Qty</label>
			                        			<div class="form-group">
			                        				<div class="input-group">
				                        				<input type="number" min="0" id="qty" class="form-control form-control-sm" name="">
				                        				<div class="input-group-append">
				                        					<button class="btn btn-primary btn-sm btn-tambah" type="button" title="Tambah"><i class="fa fa-plus"></i></button>
				                        				</div>
				                        			</div>
			                        			</div>
			                        		</div>

			                        		<div class="col-md-3 col-sm-12">
			                        			<label>Kuantitas Stok</label>
			                        			<div class="form-group">
			                        				<input type="text" readonly="" class="form-control form-control-sm" name="">
			                        			</div>
			                        		</div>

			                        	</div>
			                        </fieldset>

			                        <div class="table-responsive mb-3">
			                        	<table class="table table-hover table-striped table-bordered" id="table_penjualan" cellspacing="0">
			                        		<thead class="bg-primary">
			                        			<tr>
			                        				<th>Nama</th>
			                        				<th width="5%">Jumlah</th>
			                        				<th width="5%">Satuan</th>
			                        				<th>Harga</th>
			                        				<th width="5%">Disc Percent</th>
			                        				<th>Disc Value</th>
			                        				<th>Total</th>
			                        				<th width="1%"></th>
			                        			</tr>
			                        		</thead>
			                        	</table>
			                        </div>

		                        	<div class="row">
		                        		<div class="col-md-4 offset-md-8 col-sm-5 offset-sm-7 col-xs-12">
					                        <fieldset>
			                        			<div class="row">
			                        				<div class="col-md-12">
			                        					<label>Total Penjualan</label>
			                        					<div class="form-group">
			                        						<input type="text" class="form-control form-control-sm" readonly="" name="">
			                        					</div>
			                        				</div>
			                        				<div class="col-md-12">
			                        					<label>Total Discount</label>
			                        					<div class="form-group">
			                        						<input type="text" class="form-control form-control-sm" readonly="" name="">
			                        					</div>
			                        				</div>
			                        				<div class="col-md-12">
			                        					<label>PPn</label>
			                        					<div class="form-group">
			                        						<input type="text" class="form-control form-control-sm" readonly="" name="">
			                        					</div>
			                        				</div>
			                        				<div class="col-md-12">
			                        					<label>Total Amount</label>
			                        					<div class="form-group">
			                        						<input type="text" class="form-control form-control-sm" readonly="" name="">
			                        					</div>
			                        				</div>
			                        			</div>
					                        </fieldset>
		                        		</div>
		                        	</div>

		                        </section>
		                    </div>
		                    <div class="card-footer text-right">
		                	<button class="btn btn-primary" data-toggle="modal" data-target="#proses" type="button">Proses</button>
		                    </div>
		                </div>
		            </div>