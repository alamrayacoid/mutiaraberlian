<div class="modal fade" id="addgolongan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Golongan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formgln">{{csrf_field()}}
            <div class="modal-body">
                <div class="form-group row">
                    <div>
                        <label class="col-sm-3">Nama</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm mb-3" name="nama" id="nama">
                    </div>
                </div>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary simpan_gln">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
