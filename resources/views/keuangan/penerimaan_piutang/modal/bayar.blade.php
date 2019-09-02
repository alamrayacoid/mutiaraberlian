<!-- Modal -->
<div id="modalBayarpp" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Bayar Piutang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <div class="row">
                        <div class="col-2">
                            <label for="">Nomor Nota</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm" id="nota_paypp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Jatuh Tempo</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm datepicker" id="date_paypp" readonly>
                        </div>

                        <div class="col-2">
                            <label for="">Agen</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm" id="agent_paypp" readonly="">
                        </div>

                        <div class="col-2">
                            <label for="">Sisa Piutang</label>
                        </div>
                        <div class="col-4 mb-3">
                            <input type="text" class="form-control form-control-sm rupiah" id="total_paypp" readonly="">
                        </div>
                    </div>
                </section>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_bayarpp" width="100%">
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

                <section>
                    <div class="row">
                        <div class="form-group col-md-3 col-sm-12">
                            <label class="control-label" for="datepaypp">Tanggal Bayar</label>
                            <input type="text" class="form-control datepicker text-center" id="datepaypp" value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y') }}">
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <label class="control-label" for="bayarpaypp">Jumlah Pembayaran</label>
                            <input type="text" class="form-control form-control-sm rupiah" id="bayarpaypp">
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label class="control-label" for="paymentpp">Bayar Ke</label>
                            <select class="form-control form-control-sm" id="paymentpp">

                            </select>
                        </div>
                        <div class="form-group col-md-2 col-sm-12" style="padding-top: 10px;">
                            <button type="button" class="btn btn-primary" onclick="bayarPP()" style="width: 100%; margin-top: 17px !important;"><i class="fa fa-money"></i> Bayar</button>
                        </div>
                    </div>
                </section>

                <div class="table-responsive">
                    <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_bayarpembayaranpp" width="100%">
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
