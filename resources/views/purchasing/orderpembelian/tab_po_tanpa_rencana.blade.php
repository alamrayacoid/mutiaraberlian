<div class="tab-pane fade in" id="po_tanpa">
	<div class="card">
        <div class="card-header bordered p-2">
        	<div class="header-block">
                <h3 class="title">Order Pembelian Tanpa Rencana </h3>
            </div>
            <div class="header-block pull-right">
            	
    			<a class="btn btn-primary" href="{{route('tambah_orderpembelian_tanparencana')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
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
                	

            	</div>

            	
                	<div class="table-responsive">
                		
                		<table class="table table-hover table-striped data-table" cellspacing="0">
                			<thead class="bg-primary">
                				<tr>
                					<th>No</th>
                					<th>Tgl Order</th>
                					<th>No Order</th>
                					<th>Staff</th>
                					<th>Suplier</th>
                					<th>Cara Bayar</th>
                					<th>Total</th>
                					<th>Tgl Kirim</th>
                					<th>Status</th>
                					<th>Aksi</th>
                				</tr>
                			</thead>

                			<tbody>
                				<tr>
                					<td>1</td>
                					<td>2 Jan 2019</td>
                					<td>PO/201901/1</td>
                					<td>Administrator</td>
                					<td>Alpha</td>
                					<td>TUNAI</td>
                					<td>2.000</td>
                					<td>3 Jan 2019</td>
                					<td>
                						<span class="badge badge-success badge-pill">Diterima</span>
                					</td>
                					<td>
                						<div class="btn-group btn-group-sm">
                							<button class="btn btn-info btn_order_detail" type="button" title="Detail"><i class="fa fa-list"></i></button>
                							<button class="btn btn-warning" disabled="" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                							<button class="btn btn-danger" disabled="" type="button" title="Delete"><i class="fa fa-trash-o"></i></button>
                						</div>
                					</td>
                				</tr>
                			</tbody>
                		</table>

                	</div>
                	
            </section>
        </div>
    </div>

</div>