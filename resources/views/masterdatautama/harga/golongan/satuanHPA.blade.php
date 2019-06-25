<div class="d-none animated fadeIn" id="satuanHPA">
    <div>
        <label for="">Qty</label>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" name="qtyHPA" id="qtyHPA">
        </div>
        <div class="col-md-5">
            <select name="satuanBarangHPA" id="satuanBarangHPA" class="form-control form-control-sm select2">
                <option value="">Pilih Satuan</option>
            </select>
        </div>
        {{--<div class="col-md-4">--}}
            {{--<select name="jenis_pembayaranHPA" id="jenis_pembayaranHPA" class="form-control form-control-sm select2">--}}
                {{--<option value="MA">Marketing Area</option>--}}
                {{--<option value="A">Agen</option>--}}
                {{--<option value="SA">Sub Agen</option>--}}
            {{--</select>--}}
        {{--</div>--}}
    </div>
    <div>
        <label for="">Harga</label>
    </div>
    <div class="row">
        <div class="col-10">
            <input type="text" name="hargaHPA" id="hargaHPA" class="form-control form-control-sm input-rupiah" autocomplete="off">
        </div>
        <div class="col-2">
            <button class="btn btn-primary btn-md btn-submit btn-block" type="submit">Simpan</button>
        </div>
    </div>
</div>

