<div class="d-none animated fadeIn container" id="tukar_barang">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <label>Jenis Return<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                <select class="form-control form-control-sm">
                    <option value="">Barang Rusak</option>
                    <option value="">Kelebihan Barang</option>
                </select>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Tanggal Return</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Metode Pembayaran</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm" readonly="" name="">
            </div>
        </div>

    </div>

    <hr>

    <div class="row">
        
        <div class="col-md-3 col-sm-6 col-12">
            <label>Detail Pelanggan</label>
        </div>

        <div class="col-md-9 col-sm-6 col-12">
            <div class="form-group">
                <input type="text" readonly="" class="form-control form-control-sm" name="">
            </div>
        </div>


        <div class="col-md-3 col-sm-6 col-12">
            <label>Total Tukar</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm" readonly="" name="">
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>S Gross</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm" readonly="" name="">
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Total Diskon</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm" readonly="" name="">
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>Total Penjualan Nett</label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                
                <input type="text" class="form-control form-control-sm" readonly="" name="">
            </div>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped" cellspacing="0" id="tabel_return_2">
            <thead class="bg-primary">
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Tukar</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Disc Percent</th>
                    <th>Disc Value</th>
                    <th>Jumlah Tukar</th>
                    <th>Total</th>
                </tr>
            </thead>    
        </table>
    </div>
</div>