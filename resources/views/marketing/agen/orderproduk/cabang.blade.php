<div class="d-none animated fadeIn col-12" id="cabang">
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Area</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="row">
                <div class="form-group col-6">
                    <select name="c_prov" id="c_prov" class="form-control form-control-sm select2">
                    </select>
                </div>
                <div class="form-group col-6">
                    <select name="c_kota" id="c_kota" class="form-control form-control-sm select2" disabled>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Cabang</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <select name="c_cabang" id="c_cabang" class="form-control form-control-sm select2">
                </select>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Agen Pembeli</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="hidden" name="c_idapb" id="c_idapb">
                <input type="hidden" name="c_kodeapb" id="c_kodeapb">
                <input type="hidden" name="c_compapb" id="c_compapb">
                <select name="c_apb" id="c_apb" class="form-control form-control-sm select2" disabled>
                </select>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Total Harga</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="c_th" id="c_th" readonly="" value="Rp. 0">
                <input type="hidden" name="c_tot_hrg" id="c_tot_hrg">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover" cellspacing="0" id="table_cabang">
            <thead class="bg-primary">
                <tr>
                    <th width="30%">Kode Barang/Nama Barang</th>
                    <th width="10%">Satuan</th>
                    <th width="10%">Jumlah</th>
                    <th width="25%">Harga Satuan</th>
                    <th width="25%">Sub Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="hidden" name="c_idItem[]" class="c_itemid">
                        <input type="hidden" name="c_kode[]" class="c_kode">
                        <input type="hidden" name="c_idStock[]" class="c_idStock">
                        <input type="text"
                               name="c_barang[]"
                               class="form-control form-control-sm c_barang"
                               autocomplete="off">
                    </td>
                    <td>
                        <select name="c_satuan[]"
                                class="form-control form-control-sm select2 c_satuan">
                        </select>
                    </td>
                    <td>
                        <input type="number"
                               name="c_jumlah[]"
                               min="0"
                               class="form-control form-control-sm c_jumlah"
                               value="0" readonly>
                    </td>
                    <td>
                        <input type="text"
                               name="c_harga[]"
                               class="form-control form-control-sm text-right c_harga"
                               value="Rp. 0" readonly>
                        <p class="text-danger c_unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p>
                    </td>
                    <td>
                        <input type="text" name="c_subtotal[]" style="text-align: right;" class="form-control form-control-sm c_subtotal" value="Rp. 0" readonly>
                        <input type="hidden" name="c_sbtotal[]" class="c_sbtotal">
                    </td>
                    <td>
                        <button class="btn btn-success btn-tambah-cabang btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
