<div class="tab-pane animated fadeIn show active" id="penerimaanagen">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Pembayaran Piutang Agen </h3><span> (Pelunasan Agen ke Pusat)</span>
            </div>
            <div class="header-block pull-right">
                {{--
                    <!-- <a class="btn btn-warning" href="#"><i class="fa fa-history"></i>&nbsp;History Pembayaran</a> -->
                --}}
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-3">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm input-daterange">
                            <input type="text" class="form-control" id="date_from_pa" value="{{ $start->format('d-m-Y') }}">
                            <span class="input-group-addon">-</span>
                            <input type="text" class="form-control" id="date_to_pa" value="{{ $end->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <select class="select2 form-control form-control-sm" id="status_pa">
                            <option value="all" selected>Semua Data</option>
                            <option value="Melebihi">Melebihi Jatuh Tempo</option>
                            <option value="Belum">Belum Jatuh Tempo</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="agen_pa" placeholder="Nama/Kode Agen" style="text-transform: uppercase">
                        <input type="hidden" id="id_agen_pa">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12">
                        <button type="button" class="btn btn-primary" id="btnCari_pa">Cari</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_penerimaanagen">
                        <thead class="bg-primary">
                        <tr>
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
