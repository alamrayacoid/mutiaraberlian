<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<span class="modal-title keuangan" id="myModalLabel">Pilih COA yang akan diperbarui.</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<vue-datatable :resource="data_table_akun" @selected="dataSelected"></vue-datatable>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div> -->
		</div>
	</div>
</div>

<div class="modal fade" id="modal_akun_utama" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<span class="modal-title keuangan" id="myModalLabel">Pilih COA utama yang akan ditambahkan.</span>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<form id="form-akun-utama">
				<div class="row" style="padding: 5px;">
					<table class="keuangan table-mini" width="100%" style="font-weight: 8pt;">
						<thead>
							<tr>
								<th width="10%">*</th>
								<th width="20%">Nomor COA</th>
								<th width="40%">Nama COA</th>
								<th width="30%">Saldo Pembukaan</th>
							</tr>
						</thead>
						
						<tbody>
							<template v-if="!akun_utama.length">
								<tr>
									<td colspan="4">
										<center><small>Tidak ada COA utama yang belum dimiliki..</small></center>
									</td>
								</tr>
							</template>

							<tr v-for="(coa, idx) in akun_utama">
								<td style="text-align: center;">
									<input type="checkbox" name="ak_id[]" :value="coa.au_id">
								</td>
								<td>
									<input type="text" :value="coa.au_nomor" readonly style="background: white; border: 0px; width: 100%; text-align: center;">
								</td>
								<td>
									<input type="text" :value="coa.au_nama" readonly style="background: white; border: 0px; width: 100%">
								</td>
								<td>
									<vue-inputmask :name="'ak_saldo[]'" :id="'ak_saldo'" :style="'background: white;'" :minus="false" :css="'background: white; border: 0px; text-align: right; width: 100%; font-size: 9pt;'"></vue-inputmask>
								</td>
							</tr>
						</tbody>

						<tfoot>
							<tr>
								<td colspan="4">
									<center><small>Tabel ini hanya akan menampilkan COA utama yang belum dimiliki oleh cabang</small></center>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" @click="saveFromAkunUtama" :disabled="!akun_utama.length">
					<span class="glyphicon glyphicon-floppy-disk"></span> Tambahkan COA
				</button>
			</div>
		</div>
	</div>
</div>
