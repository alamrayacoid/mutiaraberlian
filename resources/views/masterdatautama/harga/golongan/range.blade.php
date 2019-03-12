<div class="d-none animated fadeIn" id="range">
    <div>
        <label for="">Range</label>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="rangestart" id="rangestart">
        </div>
        <span>-</span>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="rangeend" id="rangeend" readonly>
            <span class="text-danger">*)Tak terhingga = ~</span>
        </div>
        <div class="col-md-4">
            <select name="satuanrange" id="satuanrange" class="form-control form-control-sm select2">
                <option value="">Pilih Satuan</option>
            </select>
        </div>
    </div>
    <div>
        <label for="">Jenis Pembayaran</label>
    </div>
    <div>
        <select name="jenis_pembayaranrange" id="jenis_pembayaran" class="form-control form-control-sm select2">
            <option value="K">Konsinyasi</option>
            <option value="C">Cas</option>
        </select>
    </div>
    <div>
        <label for="" class="mt-3">Harga</label>
    </div>
    <div>
        <input type="text" id="hargarange" name="hargarange" class="form-control form-control-sm input-rupiah">
    </div>
    <div style="float:right;">
        <button class="btn btn-primary btn-sm btn-submit mt-4 mb-2" type="submit">Simpan</button>
    </div>
</div>
