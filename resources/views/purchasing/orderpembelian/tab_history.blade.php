<div class="tab-pane fade in" id="list_suplier">
	<div class="card">
        <div class="card-header bordered p-2">
        	<div class="header-block">
                <h3 class="title"> History Order Pembelian </h3>
            </div>
        </div>
        <div class="card-block">
            <section>
            	
            	<div class="row">

                    <div class="col-md-3 col-sm-12">
                      <label class="font-weight-bold">Tanggal Order</label>
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

                    <div class="col-md-3 col-sm-12">
                    	<div class="form-group">
                    		<select name="tampilData" id="tampil_data" class="form-control form-control-sm">
								<option value="wait">Tampilkan Data : Waiting</option>
								<option value="edit">Tampilkan Data : Dapat diedit</option>
								<option value="confirm">Tampilkan Data : Disetujui</option>
								<option value="received">Tampilkan Data : Diterima</option>
								<option value="revisied">Tampilkan Data : Ada Revisi</option>
					        </select>
                    	</div>
                    </div>
                	

            	</div>

            	
                	<div class="table-responsive">
                		
                		<table class="table table-hover table-striped data-table" cellspacing="0">
                			<thead class="bg-primary">
                				<tr>
                					<th>No</th>
                					<th>No Order</th>
                					<th>Nama Barang</th>
                					<th>Satuan</th>
                					<th>Suplier</th>
                					<th>Tgl Permintaan</th>
                					<th>Qty</th>
                					<th>Tgl Masuk</th>
                					<th>Qty Masuk</th>
                					<th>Status</th>
                					<th>Aksi</th>
                				</tr>
                			</thead>

                			<tbody>
                				<tr>
                					<td>1</td>
                					<td>PO/201901/1</td>
                					<td>Pasir</td>
                					<td>Truk/Colt</td>
                					<td>Delta</td>
                					<td>03 Jan 2019</td>
                					<td>1</td>
                					<td>04 Jan 2019</td>
                					<td>1</td>
                					<td><span class="badge badge-pill badge-success">Diterima</span></td>
                					<td>
                						<button class="btn btn-info btn-sm" type="button" title="Detail"><i class="fa fa-list"></i></button>
                					</td>
                				</tr>
                			</tbody>
                		</table>

                	</div>
                	
            </section>
        </div>
    </div>
</div>