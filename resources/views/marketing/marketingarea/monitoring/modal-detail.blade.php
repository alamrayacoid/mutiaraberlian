<!-- Modal -->
<div id="modalDetailMPA" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-2">
                            <label for="">Nomor Nota</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm" id="nota_dtmpa" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Tanggal</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm" id="date_dtmpa" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Agen</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm" id="agent_dtmpa" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Total Pembelian</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm rupiah" id="total_dtmpa" readonly="">
                        </div>
                    </div>
                </section>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_detailmpa" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Harga @</th>
                                <th class="text-center">Harga Total</th>
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
