<!-- Modal -->
<div id="detail" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Data Distribusi Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="agen">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Area</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="row">
                                <div class="form-group col-6">
                                    <select name="#" id="#" class="form-control form-control-sm select2">
                                        <option value="#">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <select name="#" id="#" class="form-control form-control-sm select2">
                                        <option value="#">Pilih Kota</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Agen</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select class="form-control form-control-sm">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Jenis</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select class="form-control form-control-sm selec2">
                                    <option value="">Pilih Jenis</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Total</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cabang">
                    <div class="row">
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Cabang</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                    <option value="">Pilih Cabang</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Jenis</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select class="form-control form-control-sm select2">
                                    <option value="">Pilih Jenis</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Total</label>
                        </div>

                        <div class="col-md-10 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped data-table table-hover" cellspacing="0">
                        <thead class="bg-primary">
                            <tr>
                                <th width="30%">Kode Barang/Nama Barang</th>
                                <th width="10%">Satuan</th>
                                <th width="10%">Jumlah</th>
                                <th width="25%">Harga Satuan</th>
                                <th width="25%">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalOrderCabang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Order Cabang Ke Pusat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cabang">Pusat :</label>
                        <input type="text" class="form-control-plaintext b-border border-bottom" id="cabang" value="" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nota">Nomer Nota :</label>
                        <input type="text" class="form-control-plaintext b-border border-bottom" id="nota" value="" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="agen">Cabang :</label>
                        <input type="text" class="form-control-plaintext b-border border-bottom" id="agen" value="" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tanggal">Tanggal Order :</label>
                        <input type="text" class="form-control-plaintext b-border border-bottom" id="tanggal" value="" readonly>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="detailOrder" class="table table-sm table-hover table-bordered">
                        <thead>
                        <tr class="bg-primary text-light">
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Satuan</th>
                            <!-- <th>Harga Satuan</th>
                            <th>Total Harga</th> -->
                        </tr>
                        </thead>
                        <tbody class="empty">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
