<!-- Modal -->
<div id="modalAcceptance" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Data Distribusi Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Nomor Nota : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="nota_ac" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tanggal : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="date_ac" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Asal : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="origin_ac" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tujuan : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="dest_ac" readonly="">
                        </div>
                    </div>
                </section>

                <div class="table-responsive">
                    <table class="table table-striped data-table table-hover" cellspacing="0" id="table_detail_ac">
                        <thead class="bg-primary">
                            <tr>
                                <th width="10%">No</th>
                                <th width="50%">Kode Barang/Nama Barang</th>
                                <th width="25%">Jumlah</th>
                                <th width="15%">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Konfirmasi Penerimaan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
