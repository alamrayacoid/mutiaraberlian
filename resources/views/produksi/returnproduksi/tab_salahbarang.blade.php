<fieldset class="mt-3 d-none animated fadeIn" id="salah_barang">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <label>Nota Penjualan<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                <select class="form-control form-control-sm select2">
                    <option value="">--Pilih Nota--</option>
                </select>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <label>No Resi dari Cus<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" name="">
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
            <label>Kirim</label>
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
        <table class="table table-bordered table-hover table-striped" cellspacing="0" id="tabel_return_3">
            <thead class="bg-primary">
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Kirim</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Disc Percent</th>
                    <th>Disc Value</th>
                    <th>Jumlah Kirim</th>
                    <th>Total Barang Sesuai</th>
                </tr>
            </thead>    
        </table>
    </div>

    <hr>

    <h3>Masukkan Item Salah Kirim</h3>

    <hr>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-12">
            <div class="form-group">
                <label>Masukkan Kode/Nama Barang</label>
                <input type="text" class="form-control-sm form-control" name="">
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
            <div class="form-group">
                <label>Masukkan Jumlah</label>
                <input type="number" min="0" class="form-control-sm form-control" name="">
            </div>
        </div>
        <div class="col-md-3 col-sm-3 col-12">
            <div class="form-group">
                <label>Kuantitas Stok</label>
                <input type="number" min="0" class="form-control-sm form-control" name="">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" cellspacing="0" id="table_salahkirim_1">
            <thead class="bg-primary">
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th width="1%"></th>
                </tr>
            </thead>
        </table>
    </div>
</fieldset>