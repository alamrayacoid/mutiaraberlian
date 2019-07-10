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

<div class="modal fade" id="modal_err" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" style="width: 28%;">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
						<span style="font-size: 14pt; font-weight: 600;">Form Transaksi Ini Belum Siap Digunakan</span>
					</div>

					<div class="col-md-12" style="margin-top: 10px;">
						<p>Tidak ada <b>COA keuangan</b> yang bisa digunakan untuk melakukan transaksi memorial ini.</p>
						<p>Perlu anda ketahui bahwa COA keuangan yang digunakan pada form ini adalah <b>COA keuangan yang tidak termasuk kedalam COA kas dan bank</b>. dan Sistem kami tidak bisa menemukan COA keuangan dengan klasifikasi tersebut.</p>
						<p>Untuk menambahkan COA keuangan baru, anda bisa klik <a href="{{ Route('keuangan.akun.create') }}" target="_blank">halaman ini</a></p>
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div> -->
		</div>
	</div>
</div>
