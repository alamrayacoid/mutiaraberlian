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
                        <div class="input-group">
                            <input type="text" placeholder="Tulis Nota / Filterisasi --->" class="form-control" id="nota_s" autocomplete="off" style="text-transform:uppercase">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text btn btn-sm btn-primary btn-block rounded" style="height: 100%;" onclick="goSearch()"><i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-lg btn-primary btn-block rounded" onclick="getNota()"><i class="fa fa-filter"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped w-100" cellspacing="0" id="table_piutang">
                        <thead class="bg-primary">
                        <tr>
                            {{-- <th width="1%">No</th> --}}
                            <th>Nota</th>
                            <th>Deadline</th>
                            <th>Sisa</th>
                            <th width="10%">Bayar</th>
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
