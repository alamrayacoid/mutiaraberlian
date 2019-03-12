<div class="tab-pane fade in active show" id="golongan">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Data Harga Golongan</h3>
            </div>
        </div>
        <div class="card-block">
            <section>
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="">
                            <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                   id="table_golongan">
                                <thead class="bg-primary">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="70" style="text-align:center">Nama</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--<tr>--}}
                                {{--<td>1</td>--}}
                                {{--<td style="text-align:center">Agen</td>--}}
                                {{--<td>--}}
                                {{--<div class="btn-group btn-group-sm">--}}
                                {{--<button class="btn btn-warning btn-edit-golonganharga" title="Edit"--}}
                                {{--type="button"><i class="fa fa-pencil"></i></button>--}}
                                {{--<button class="btn btn-danger btn-disable-golonganharga" type="button"--}}
                                {{--title="Disable"><i class="fa fa-times-circle"></i></button>--}}
                                {{--<button class="btn btn-primary btn-add-golonganharga" title="add"--}}
                                {{--type="button"><i class="fa fa-arrow-right"></i>--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <fieldset class="col-md-7 col-sm-12">
                        <form method="post" id="formsetharga">{{csrf_field()}}
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="txtGol">Gologan :</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="hidden" name="idGol" id="idGol">
                                    <p id="txtGol">~</p>
                                </div>
                            </div>
                            <div>
                                <label for="">Nama Barang</label>
                            </div>
                            <div>
                                <input type="hidden" name="idBarang" id="idBarang">
                                <input type="text" class="form-control form-control-sm mb-2 barang" name="nama_barang">
                            </div>

                            <div>
                                <label for="">Jenis Harga</label>
                            </div>
                            <div class="form-group">
                                <select class="form-control form-control-sm select2" id="jenisharga" name="jenisharga">
                                    <option value="">Pilih Jenis Harga</option>
                                    <option value="U">Satuan</option>
                                    <option value="R">Range</option>
                                </select>
                            </div>
                            <hr>
                            @include('masterdatautama.harga.golongan.satuan')
                            @include('masterdatautama.harga.golongan.range')
                        </form>
                        <div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                       id="table_golonganharga">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th width="1%">No</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Range</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Jenis</th>
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
