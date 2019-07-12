<!-- Modal -->
<div id="modal_distribusi" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-xl">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Kirim Order</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <label for="">Tanggal</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="dateModalSend" class="form-control form-control-sm" readonly="">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">No Nota</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="notaModalSend" class="form-control form-control-sm" readonly="">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Nama Agen</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="agentModalSend" class="form-control form-control-sm" readonly="">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Total Transaksi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="totalModalSend" class="form-control form-control-sm rupiah-left" readonly="">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Jasa Ekspedisi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select class="select2" id="ekspedisi">
                            <option></option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Jenis Ekspedisi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select class="select2" id="jenis_ekspedisi">
                            <option></option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Nama Kurir</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="nama_kurir" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Nomor Telepon</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="tlp_kurir" class="form-control form-control-sm hp">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Nomor Resi</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="resi_kurir" class="form-control form-control-sm text-uppercase">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Biaya</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <input type="text" id="biaya_kurir" class="form-control form-control-sm rupiah">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Jenis Pembayaran</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select class="select2 form-control form-control-sm" id="jenis_pembayaran" name="jenis_pembayaran">
                            <option value="C">Cash</option>
                            <option value="T">Cash Tempo</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="">Metode Pembayaran</label>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <select class="select2 form-control form-control-sm" id="metode_pembayaran">
                            <option value="C">Cash</option>
                            <option value="T">Transfer BRI</option>
                            <option value="T">Transfer BNI</option>
                            <option value="T">Transfer BCA</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="table-responsive col-8">
                        <table class="table table-striped table-hover display nowrap" cellspacing="0"
                               id="table_senddistribution">
                            <thead class="bg-primary">
                            <tr>
                                <th width="25%">Barang</th>
                                <th width="10%">Jumlah</th>
                                <th width="10%">Satuan</th>
                                <th width="15%">Harga Satuan</th>
                                <th width="15%">Diskon Satuan</th>
                                <th width="15%">Total Harga</th>
                                <th width="10%">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-4" style="padding-right: 0px;">
                        <div class="row col-12" style="padding-right: 0px;">
                            <div class="col-8" style="padding-left: 0px !important;">
                                <input type="text" onkeypress="pressCode(event)" style="width: 100%; text-transform: uppercase" class="inputkodeproduksi form-control form-control-sm" id="inputkodeproduksi" readonly>
                                <input type="hidden" id="iditem_modaldt">
                            </div>
                            <div class="input-group col-4" style="width: 100%; padding-right: 0px;">
                                <input type="number" onkeypress="pressCode(event)" class="inputqtyproduksi form-control form-control-sm" id="inputqtyproduksi" readonly>
                                <span class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-addprodcode"><i class="fa fa-plus"></i></button>
                                    </span>
                            </div>
                        </div>
                        <div class="row col-12">
                            <p>Masukkan kode produksi untuk barang <span class="text-item">-</span> kemudian tekan Enter untuk memasukkan ke tabel distribusi</p>
                        </div>
                        <div class="row col-12 pr-0">
                            <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_prosesordercode">
                                <thead class="bg-primary">
                                <tr>
                                    <th>Kode Produksi</th>
                                    <th>Kuantitas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <span class="float-left text-left" style="float: left">Periksa terlebih dahulu kode produksi sebelum mengirim barang</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="kirim()">Simpan dan Kirim</button>
            </div>
        </div>

    </div>
</div>
