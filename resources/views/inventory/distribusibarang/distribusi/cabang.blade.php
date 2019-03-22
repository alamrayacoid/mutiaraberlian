<div class="d-none animated fadeIn col-12" id="cabang">
  <form id="datacabang">
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <label>Cabang</label>
        </div>

        <div class="col-md-10 col-sm-6 col-xs-12">
            <div class="form-group">
                <select name="cabang" id="pilihcabang" class="form-control form-control-sm select2">
                    <option value="" disabled selected>Pilih Cabang</option>
                    @foreach ($cabang as $key => $value)
                      <option value="{{$value->c_id}}">{{$value->c_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover" cellspacing="0" id="table_cabang">
            <thead class="bg-primary">
                <tr>
                    <th>Kode Barang/Nama Barang</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" name="namabarang[]" id="namabarang0" data-counter="0" class="form-control form-control-sm namabarang" value="">
                        <input type="hidden" name="idbarang[]" id="idbarang0">
                    </td>
                    <td>
                        <select id="satuan0" name="satuan[]" class="form-control form-control-sm select2">
                            <option value="" disabled selected>Pilih Satuan</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="qty[]" id="qty0" class="form-control form-control-sm" value="0">
                    </td>
                    <td>
                        <button class="btn btn-success btn-tambah-cabang btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </form>
</div>
