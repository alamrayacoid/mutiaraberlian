<!-- Modal -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Data Cashflow</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form action="" id="form_update">
      		@csrf
	        <div class="row">
	        	<div class="col-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label for="edit_nama">Nama Cashflow</label>
								<input type="text" name="ac_nama" class="form-control form-control-sm" id="edit_nama">
								<input type="hidden" name="ac_id" id="ac_id">
							</div>
						</div>
						<div class="col-12 col-md-12 col-sm-12">
			        <label for="edit_type">Type Cashflow</label>
			        <select name="ac_type" id="edit_type" class="form-control form-control-sm select2">
			          <option value="OCF">OCF</option>
			          <option value="ICF">ICF</option>
			          <option value="FCF">FCF</option>
			        </select>
			      </div>
	        </div>
				</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updateCashflow()">Simpan</button>
      </div>
    </div>
  </div>
</div>