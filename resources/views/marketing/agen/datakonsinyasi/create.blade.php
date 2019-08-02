<div class="tab-pane animated fadeIn show" id="datakonsinyasi">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Kelola Data Konsinyasi </h3>
            </div>
        </div>
        <div class="card-block">

            <section>
                <form id="formKonsinyasi" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <!-- <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Area</label>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select name="provinsi" id="provinsi" class="form-control form-control-sm select2" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select name="kota" id="kota"
                                class="form-control form-control-sm select2" disabled></select>
                            </div>
                        </div> -->

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Cabang</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="form-group">
                                <input type="hidden" class="form-control corm-control-sm" name="branchCode" id="branchCode" value="{{ $info->c_id }}">
                                <input type="text" class="form-control corm-control-sm" value="{{ $info->c_name }}">
                                <!-- <select class="form-control select2" name="branch" id="branch" disabled></select> -->
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Konigner</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="form-group">
                                <input type="hidden" name="agentCode" id="agentCode">
                                <select class="form-control select2" name="agent" id="agent">
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Total</label>
                        </div>
                        <div class="col-md-10 col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm"
                                name="total_harga" id="total_harga" value="Rp. 0" readonly>
                                <input type="hidden" name="tot_hrg" id="tot_hrg">
                            </div>
                        </div>

                        <div class="container" id="tbl_item">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover table-striped" id="table_rencana" cellspacing="0">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th width="20%">Kode/Nama Barang</th>
                                            <th width="10%">Satuan</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="13%">Kode Produksi</th>
                                            <th width="14%">Harga Satuan</th>
                                            <th width="13%">Diskon @</th>
                                            <th width="15%">Sub Total</th>
                                            <th width="5%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="idItem[]" class="itemid">
                                                <input type="hidden" name="kode[]" class="kode">
                                                <input type="hidden" name="idStock[]" class="idStock">
                                                <input type="text"
                                                name="barang[]"
                                                class="form-control form-control-sm barang"
                                                autocomplete="off">
                                            </td>
                                            <td>
                                                <select name="satuan[]"
                                                class="form-control form-control-sm select2 satuan"></select>
                                            </td>
                                            <td>
                                                <input type="number"
                                                name="jumlah[]"
                                                min="0"
                                                class="form-control form-control-sm jumlah"
                                                value="0" readonly>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btnCodeProd btn-sm rounded"
                                                type="button">kode produksi </button>
                                            </td>
                                            <td>
                                                <input type="text"
                                                name="harga[]"
                                                class="form-control form-control-sm text-right harga"
                                                value="Rp. 0" readonly>
                                                <p class="text-danger unknow mb-0"
                                                style="display: none; margin-bottom:-12px !important;">
                                                Harga tidak ditemukan!</p>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm diskon rupiah text-right"
                                                id="diskon" name="diskon[]">
                                            </td>
                                            <td>
                                                <input type="text" name="subtotal[]"
                                                style="text-align: right;"
                                                class="form-control form-control-sm subtotal"
                                                value="Rp. 0" readonly>
                                                <input type="hidden" name="sbtotal[]" class="sbtotal">
                                            </td>
                                            <td>
                                                <button type="button"
                                                class="btn btn-sm btn-success rounded-circle btn-tambahp">
                                                <i
                                                class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
