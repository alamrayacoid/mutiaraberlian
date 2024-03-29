<div class="d-none animated fadeIn col-12" id="agen">
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Area</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="row">
                <div class="form-group col-6">
                    <select name="#" id="#" class="form-control form-control-sm select2">
                        <option value="#">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <select name="#" id="#" class="form-control form-control-sm select2">
                        <option value="#">Pilih Kota</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Agen</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <select class="form-control form-control-sm">
                    <option value=""></option>
                </select>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Jenis</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <select class="form-control form-control-sm selec2">
                    <option value="">Pilih Jenis</option>
                </select>
            </div>
        </div>

        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Total</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="" readonly="">
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
