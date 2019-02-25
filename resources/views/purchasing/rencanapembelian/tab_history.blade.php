<div class="tab-pane fade in" id="history_rencana">
	<div class="card">
		<div class="card-header bordered p-3">
			<div class="header-block">
				<h3 class="title">History Rencana Pembelian</h3>
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

                    <div class="col-md-3 col-sm-12">
                    	<div class="form-group">
                    		<select name="tampilData" id="tampil_data" class="form-control form-control-sm">
								<option value="wait">Tampilkan Data : Waiting</option>
								<option value="edit">Tampilkan Data : Dapat diedit</option>
								<option value="confirm">Tampilkan Data : Disetujui</option>
					        </select>
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
            				</tr>
            			</tbody>
            		</table>

            	</div>
			</section>
		</div>
	</div>
</div>