<!-- Modal -->
<div id="modalBayar" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <form id="fromBayarTermin" method="post">{{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title">Pembayaran Termin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="poid" id="poid">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Nota</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: left;"
                                   id="nota" name="nota" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Supplier</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: left;"
                                   id="supplier" name="supplier" value="EFENDI" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Tanggal Pembelian</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: left;"
                                   id="tgl_beli" name="tgl_beli" value="13/03/2019" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Termin ke-</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: left;"
                                   id="termin" name="termin" value="1" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Tagihan</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: right;"
                                   id="tagihan" name="tagihan" value="Rp. 435.000" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;" id="nominal_termin_lbl">Terbayar</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: right;"
                                   id="terbayar" name="terbayar" value="Rp. 0" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Kekurangan</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control-plaintext"
                                   style="font-weight: bold; text-align: right;"
                                   id="kekurangan" name="kekurangan" readonly value="Rp. 0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Pilih Akun Kas</label>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <select name="cashAccount" id="cashAccountPP" class="form-control form-control-sm select2 w-100">
                                <option value="">Pilih Akun Kas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <label for="" style="margin-right:10px;">Nilai Bayar</label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"
                                   id="nilai_bayar" name="nilai_bayar" autocomplete="false">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
