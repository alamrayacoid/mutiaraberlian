@extends('main')
@section('extra_style')
    <style>
        #table_cabang td {
            padding: 5px;
        }
        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1140px;
            }
        }
    </style>
@endsection
@section('content')
    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Master Pembayaran</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary" style="font-weight: bold;">Master Pembayaran</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Master Pembayaran </h3>
                            </div>
                            <div class="header-block pull-right">
                                <button data-toggle="modal" data-target="#modal_create" class="btn btn-primary" id="e-create">
                                    <i class="fa fa-plus"></i>&nbsp;Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="table-responsive">
                                    <table class="table table-hover display nowrap" cellspacing="0" id="table_pembayaran">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pembayaran</th>
                                            <th>Akun</th>
                                            <th>Nama Akun</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>

    {{--Modal--}}
    @include('masterdatautama.pembayaran.modal')

@endsection
@section('extra_script')
    <script type="text/javascript">

    </script>
@endsection
