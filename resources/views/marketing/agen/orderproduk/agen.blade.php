<div class="d-none animated fadeIn col-12" id="agen">
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Area</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="row">
                <div class="form-group col-6">
                    <select name="a_prov" id="a_prov" class="form-control form-control-sm select2">
                    </select>
                </div>
                <div class="form-group col-6">
                    <select name="a_kota" id="a_kota" class="form-control form-control-sm select2" disabled>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Agen Penjual</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="hidden" name="a_idapj" id="a_idapj">
                <input type="hidden" name="a_kodeapj" id="a_kodeapj">
                <input type="hidden" name="a_compapj" id="a_compapj">
                <input type="text" class="form-control form-control-sm" name="a_apj" id="a_apj" disabled>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Agen Pembeli</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="hidden" name="a_idapb" id="a_idapb">
                <input type="hidden" name="a_kodeapb" id="a_kodeapb">
                <input type="hidden" name="a_compapb" id="a_compapb">
                <input type="text" class="form-control form-control-sm" name="a_apb" id="a_apb" disabled>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Total Harga</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="a_th" id="a_th" readonly="" value="Rp. 0">
                <input type="hidden" name="a_tot_hrg" id="a_tot_hrg">
            </div>
        </div>
    </div>
    <div class="container" id="tbl_item" style="display: none;">
        <div class="table-responsive">
            <table class="table table-striped table-hover" cellspacing="0" id="table_agen">
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
                        <input type="hidden" name="idItem[]" class="itemid">
                        <input type="hidden" name="kode[]" class="kode">
                        <input type="hidden" name="idStock[]" class="idStock">
                        <input type="text"
                               name="barang[]"
                               class="form-control form-control-sm barang"
                               autocomplete="off">
                    </td>
                    <td>
                        <select name="satuan[]"
                                class="form-control form-control-sm select2 satuan">
                        </select>
                    </td>
                    <td>
                        <input type="number"
                               name="jumlah[]"
                               min="0"
                               class="form-control form-control-sm jumlah"
                               value="0" readonly>
                    </td>
                    <td>
                        <input type="text"
                               name="harga[]"
                               class="form-control form-control-sm text-right harga"
                               value="Rp. 0" readonly>
                        <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p>
                    </td>
                    <td>
                        <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly>
                        <input type="hidden" name="sbtotal[]" class="sbtotal">
                    </td>
                    <td>
                        <button class="btn btn-success btn-tambah-agen btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
