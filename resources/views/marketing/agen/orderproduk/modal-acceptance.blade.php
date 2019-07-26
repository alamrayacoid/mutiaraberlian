<!-- Modal -->
<div id="modalAcceptanceDO" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Konfirmasi Terima Barang Pesanan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <!-- hidden id stockdist -->
                        <input type="hidden" id="id_ac">

                        <div class="col-md-2">
                            <label for="">Nomor Nota : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="nota_ac" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tanggal Pengiriman : </label>
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
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Tanggal Diterima :</label>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                </div>
                                <input type="text" name="dateReceive_ac" class="form-control form-control-sm datepicker" autocomplete="off" id="dateReceive_ac" value="">
                            </div>
                        </div>
                    </div>
                </section>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" id="table_detail_ac">
                        <thead class="bg-primary">
                            <tr>
                                <th width="10%">No</th>
                                <th width="45%">Kode Barang/Nama Barang</th>
                                <th width="15%">Jumlah</th>
                                <th width="15%">Satuan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="keterangan" style="margin-top: 10px">
                    <span>Kode Produksi untuk - <strong id="product_name"></strong></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" id="table_detail_ackode">
                        <thead class="bg-primary">
                        <tr>
                            <th width="10%">No</th>
                            <th width="60%">Kode Produksi</th>
                            <th width="30%">Jumlah</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_confirmAc" onclick="terimaDO()">Konfirmasi Penerimaan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
