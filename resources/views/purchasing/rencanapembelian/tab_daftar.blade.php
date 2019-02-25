<div class="tab-pane fade in active show" id="daftar_rencana">
	<div class="card">
        <div class="card-header bordered p-2">
        	<div class="header-block">
                <h3 class="title"> Daftar Rencana Pembelian </h3>
            </div>
            <div class="header-block pull-right">
    			<a class="btn btn-primary" href="{{route('tambah_rencanapembelian')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
    		</div>
        </div>
        <div class="card-block">
            <section>
            	
            	<div class="row">

                    <div class="col-md-3 col-sm-12">
                      <label class="font-weight-bold">Tanggal Rencana</label>
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
                					<th>Tgl Dibuat</th>
                					<th>Kode Rencana</th>
                					<th>Staff</th>
                					<th>Suplier</th>
                					<th>Tgl Disetujui</th>
                					<th>Status</th>
                					<th>Aksi</th>
                				</tr>
                			</thead>

                			<tbody>
                				<tr>
                					<td>1</td>
                					<td>29 Des 2018</td>
                					<td>ReP/201812/1</td>
                					<td>Administrator</td>
                					<td>Alpha</td>
                					<td>30 Des 2018</td>
                					<td>
                						<span class="badge badge-info ">Waiting</span>
                					</td>
                					<td>
                						<div class="btn-group btn-group-sm">
                							<button type="button" class="btn btn-info rencana_detail" title="Detail"><i class="fa fa-list"></i></button>
                							<button type="button" class="btn btn-warning rencana_edit" title="Edit"><i class="fa fa-pencil"></i></button>
                							<button type="button" class="btn btn-danger rencana_hapus" title="Delete"><i class="fa fa-trash-o"></i></button>
                						</div>
                					</td>
                				</tr>
                				<tr>
                					<td>2</td>
                					<td>30 Des 2018</td>
                					<td>ReP/201812/1</td>
                					<td>Administrator</td>
                					<td>Bravo</td>
                					<td>31 Des 2018</td>
                					<td>
                						<span class="badge badge-success">Disetujui</span>
                					</td>
                					<td>
                						<div class="btn-group btn-group-sm">
                							<button type="button" class="btn btn-info" disabled="" title="Detail"><i class="fa fa-list"></i></button>
                							<button type="button" class="btn btn-warning" disabled="" title="Edit"><i class="fa fa-pencil"></i></button>
                							<button type="button" class="btn btn-danger" disabled="" title="Delete"><i class="fa fa-trash-o"></i></button>
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