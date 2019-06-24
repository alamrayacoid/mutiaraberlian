<div class="tab-pane animated fadeIn show" id="distribusipenjualan">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Distribusi Penjualan</h3>
            </div>
            {{--<div class="header-block pull-right">
                <a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
            </div>--}}
            <div class=""></div>
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <label for="">Status Distribusi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select class="select2" id="status_distribusi" onchange="tableDistribusi()">
                            <option value="N">Belum Dikirim</option>
                            <option value="P">Sudah Dikirim</option>
                            <option value="Y">Diterima</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped" cellspacing="0" id="table_distribusi">
                        <thead class="bg-primary">
                        <tr>
                            <th width="1%">No</th>
                            <th>Tanggal</th>
                            <th>Nama Agen</th>
                            <th>Nomer Nota</th>
                            <th>Total Transaksi</th>
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
