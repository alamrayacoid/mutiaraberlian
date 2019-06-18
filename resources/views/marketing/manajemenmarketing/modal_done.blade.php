<!-- Modal -->
<div id="modal_done" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Promosi Selesai</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body col-12">
                <div class="row">

                    <div class="col-2">
                        <label for="">Judul Promosi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="text" class="form-control" id="done_judulpromosi" readonly value="">
                    </div>

                    <div class="col-2">
                        <label for="">Kode Promosi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="text" class="form-control" id="done_kodepromosi" readonly value="">
                    </div>

                    <div class="col-2">
                        <label for="">Tanggal Promosi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="text" class="form-control" id="done_tanggalpromosi" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                    </div>

                    <div class="col-2">
                        <label for="">Output Target</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_outputpromosi" class="form-control form-control-sm" readonly=""></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Output Realisasi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_outputreal" class="form-control form-control-sm"></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Output Persentase <strong>%</strong></label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="number" class="form-control" id="done_outputpersentase" value="">
                    </div>

                    <div class="col-2">
                        <label for="">Outcome Target</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_outcomepromosi" class="form-control form-control-sm" readonly=""></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Outcome Realisasi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_outcomerealisasi" class="form-control form-control-sm" ></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Outcome Persentase <strong>%</strong></label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="number" class="form-control" id="done_outcomepersentase" value="">
                    </div>

                    <div class="col-2">
                        <label for="">Impact Target</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_impactpromosi" class="form-control form-control-sm" readonly=""></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Impact Realisasi</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_impactrealisasi" class="form-control form-control-sm" ></textarea>
                    </div>

                    <div class="col-2">
                        <label for="">Impact Persentase <strong>%</strong></label>
                    </div>
                    <div class="col-9 mb-3">
                        <input type="number" class="form-control" id="done_impactpersentase" value="">
                    </div>

                    <div class="col-2">
                        <label for="">Catatan</label>
                    </div>
                    <div class="col-9 mb-3">
                        <textarea name="" cols="5" rows="3" id="done_catatanpromosi" class="form-control form-control-sm"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-sm" style="color: white" onclick="done()">Simpan</button>
            </div>
        </div>

    </div>
</div>
