<!-- Modal -->
<div id="modalBayar" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Pembayaran Termin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="poid" id="poid">
                <input type="hidden" name="terminid" id="terminid">
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Nota</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext"
                               style="font-weight: bold; text-align: left;"
                               id="nota" name="nota" value="PO-001/13/03/2019" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Supplier</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext"
                               style="font-weight: bold; text-align: left;"
                               id="supplier" name="supplier" value="EFENDI" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Tanggal Pembelian</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext"
                               style="font-weight: bold; text-align: left;"
                               id="tgl_beli" name="tgl_beli" value="13/03/2019" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Termin ke-</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext"
                               style="font-weight: bold; text-align: left;"
                               id="termin" name="termin" value="1" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Tagihan</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext"
                               style="font-weight: bold; text-align: right;"
                               id="tagihan" name="tagihan" value="Rp. 435.000" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;" id="nominal_termin_lbl">Terbayar</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext" style="font-weight: bold; text-align: right;"
                               id="terbayar" name="terbayar" value="Rp. 0" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Kekurangan</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control-plaintext" style="font-weight: bold; text-align: right;"
                               id="kekurangan" name="kekurangan" readonly value="Rp. 0">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-sm-4">
                        <label for="" style="margin-right:10px;">Nilai Bayar</label>
                    </div>
                    <div class="col-4 mb-3">
                        <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"
                               id="nilai_bayar" name="nilai_bayar">
                        {{--<div class="input-group">--}}
                            {{--<div class="input-group-prepend">--}}
                                {{--<button class="btn btn-primary" type="button" title="Lunasi" onclick="lunasiTermin()"><i--}}
                                        {{--class="fa fa-check-circle"></i></button>--}}
                            {{--</div>--}}
                            {{--<input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"--}}
                                   {{--id="nilai_bayar">--}}
                        {{--</div>--}}
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_simpan">Simpan</button>
            </div>
        </div>

    </div>
</div>
