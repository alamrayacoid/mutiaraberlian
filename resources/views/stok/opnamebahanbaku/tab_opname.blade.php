					<div class="tab-pane fade in active show" id="opname">
						<div class="card">
	                        <div class="card-header bordered p-2">
	                        	<div class="header-block">
		                            <h3 class="title"> Opname Bahan Baku </h3>
		                        </div>
	                        </div>
		                    <div class="card-block">
		                        <section>
		                        	
		                        	<fieldset class="mb-3">
		                        		<div class="row">

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <label>Pemilik Item</label>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <div class="form-group">
		                                  <select class="form-control form-control-sm select2">
		                                  	<option value="">--Pilih--</option>
		                                  </select>
		                                </div>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <label>Posisi Item</label>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <div class="form-group">
		                                  <select class="form-control form-control-sm select2">
		                                  	<option value="">--Pilih--</option>
		                                  </select>
		                                </div>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <label>Tanggal Opname</label>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <div class="form-group">
		                                  <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
		                                </div>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <label>Nama Staff</label>
		                              </div>

		                              <div class="col-md-3 col-sm-6 col-xs-12">
		                                <div class="form-group">
		                                  <input type="text" class="form-control form-control-sm" readonly="" name="">
		                                </div>
		                              </div>

		                        		</div>
		                        	</fieldset>

		                        	<fieldset class="mb-3">
		                        		<div class="row">
		                        			
		                              <div class="col-md-6 col-sm-8 col-xs-12">
		                                <label>Barang</label>
		                                <div class="form-group">
		                                  <select class="form-control form-control-sm select2">
		                                  	<option value="">--Pilih--</option>
		                                  </select>
		                                </div>
		                              </div>

		                              <div class="col-md-3 col-sm-2 col-xs-12">
		                              	<label>Qty Real</label>
		                              	<div class="form-group">
		                              		<div class="input-group">
				                                <input type="number" min="0" class="form-control-sm form-control" name="">
				                                <div class="input-group-append">
				                                	<button class="btn btn-sm btn-primary" title="Tambah" type="button"><i class="fa fa-plus"></i></button>
				                                </div>
				                            </div>
		                                </div>
		                              </div>

		                              <div class="col-md-3 col-sm-2 col-xs-12">
		                              	<label>Qty Sistem</label>
		                              	<div class="form-group">
		                                  <input type="number" readonly="" class="form-control-sm form-control" name="">
		                                </div>
		                              </div>
		                        		</div>
		                        	</fieldset>

		                        	<div class="table-responsive">
			                            <table class="table data-table table-hover" cellspacing="0">
			                                <thead class="bg-primary">
			                                    <tr>
									                <th>Kode | Item</th>
									                <th>Qty Sistem</th>
									                <th>Satuan</th>
									                <th>Qty Real</th>
									                <th>Opname</th>
									                <th>Aksi</th>
									            </tr>
			                                </thead>
			                                <tbody>

									        </tbody>
			                            </table>
			                        </div>
		                        </section>
		                    </div>
		                    <div class="card-footer text-right">
		                    	<button class="btn btn-primary" type="button">Simpan</button>
		                    </div>
		                </div>

		            </div>