@extends('main')

@section('extra_style')
<style type="text/css">
    a:not(.btn){
        text-decoration: none;
    }
    .card img{
        margin: auto;
    }
    .card-custom{
        min-height: calc(100vh / 2);
    }
    .card-custom:hover,
    .card-custom:focus-within{
        background-color: rgba(255,255,255,.6);
    }
</style>
@endsection

@section('content')

<article class="content">

    <div class="title-block text-primary">
        <h1 class="title">Laporan Keuangan </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Keuangan</span>
            / <a href="#"><span>Laporan Keuangan</span></a>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{route('laporan.keuangan.jurnal_umum')}}">
                    <div class="card text-center p-4 card-custom text-info">
                        <img src="{{asset('assets/img/quality.png')}}" height="128px" width="128px">
                        <h6>Laporan Jurnal Umum</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{route('laporan.keuangan.neraca')}}">
                    <div class="card text-center p-4 card-custom text-info">
                        <img src="{{asset('assets/img/quality.png')}}" height="128px" width="128px">
                        <h6>Laporan Neraca</h6>
                    </div>
                </a>
            </div>
        </div>

    </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
@endsection
