<div class="tab-pane animated fadeIn show" id="pembayarancabang">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Pembayaran Cabang </h3><span> (Pelunasan Cabang ke Pusat)</span>
            </div>
            <div class="header-block pull-right">
                {{--<a class="btn btn-warning" href="#"><i class="fa fa-history"></i>&nbsp;History Pembayaran</a>--}}
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-3">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm input-daterange">
                            <input type="text" class="form-control" id="date_from_pc" value="{{ $start->format('d-m-Y') }}">
                            <span class="input-group-addon">-</span>
                            <input type="text" class="form-control" id="date_to_pc" value="{{ $end->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <select class="select2 form-control form-control-sm" id="status_pc">
                            <option value="all" selected>Semua Data</option>
                            <option value="Melebihi">Melebihi Jatuh Tempo</option>
                            <option value="Belum">Belum Jatuh Tempo</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="cabang_pc" placeholder="Nama Cabang/MMA" style="text-transform: uppercase">
                        <input type="hidden" id="id_cabang_pc">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12">
                        <button type="button" class="btn btn-primary" onclick="getDataPC()">Cari</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_pembayarancabang">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center">Cabang</th>
                            <th class="text-center">Agen</th>
                            <th class="text-center">Piutang</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
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
