<!-- Modal -->
<div class="modal fade" id="detailKpiPegawai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail KPI Pegawai</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
					<div class="col-3 col-md-3">
						<label for="m_employee">Nama Pegawai</label>
					</div>
					<div class="col-9 col-md-9 mb-2">
						<div id="m_employee" class="w-100 border-bottom" style="height: 30px;"></div>
					</div>
					<div class="col-3 col-md-3">
						<label for="m_divisi">Divisi</label>
					</div>
					<div class="col-9 col-md-9 mb-2">
						<div id="m_divisi" class="w-100 border-bottom" style="height: 30px;"></div>
					</div>
					<div class="col-3 col-md-3">
						<label for="m_posisi">Posisi</label>
					</div>
					<div class="col-9 col-md-9 mb-2">
						<div id="m_posisi" class="w-100 border-bottom" style="height: 30px;"></div>
					</div>
        </div>
        <div class="row">
        	<div class="col-12 col-md-12 col-sm-12">
        		<table class="table table-bordered table-hover table-striped w-100" id="tb_detail_kpi_pegawai" cellspacing="0">
        			<thead>
        			  <tr>
        			    <th class="th-number">No.</th>
        			    <th>Nama Indikator</th>
                  <th>Bobot</th>
                  <th>Target</th>
        			  </tr>
        			</thead>
        			<tbody>
        			  
        			</tbody>
        		</table>
        	</div>
        </div>
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}
    </div>
  </div>
</div>