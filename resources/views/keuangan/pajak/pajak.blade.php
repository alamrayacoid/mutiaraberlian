@extends('main')
@section('tittle')
    Penerimaan Piutang
@endsection
@section('extra_style')
    <style>

        .border-half{
            border-radius: 15px;
        }

        @media (min-width: 992px) {
            .modal-xl {
                max-width: 1200px !important;
            }
        }
    </style>
@stop
@section('content')

    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Pajak </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Keuangan</span> /
                <span class="text-primary" style="font-weight: bold;">Manajemen Pajak</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 mt-sm-3 mt-md-0 " >
                            <div class="col-12 border-half d-flex  align-items-center justify-content-between" style="background:#2b3f87;height: 120px;color: #fff;">
                                <div class="col-4">
                                    <i class="fa fa-bar-chart-o" style="font-size: 4em;"></i>
                                </div>
                                <div class="col-8 text-right">
                                    <p>Total PPN Masukkan</p>
                                    <p class="h5">Rp. 2.000.0000,00-</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3 mt-md-0" >
                            <div class="col-12 border-half d-flex  align-items-center justify-content-between" style="background:#2b3f87;height: 120px;color: #fff;">
                                <div class="col-4">
                                    <i class="fa fa-bar-chart-o" style="font-size: 4em;"></i>
                                </div>
                                <div class="col-8 text-right">
                                    <p>Total PPN Keluaran</p>
                                    <p class="h5">Rp. 2.000.0000,00-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-2 mt-md-4">
                    <div class="ibox-content text-center">
                        <b style="font-size: 2em;">Pajak</b>
                        <div id="kinerja" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
    <script type="text/javascript">
        function line1(variable,data_data,key,x,label)
        {
            Morris.Line({
                element: variable,
                data: data_data,
                xkey: key,
                ykeys: x,
                labels: label,
                hideHover: 'auto',
                resize: true,
                barColors: ['#1ab394', '#cacaca'],
            });
        }

        $(document).ready(function(){

            line1('kinerja',
                [
                    { y: '2008', a: 5 ,b:4 },
                    { y: '2009', a: 10 ,b:4 },
                    { y: '2010', a: 8 ,b:4 },
                    { y: '2011', a: 22 ,b:4 },
                    { y: '2012', a: 8 ,b:4 },
                    { y: '2014', a: 10 ,b:4 },
                    { y: '2015', a: 5 ,b:4 }
                ],
                'y'
                ,['a','b'],
                ['Pajak Tahun lalu', 'Pajak Tahun ini']
            );
        })
    </script>
@endsection
