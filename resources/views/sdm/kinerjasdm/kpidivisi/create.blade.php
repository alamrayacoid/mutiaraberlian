@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Aktivitas SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> /
                <span class="text-primary" style="font-weight: bold;">Aktivitas SDM</span>
                / <span>Kelola Kinerja SDM</span>
                / <span>KPI Divisi</span>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data KPI Divisi</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data KPI Divisi </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <section>

                                <div id="section-kpi">

                                    <div class="row col-12">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Nama Divisi</label>
                                        </div>

                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm select2" id="" name="">
                                                    <option value="">Pilih Divisi</option>
                                                </select>
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
                                                    <select class="form-control form-control-sm select2" id="" name="">
                                                        <option value="">Pilih Indikator</option>
                                                    </select>
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
                            <a href="{{ route('kinerjasdm.index') }}" class="btn btn-secondary">Kembali</a>
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
