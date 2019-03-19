@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Edit Data Distribusi Barang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('mngagen.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Edit Data Distribusi Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Edit Data Distribusi Barang</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>

                                <div class="row">

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Tujuan</label>
                                    </div>

                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="" id="select-order" class="form-control form-control-sm select2">
                                                <option value="0">Pilih Tujuan</option>
                                                <option value="1">Agen</option>
                                                <option value="2">Cabang</option>
                                            </select>
                                        </div>
                                    </div>
                                    @include('inventory.distribusibarang.distribusi.edit-agen')
                                    @include('inventory.distribusibarang.distribusi.edit-cabang')
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary">Kembali</a>
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
            $('#type_cus').change(function(){
                if($(this).val() === 'kontrak'){
                    $('#label_type_cus').text('Jumlah Bulan');
                    $('#jumlah_hari_bulan').val('');
                    $('#pagu').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').addClass('d-none');
                    $('.122mm').removeClass('d-none');
                } else if($(this).val() === 'harian'){
                    $('#label_type_cus').text('Jumlah Hari');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('#jumlah_hari_bulan').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').removeClass('d-none');
                } else {
                    $('#jumlah_hari_bulan').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').addClass('d-none');
                    $('.125mm').addClass('d-none');
                }
            });


            $(document).on('click', '.btn-hapus-agen', function(){
                $(this).parents('tr').remove();
            });

            $('.btn-tambah-agen').on('click',function(){
                $('#table_agen')
                    .append(
                        '<tr>'+
                        '<td><input type="text" class="form-control form-control-sm"></td>'+
                        '<td><select name="#" id="#" class="form-control form-control-sm select2"><option value=""></option></select></td>'+
                        '<td><input type="number" class="form-control form-control-sm" value="0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
                        '<td><button class="btn btn-danger btn-hapus-agen btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+
                        '</tr>'
                    );
            });

            $(document).on('click', '.btn-hapus-cabang', function(){
                $(this).parents('tr').remove();
            });

            $('.btn-tambah-cabang').on('click',function(){
                $('#table_cabang')
                    .append(
                        '<tr>'+
                        '<td><input type="text" class="form-control form-control-sm"></td>'+
                        '<td><select name="#" id="#" class="form-control form-control-sm select2"><option value=""></option></select></td>'+
                        '<td><input type="number" class="form-control form-control-sm" value="0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>'+
                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
                        '<td><button class="btn btn-danger btn-hapus-cabang btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+
                        '</tr>'
                    );
            });
            $(document).on('click', '.btn-submit', function(){
                $.toast({
                    heading: 'Success',
                    text: 'Data Berhasil di Simpan',
                    bgColor: '#00b894',
                    textColor: 'white',
                    loaderBg: '#55efc4',
                    icon: 'success'
                })
            })
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#select-order').change(function(){
                var ini, agen, cabang;
                ini         = $(this).val();
                agen        = $('#agen');
                cabang      = $('#cabang');

                if (ini === '1') {
                    agen.removeClass('d-none');
                    cabang.addClass('d-none');
                } else if(ini === '2'){
                    agen.addClass('d-none');
                    cabang.removeClass('d-none');
                } else {
                    agen.addClass('d-none');
                    cabang.addClass('d-none');
                }
            });
        });
    </script>
@endsection