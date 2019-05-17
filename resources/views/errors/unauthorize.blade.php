@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Agen </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <a href="{{route('agen.index')}}"><span>Data Agen</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Agen</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Maaf anda tidak memiliki akses :) </h3>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')

@endsection
