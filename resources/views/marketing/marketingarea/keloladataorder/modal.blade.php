<!-- Modal -->
<div id="detail" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-2">
                            <label for="">Tanggal</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm datepicker" value="07-09-2019"
                                   readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">No Nota</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm" value="KUY001" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Agen</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm" value="Bambang" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Total Harga</label>
                        </div>
                        <div class="col-10 mb-3">
                            <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 25,000.00"
                                   readonly="">
                        </div>
                    </div>
                </section>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" cellspacing="0" id="modal-order"
                           width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kuantitas</th>
                            <th>Satuan</th>
                            <th>Harga @</th>
                            <th>Harga Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Obat</td>
                            <td>~</td>
                            <td>DUS</td>
                            <td class="input-rupiah">Rp. 5.000,00</td>
                            <td class="input-rupiah">Rp. 25.000,00</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm btn-accept" style="color:white;">Setuju</button>
                <button type="button" class="btn btn-danger btn-sm btn-reject">Tolak</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
