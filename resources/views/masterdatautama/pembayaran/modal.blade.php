<div class="modal fade" id="modal_create" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Jenis Pembayaran </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-3">
                        <label>Nama</label>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <input type="text" class="form-control-sm form-control" id="nama" name="nama" placeholder="Nama Jenis Pembayaran">
                        </div>
                    </div>

                    <div class="col-3">
                        <label>Akun</label>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <select class="form-control-sm form-control select2" id="akun" name="akun">
                                <option selected>== Pilih Akun ==</option>
                                @foreach($akun as $dk)
                                    <option value="{{ $dk->ak_id }}">{{ $dk->ak_nomor }} - {{ $dk->ak_nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <label>Catatan</label>
                    </div>
                    <div class="col-9">
                        <div class="form-group">
                            <textarea class="form-control form-control-sm" id="note"></textarea>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
