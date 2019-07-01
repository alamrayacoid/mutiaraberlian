<div class="tab-pane animated fadeIn show" id="penjualanviaweb">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Penjualan Via Website</h3>
            </div>
            <div class="header-block pull-right">
                <button class="btn btn-primary" data-toggle="modal" style="color: white" data-target="#createKPW"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-3">
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group input-group-sm input-daterange">
                            <input type="text" class="form-control" id="date_from_kpw">
                            <span class="input-group-addon">-</span>
                            <input type="text" class="form-control" id="date_to_kpw">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" id="btn_search_date_kpl"><i class="fa fa-search"></i></button>
                                <button class="btn btn-primary" type="button" id="btn_refresh_date_kpl"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 filter_agent">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Agen" id="filter_agent_name_kpw" autocomplete="off">
                            <input type="hidden" id="filter_agent_code_kpw">
                            <button class="btn btn-secondary btn-md" title="Cari Agen" style="border-left:none;" data-toggle="modal" data-target="#searchAgenKpw"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 filter_agent">
                        <button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter" id="btn_filter_kpw">Filter</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display" cellspacing="0" id="table_penjualanviaweb">
                        <thead class="bg-primary">
                        <tr>
                            <th width="10%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Website</th>
                            <th width="20%">Total Penjualan</th>
                            <th width="10%">Aksi</th>
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
