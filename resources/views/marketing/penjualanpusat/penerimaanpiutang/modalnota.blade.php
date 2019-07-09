{{-- Modal Nota --}}
<div id="modal_nota" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <label for="">Provinsi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select name="" id="provId" class="select2">
                            <option value="" selected="" disabled="">Pillih Provinsi</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="">Kabupaten / Kota</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select name="" id="kabId" class="select2">
                            <option value="" selected="" disabled="">Pilih Kabupaten / Kota</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="">Agen</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select name="agen" id="agen" class="select2">
                            <option value="" selected="" disabled="">Pilih Agen</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped data-table table-hover display nowrap" cellspacing="0"
                           id="table_getNota">
                        <thead class="bg-primary">
                        <tr>
                            <th width="30%">Nota</th>
                            <th>Sisa</th>
                            <th width="20%">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>