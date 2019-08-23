<div class="tab-pane fade in active show" id="list_manajemen">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Cashbon Pegawai</h3>
            </div>
            <div class="header-block pull-right">

            </div>
            <div class=""></div>
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <label>Pegawai</label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm namapegawai" id="namapegawai"
                                placeholder="Nama Pegawai">
                            <input type="hidden" id="id_pegawai">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <label>Cashbon<</label>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm cashbontop rupiahnull" id="cashbontop">
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <label>Cashbon></label>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm cashbonbot rupiahnull" id="cashbonbot">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <button class="btn btn-primary cari" onclick="filterCashbon()"><i class="fa fa-search"></i></button>
                        <button class="btn btn-info cariall" onclick="filterCashbon('all')">Semua</button>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: -10px !important; padding-bottom: 10px !important">
                        <span>Keterangan: Tombol <strong>Semua</strong> untuk menampilkan semua data</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_cashbon">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Cashbon</th>
                                <th>Aksi</th>
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
