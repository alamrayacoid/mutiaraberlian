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
                    <select name="a_kota" id="a_kota" class="form-control form-control-sm select2">
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
                <input type="text" class="form-control form-control-sm" name="a_apj" id="a_apj">
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
                <input type="text" class="form-control form-control-sm" name="a_apb" id="a_apb">
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Total Harga</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="a_th" id="a_th" readonly="" value="Rp. 0">
            </div>
        </div>
    </div>
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
                        <input type="text" class="form-control form-control-sm" value="">
                    </td>
                    <td>
                        <select name="#" id="#" class="form-control form-control-sm select2">
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="0">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" value="" readonly="">
                    </td>
                    <td>
                        <button class="btn btn-success btn-tambah-agen btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
