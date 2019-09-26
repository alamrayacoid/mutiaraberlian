<!-- Modal -->
<div id="modalDetailMP" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Konsinyasi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="">Nomor Nota : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="nota_dtmp" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Tanggal : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="date_dtmp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Penempatan : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext border-bottom" id="placement_dtmp" readonly="">
                        </div>

                        <div class="col-md-2">
                            <label for="">Total Pembelian : </label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control-plaintext rupiah border-bottom" id="total_dtmp" readonly="">
                        </div>
                    </div>
                </section>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered w-100" cellspacing="0" id="table_detailmp">
                        <thead class="bg-primary">
                            <tr>
                                <th>Kode/Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <br>
                <hr>
                <br>
                <h5>Detail Transaksi dari Item Konsinyasi</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered w-100" cellspacing="0" id="table_detailtransaksimp">
                        <thead class="bg-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nota</th>
                                <th>Pembeli</th>
                                <th>Jumlah item</th>
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
