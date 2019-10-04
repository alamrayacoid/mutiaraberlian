<div class="tab-pane fade in show animated fadeIn" id="kpipegawai">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title"> KPI Pegawai </h3>
            </div>
            <div class="header-block pull-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#tambah" onclick="window.location.href='{{route('kpipegawai.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
            </div>
        </div>
        <div class="card-block">
            <section>

                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap w-100" cellspacing="0" id="table_kpi_pegawai">
                        <thead class="bg-primary">
                        <tr align="center">
                            <th width="1%">No</th>
                            <th>Nama Pegawai</th>
                            <th>Divisi</th>
                            <th>Posisi</th>
                            <th width="15%">Aksi</th>
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
