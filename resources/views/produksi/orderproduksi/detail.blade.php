@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Detail Order Produksi </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Produksi</span>
                / <a href="{{route('order.index')}}"><span>Order Produksi</span></a>
                / <span class="text-primary font-weight-bold"> Detail Order Produksi</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Detail Order Produksi</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('order.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <form id="form">
                                <section>

                                    <div class="row">

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Tanggal</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fa fa-calendar" aria-hidden="true"></i></span>
                                                </div>
                                                <input type="text" name="po_date"
                                                       class="form-control form-control-sm datepicker" readonly="" autocomplete="off" id="tanggal"
                                                       value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Nota</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" name="">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Supplier</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" name="">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Total Harga</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="total_harga" id="total_harga" readonly>
                                                <input type="hidden" name="tot_hrg" id="tot_hrg">
                                            </div>
                                        </div>
                                        
                                           
                                        
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" cellspacing="0"
                                               id="table_order">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th>Kode Barang/Nama Barang</th>
                                                <th width="10%">Satuan</th>
                                                <th width="10%">Jumlah</th>
                                                <th>Harga</th>
                                                <th>Sub Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input readonly="" type="text" class="form-control form-control-sm" name="">
                                                    </td>
                                                    <td>
                                                        <input readonly="" type="text" class="form-control form-control-sm" name="">
                                                    </td>
                                                    <td>
                                                        <input readonly="" type="text" class="form-control form-control-sm" name="">
                                                    </td>
                                                    <td>
                                                        <input readonly="" type="text" class="form-control form-control-sm" name="">
                                                    </td>
                                                    <td>
                                                        <input readonly="" type="text" class="form-control form-control-sm" name="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                    <hr >
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" cellspacing="0"
                                               id="table_order_termin">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th>Termin</th>
                                                <th>Estimasi</th>
                                                <th>Nominal</th>
                                                <th>Tanggal</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="" readonly="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="" readonly="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="" readonly="">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="" readonly="">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </section>
                            </form>

                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="{{route('order.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        $(document).ready(function(){
            var table, table2;

            table = $('#table_order').DataTable();
            table2 = $('#table_order_termin').DataTable();
        });
    </script>
@endsection
