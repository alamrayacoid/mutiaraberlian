<div class="tab-pane animated fadeIn show" id="returnpenjualan">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title"> Return Penjualan </h3>
            </div>
            <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{route('mmareturn.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
            </div>
        </div>
        <div class="card-block">
            <section>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-100" cellspacing="0" id="table_return">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Tgl Return</th>
                                <th>Nota Return</th>
                                <th>Nota Penjualan</th>
                                <th>Kode Produksi</th>
                                <th>Jenis Return</th>
                                <th>Agen</th>
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
