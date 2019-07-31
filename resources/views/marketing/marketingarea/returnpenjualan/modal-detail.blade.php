
@section('extra_style')
    <style>
        .readonly {
            pointer-events: none;
        }
    </style>
@endsection
<!-- Modal -->
<div id="detailReturn" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Return Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label>Agen</label>
                    </div>
                    <div class="col-md-10 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm agentNameDt" id="" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Nota Return</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm notaReturnDt" id="" readonly="">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Nota Penjualan</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm notaSalesDt" id="" readonly="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Nama Barang</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm itemNameDt" id="" readonly="">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Kode Produksi</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm prodCodeDt" id="" readonly="" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Jumlah Return</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm qtyReturnDt" id="" readonly="">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Total Return</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm totalReturnDt rupiah" id="" readonly="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <label>Jenis Penggantian</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control form-contorl-sm typeDt" id="" readonly="">
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped diplay nowrap w-100 table_gantibarangdt d-none" id="">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nama Barang Pengganti</th>
                                <th width="10%">Satuan</th>
                                <th width="10%">Jumlah</th>
                                <!-- <th width="15%">Kode Produksi</th> -->
                                <!-- <th width="13%">Harga Satuan</th> -->
                                <!-- <th width="15%">Sub Total</th> -->
                                <!-- <th width="5%">Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <td>
                                    <input type="hidden" name="idItem[]" class="itemid">
                                    <input type="hidden" name="kode[]" class="kode">
                                    <input type="hidden" name="idStock[]" class="idStock">
                                    <input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off">
                                </td>
                                <td>
                                    <select name="satuan[]" class="form-control form-control-sm select2 satuan"></select>
                                </td>
                                <td>
                                    <input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly>
                                </td>
                                <td>
                                    <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button"><i class="fa fa-plus"></i> kode produksi </button>
                                </td>
                                <td>
                                    <input type="text" name="harga[]" class="form-control form-control-sm rupiah harga">
                                    <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;"> Harga tidak ditemukan!</p>
                                </td>
                                <td>
                                    <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly>
                                    <input type="hidden" name="sbtotal[]" class="sbtotal">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success rounded-circle btn-addRow">
                                    <i class="fa fa-plus"></i></button>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
