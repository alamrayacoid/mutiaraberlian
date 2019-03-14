<div class="tab-pane animated fadeIn active show" id="orderprodukagenpusat">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Order Produk ke Agen / Cabang</h3>
            </div>
            <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('orderagenpusat.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_orderprodukagenpusat">
                        <thead class="bg-primary">
                            <tr>
                                <th width="1%">No</th>
                                <th>Tanggal Order</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>07/09/2019</td>
                                <td>Obat</td>
                                <td>DUS</td>
                                <td>5</td>
                                <td>Rp. 1.000.000,00</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>

        </div>
    </div>
</div>
