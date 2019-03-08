<div class="d-none animated fadeIn" id="satuan">
    <div>
        <label for="">Qty</label>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm" name="qty" id="qty">
        </div>
        <div class="col-md-4">
            <select name="satuanBarang" id="satuanBarang" class="form-control form-control-sm select2">
                <option value="">Pilih Satuan</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="jenis_pembayaran" id="jenis_pembayaran" class="form-control form-control-sm select2">
                <option value="">Jenis Pembayaran</option>
                <option value="K">Konsinyasi</option>
                <option value="C">Cash</option>
            </select>
        </div>
    </div>
    <div>
        <label for="">Harga</label>
    </div>
    <div>
        <input type="text" name="harga" id="harga" class="form-control form-control-sm input-rupiah">
    </div>
    <div style="float:right;">
        <button class="btn btn-primary btn-sm btn-submit mt-4 mb-2" type="submit">Simpan</button>
    </div>
</div>

