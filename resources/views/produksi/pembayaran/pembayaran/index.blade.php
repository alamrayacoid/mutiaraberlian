<div class="tab-pane animated fadeIn active show" id="pembayaran">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title"> Pembayaran </h3>
            </div>
            <!-- <div class="header-block pull-right">

            <a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a> -->
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-2 ml-3">
                        <label for="">Pilih Order</label>
                    </div>
                    <div class="col-6">
                        <select name="po_nota" id="po_nota" class="form-control form-control-sm select2">
                            @foreach($data as $po)
                                @if($po->terbayar != $po->value)
                                    <option value="{{ $po->id }}">{{ $po->nota }} - {{ $po->supplier }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 float-right">
                        <button class="btn btn-primary btn-md btn-primary float-right" title="Pencarian Lanjutan" style=";" data-toggle="modal" data-target="#searchLanjutan"><i class="fa fa-search"> Lanjutan</i></button>
                    </div>
                </div>
                <hr style="border:0.5px solid grey">
                <div class="table-responsive termin-table">
                    <table class="table table-striped table-hover w-100" cellspacing="0" id="table_pembayaran">
                        <thead class="bg-primary">
                            <tr>
                                <th>Termin</th>
                                <th>Estimasi</th>
                                <th>Nominal</th>
                                <th>Terbayar</th>
                                <th>Status</th>
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
