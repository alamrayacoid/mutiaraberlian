<!-- Modal -->
<div id="createKPW" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Create Penjualan via Website</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-2 col-form-label">Area Provinsi :</label>
                    <div class="col-4">
                        <select class="select2" id="area_provinsi" onchange="getCity()">
                            <option selected disabled>== Pilih Provinsi ==</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="area_kota" class="col-2 col-form-label">Area Kota :</label>
                    <div class="col-4">
                        <select class="select2" id="area_kota" onchange="getAgen()">
                            <option>== Pilih Kota ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="nama_agen" class="col-2 col-form-label">Nama Agen :</label>
                    <div class="col-10">
                        <select class="select2" id="nama_agen">
                            <option>== Pilih Agen ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="website" class="col-2 col-form-label">Website :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="website">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="website" class="col-2 col-form-label">Kode Transaksi :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="transaksi" style="text-transform: uppercase">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="produk" class="col-2 col-form-label">Produk :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="produk" style="text-transform: uppercase">
                        <input type="hidden" class="form-control-sm form-control" id="id_produk">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="kuantitas" class="col-2 col-form-label">Kuantitas :</label>
                    <div class="col-4">
                        <input type="number" class="form-control-sm form-control" id="kuantitas" onkeyup="setTotal()">
                    </div>
                    <label for="satuan" class="col-2 col-form-label">Satuan :</label>
                    <div class="col-4">
                        <select class="select2" id="satuan">
                            <option>== Pilih Satuan ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="harga" class="col-2 col-form-label">Harga/<span id="label-satuan">-</span> :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control input-harga" id="harga" onkeyup="setTotal()">
                    </div>
                    <label for="total" class="col-2 col-form-label">Total :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control input-harga" id="total" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="note" class="col-2 col-form-label">Catatan :</label>
                    <div class="col-10">
                        <textarea class="form-control form-control-sm" id="note"></textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-8">
                        <input type="text" class="form-control-sm form-control" id="code" onkeypress="cekCode(event)" placeholder="Kode Produksi" style="text-transform: uppercase">
                    </div>
                    <div class="col-3">
                        <input type="number" class="form-control-sm form-control" id="code_qty" onkeypress="cekCode(event)" >
                    </div>
                    <div class="col-1">
                        <button class="btn btn-primary" onclick="addCode()"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="table-responsive" style="padding: 0px 15px 0px 15px;">
                        <table class="table table-hover table-striped display" cellspacing="0" id="table_KPW" style="width: 100%">
                            <thead class="bg-primary">
                            <tr>
                                <th style="width: 70%">Kode</th>
                                <th style="width: 20%">Qty</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveSalesWeb()">Simpan</button>
            </div>
        </div>

    </div>
</div>
