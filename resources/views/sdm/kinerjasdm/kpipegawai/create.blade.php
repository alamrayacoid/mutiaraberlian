@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Aktivitas SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> /
                <span class="text-primary" style="font-weight: bold;">Aktivitas SDM</span>
                / <span>Kelola Kinerja SDM</span>
                / <span>KPI Pegawai</span>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data KPI Pegawai</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data KPI Pegawai </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="#" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>

                                <div id="section-kpi">

                                    <div class="row col-12">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Nama Pegawai</label>
                                        </div>

                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" name="">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="private-section">
                                        <div class="row col-12">
                                            <div class="col-md-2 col-sm-6 col-xs-12">
                                                <label>Indikator</label>
                                            </div>

                                            <div class="col-md-7 col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-control-sm" name="">
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <button class="btn btn-primary btn-sm rounded btn-tambahp"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>

                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="#" class="btn btn-secondary">Kembali</a>
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

            $(document).on('click', '.btn-hapus', function(){
                $(this).parents('.section2').remove();
            });

            $('.btn-tambahp').on('click',function(){
                $('#private-section')
                    .append(
                        '<div class="row col-12 section2">'+
                        '<div class="col-md-2 col-sm-6 col-xs-12">'+
                        '<label style="display: none;">~</label></div>'+
                        '<div class="col-md-7 col-sm-6 col-xs-12">'+
                        '<div class="form-group">'+
                        '<input type="text" class="form-control form-control-sm" name=""></div>'+
                        '</div>'+
                        '<div class="col-1">'+
                        '<button class="btn btn-danger btn-sm rounded btn-hapus"><i class="fa fa-trash"></i></button>'+
                        '</div>'+
                        '</div>'
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
@endsection
