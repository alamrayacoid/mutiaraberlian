<div class="tab-pane animated fadeIn show" id="keloladataagen">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Data Order Agen</h3>
            </div>
            <div class=""></div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-4">
                    <div class="col-md-2 col-sm-12">
                        <input type="text" id="start_date" name="start_date"
                               class="form-control form-control-sm datepicker text-center" placeholder="Tanggal Awal"
                               autocomplete="off">
                    </div>
                    <span>-</span>
                    <div class="col-md-2 col-sm-12">
                        <input type="text" id="end_date" name="end_date"
                               class="form-control form-control-sm datepicker text-center" placeholder="Tanggal Akhir"
                               autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <select name="status" id="status" class="form-control form-control-sm select2">
                            <option value="P" selected>Menunggu</option>
                            <option value="N">Ditolak</option>
                            <option value="Y">Disetujui</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="input-group">
                            <input type="text" name="nameAgen[]" class="form-control form-control-sm agen"
                                   autocomplete="off" style="text-transform: uppercase;" placeholder="Agen">
                            <input type="hidden" name="idAgen[]" class="agenId">
                            <input type="hidden" name="codeAgen[]" class="codeAgen">
                            <button class="btn btn-secondary btn-md rounded-right" style="border-left:none;"
                                    data-toggle="modal" data-target="#searchAgen"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-md rounded" title="Cari Berdasarkan Filter"
                                onclick="filterAgen();"><i class="fa fa-filter" aria-hidden="true"></i> &nbspFilter
                        </button>
                    </div>
                </div>
                @if (Auth::user()->u_user == 'A')
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" cellspacing="0" id="table_dataAgen">
                            <thead class="bg-primary">
                            <tr>
                                <th width="10%">Tanggal</th>
                                <th width="20%" style="text-align:center;">Nota</th>
                                <th width="20%">Agen/Cabang</th>
                                <th width="20%">Sub Agen</th>
                                <th width="20%">Total Transaksi</th>
                                <th width="10%" style="text-align:center;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" cellspacing="0" id="table_dataAgen">
                            <thead class="bg-primary">
                            <tr>
                                <th width="10%">Tanggal</th>
                                <th width="20%" style="text-align:center;">Nota</th>
                                <th width="20%">Cabang</th>
                                <th width="20%">Agen</th>
                                <th width="20%">Total Transaksi</th>
                                <th width="10%" style="text-align:center;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
