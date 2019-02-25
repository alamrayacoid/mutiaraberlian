					<div class="tab-pane fade in active show" id="list">
						<div class="card">
	                        <div class="card-header bordered p-2">
	                        	<div class="header-block">
		                            <h3 class="title"> Penggunaan Bahan Baku </h3>
		                        </div>
		                        <div class="header-block pull-right">
	                    			<a class="btn btn-primary" href="{{route('tambah_penggunaanbahanbaku')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
		                        </div>
	                        </div>
		                    <div class="card-block">
		                        <section>

		                        	<div class="row">

			                            <div class="col-md-3 col-sm-12">
			                              <label class="font-weight-bold">Tanggal Penggunaan</label>
			                            </div>

			                            <div class="col-md-6 col-sm-12">
			                              <div class="form-group">
			                                <div class="input-group input-group-sm input-daterange">
			                                  <input type="text" class="form-control" name="">
			                                  <span class="input-group-addon">-</span>
			                                  <input type="text" class="form-control" name="">
			                                  <div class="input-group-append">
			                                  	<button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
			                                  	<button class="btn btn-secondary" type="button"><i class="fa fa-refresh"></i></button>
			                                  </div>
			                                </div>
			                              </div>
			                            </div>
			                        	

			                    	</div>
		                        	
		                        	<div class="table-responsive">
			                            <table class="table data-table table-hover table-striped table-bordered" cellspacing="0">
			                                <thead class="bg-primary">
			                                    <tr>
									                <th width="1%">No</th>
									                <th>Tanggal</th>
									                <th>Kode</th>
									                <th>Staff</th>
									                <th>Gudang</th>
									                <th>Peminta</th>
									                <th>Keperluan</th>
									                <th align="center">Aksi</th>
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