<div class="tab-pane fade in show" id="hpa">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Data Harga Penjualan Agen</h3>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="">
                            <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                   id="table_golonganHPA">
                                <thead class="bg-primary">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="70" style="text-align:center">Nama</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <fieldset class="col-md-8 col-sm-12">
                        <form method="post" id="formsethargaHPA">{{csrf_field()}}
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="txtGolHPA">Golongan :</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="hidden" name="idGolHPA" id="idGolHPA">
                                    <p id="txtGolHPA">~</p>
                                </div>
                            </div>
                            <div>
                                <label for="">Nama Barang</label>
                            </div>
                            <div>
                                <input type="hidden" name="idBarangHPA" id="idBarangHPA">
                                <input type="text" class="form-control form-control-sm mb-2 barangHPA" name="nama_barangHPA">
                            </div>

                            <div>
                                <label for="">Jenis Harga</label>
                            </div>
                            <div class="form-group">
                                <select class="form-control form-control-sm select2" id="jenishargaHPA" name="jenishargaHPA">
                                    <option value="">Pilih Jenis Harga</option>
                                    <option value="U">Satuan</option>
                                    <option value="R">Range</option>
                                </select>
                            </div>
                            <hr>
                            @include('masterdatautama.harga.golongan.satuanHPA')
                            @include('masterdatautama.harga.golongan.rangeHPA')
                        </form>
                        <div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                       id="table_golonganhargaHPA">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Range</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </section>

        </div>
    </div>
</div>
