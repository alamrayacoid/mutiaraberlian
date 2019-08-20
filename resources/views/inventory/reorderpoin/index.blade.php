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
            <h1 class="title"> Pengelolaan Data Re-Order Poin </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="#"><span>Kelola Data Re-order Poin & Repeat Order</span></a>
            </p>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Kelola Data Reorder Poin & Repeat Order </h3>
                            </div>
                            {{--<div class="header-block pull-right">
                                <button type="button" class="btn btn-primary" id="e-create" onclick="window.location.href = '{{route('pegawai.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                            </div>--}}
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table-reorderpoin">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Reorder</th>
                                            <th class="text-center">Status</th>
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

@endsection

@section('extra_script')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endsection
