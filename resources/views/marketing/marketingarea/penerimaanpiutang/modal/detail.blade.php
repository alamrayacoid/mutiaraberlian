<!-- Modal -->
<div id="modalDetailpp" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Piutang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-2">
                            <label for="">Nomor Nota</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm" id="nota_dtpp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Jatuh Tempo</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm" id="date_dtpp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Agen</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm" id="agent_dtpp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Sisa Piutang</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm rupiah" id="total_dtpp" readonly="">
                        </div>
                    </div>
                </section>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_detailpp" width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Harga @</th>
                            <th class="text-center">Diskon @</th>
                            <th class="text-center">Harga Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_detailpembayaranpp" width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Nominal</th>
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