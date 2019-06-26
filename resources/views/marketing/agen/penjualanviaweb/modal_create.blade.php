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
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Area Provinsi :</label>
                    <div class="col-4">
                        <select class="select2" id="area_provinsi" onchange="getCity()">
                            <option selected disabled>== Pilih Provinsi ==</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Area Kota :</label>
                    <div class="col-4">
                        <select class="select2" id="area_provinsi">
                            <option>== Pilih Kota ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Nama Agen :</label>
                    <div class="col-10">
                        <select class="select2" id="nama_agen">
                            <option>== Pilih Agen ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Website :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="website">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Produk :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="produk">
                    </div>
                </div>
                <div class="row form-group">
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Kuantitas :</label>
                    <div class="col-4">
                        <input type="number" class="form-control-sm form-control" id="kuantitas">
                    </div>
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Satuan :</label>
                    <div class="col-4">
                        <select class="select2" id="satuan">
                            <option>== Pilih Satuan ==</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Harga@ :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control rupiah" id="harga">
                    </div>
                    <label for="detail_kpl_nota" class="col-2 col-form-label">Total :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control rupiah" id="total">
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
