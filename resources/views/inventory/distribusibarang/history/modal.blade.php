<!-- Modal -->
<div id="modalHistory" class="modal fade animated fadeIn" role="dialog">
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
                            <input type="text" class="form-control-plaintext border-bottom" id="nota_ht" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tanggal : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="date_ht" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Asal : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="origin_ht" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tujuan : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="dest_ht" readonly="">
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="table-responsive col-md-8 col-sm-8">
                        <table class="table table-striped table-hover" cellspacing="0" id="table_detail_ht">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="50%">Kode Barang/Nama Barang</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="15%">Satuan</th>
                                    <th width="15%">Kode Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_detail_showpc" width="100%">
                            <thead class="bg-primary">
                            <tr>
                                <th>Kode Produksi</th>
                                <th>Kuantitas</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
