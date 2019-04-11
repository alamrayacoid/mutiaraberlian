<div class="d-none animated fadeIn" id="rangeHPA">
    <div>
        <label for="">Range</label>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="rangestartHPA" id="rangestartHPA">
        </div>
        <span>-</span>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="rangeendHPA" id="rangeendHPA" readonly>
            <span class="text-danger">*)Tak terhingga = ~</span>
        </div>
        <div class="col-md-4">
            <select name="satuanrangeHPA" id="satuanrangeHPA" class="form-control form-control-sm select2">
                <option value="">Pilih Satuan</option>
            </select>
        </div>
    </div>
    <div>
        <label for="">Jenis Pembayaran</label>
    </div>
    <div>
        <select name="jenis_pembayaranrangeHPA" id="jenis_pembayaranHPA" class="form-control form-control-sm select2">
            <option value="MA">Marketing Area</option>
            <option value="A">Agen</option>
            <option value="SA">Sub Agen</option>
        </select>
    </div>
    <div>
        <label for="" class="mt-3">Harga</label>
    </div>
    <div class="row">
        <div class="col-10">
            <input type="text" id="hargarangeHPA" name="hargarangeHPA" class="form-control form-control-sm input-rupiah">
        </div>
        <div class="col-2">
            <button class="btn btn-primary btn-md btn-submit btn-block" type="submit">Simpan</button>
        </div>
    </div>
</div>
