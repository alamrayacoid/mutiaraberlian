<div class="tab-pane animated fadeIn show" id="penerimaanpiutang">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Pembayaran Piutang </h3>
            </div>
            <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('datakonsinyasi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row mb-3">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="input-group input-group-sm input-daterange">
                            <input type="text" class="form-control" id="date_from_pp" value="{{ $start->format('d-m-Y') }}">
                            <span class="input-group-addon">-</span>
                            <input type="text" class="form-control" id="date_to_pp" value="{{ $end->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <select class="select2 form-control form-control-sm" id="status_pp">
                            <option value="all" selected>Semua Data</option>
                            <option value="lebih">Melebihi Jatuh Tempo</option>
                            <option value="belum">Belum Jatuh Tempo</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="agen_pp" placeholder="Nama/Kode Agen">
                        <input type="hidden" id="id_agen_pp">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12">
                        <button type="button" class="btn btn-primary">Cari</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_penerimaanpiutang">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center">Agen</th>
                            <th class="text-center">Piutang</th>
                            <th class="text-center">Tanggal</th>
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

<script type="text/javascript">
    var table_penerimaanpiutang;
    $(document).ready(function () {
        table_penerimaanpiutang = $('#table_penerimaanpiutang').DataTable({

        });
    })
</script>
