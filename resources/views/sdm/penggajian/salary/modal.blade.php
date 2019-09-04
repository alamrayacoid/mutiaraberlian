<div class="modal fade" id="modal_detailsalary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Detail Salary </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Nama Pegawai: <span id="modal_detailnama"></span></label>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Gaji Pokok: <span id="modal_detailgaji"></span></label>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Bulan: <span id="modal_detailbulan"></span></label>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Reward</label>
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-striped display nowrap table-bordered" style="width: 100%" cellspacing="0" id="modal_detailtablereward">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Nama Reward</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Punishment</label>
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-striped display nowrap table-bordered" style="width: 100%" cellspacing="0" id="modal_detailtablepunishment">
                            <thead class="bg-warning">
                                <tr>
                                    <th>Nama Punishment</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-12">
                        <label>Tunjangan</label>
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-hover table-striped display nowrap table-bordered" style="width: 100%" cellspacing="0" id="modal_detailtabletunjangan">
                            <thead class="bg-info" style="color: azure">
                                <tr>
                                    <th>Nama Tunjangan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
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
