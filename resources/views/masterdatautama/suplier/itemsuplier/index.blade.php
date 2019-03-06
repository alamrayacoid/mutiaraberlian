<div class="tab-pane fade in show" id="itemsuplier">
	<div class="card">
			<div class="card-header bordered p-2">
				<div class="header-block">
						<h3 class="title"> Item Suplier </h3>
				</div>
			</div>
			<div class="card-block">
					<section>
                    <div class="container">
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <label for="">Supplier</label>
                        </div>
                        <div class="col-md-9 col-sm-12 mb-3">
                            <select name="suppId" id="suppId" class="form-control form-control-sm select2">
                                <option value="" selected disabled>== Pilih Supplier ==</option>
                                @foreach($getSupp as $supp)
                                <option value="{{ $supp->s_id }}">{{ $supp->s_company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label for="">Nama Barang</label>
                        </div>
                        <div class="col-md-9 col-sm-12 mb-3">
                            <input type="hidden" id="suppItemId">
                            <input type="text" class="form-control form-control-sm" id="suppItemNama" name="suppItemNama" style="text-transform:uppercase">
                        </div>
                        <div class="col-1 btn-group-sm">
                            <button class="btn btn-sm btn-primary" onclick="tambah()">Simpan</button>
                        </div>
                    </div>
                    <hr>
                    </div>
						<div class="table-responsive">
                            <table class="table table-hover table-striped display nowrap" cellspacing="0" id="item_suplier">
                                <thead class="bg-primary">
                                    <tr>
                                        <th width="10%">No</th>
                                        <th width="15%" style="text-align:center;">Kode Barang</th>
                                        <th width="35%" style="text-align:center;">Nama Barang</th>
                                        <th width="10%" style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyItemSuppDT">
                                    
                                </tbody>
                            </table>
						</div>
					</section>
			</div>
	</div>
</div>