<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<span class="modal-title keuangan" id="myModalLabel">Pilih mutasi yang akan diperbarui</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<vue-datatable :resource="data_table_transaksi" @selected="dataSelected"></vue-datatable>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div> -->
		</div>
	</div>
</div>

<div class="modal fade" id="modal_keterangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 28%;">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<span class="modal-title keuangan" id="myModalLabel">Pilih keterangan transaksi</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<vue-datatable :resource="data_table_keterangan" @selected="keteranganSelected"></vue-datatable>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div> -->
		</div>
	</div>
</div>
