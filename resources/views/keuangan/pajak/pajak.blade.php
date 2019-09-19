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
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
    <script type="text/javascript">

    </script>
@endsection
