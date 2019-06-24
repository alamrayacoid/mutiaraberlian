<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Tambah Data Jenis Produk</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="inputPassword" class="col-sm-3 col-form-label">Nama Jenis</label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm" name="jenis" id="jenis" style="text-transform: uppercase">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="savejenis" onclick="savejenis()">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div>
		</div>
	</div>
</div>
