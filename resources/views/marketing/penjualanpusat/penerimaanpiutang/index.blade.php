<div class="tab-pane animated fadeIn show" id="terimapiutang">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Penerimaan Piutang</h3>
            </div>
            <div class="header-block pull-right">
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-3">
                    <div class="col-5 pr-0">
                        <input type="text" placeholder="Tulis Nota / Filterisasi --->" class="form-control form-control-sm" id="nota_s">
                        <input type="hidden" id="nota_r">
                    </div>
                    <div class="col-1 pl-1">
                        <button type="button" class="btn btn-sm btn-primary btn-block rounded" style="height: 100%;" onclick="getNota()"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped w-100" cellspacing="0" id="table_piutang">
                        <thead class="bg-primary">
                        <tr>
                            <th width="1%">No</th>
                            <th>Nota</th>
                            <th>Deadline</th>
                            <th>Sisa</th>
                            <th>Bayar</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
