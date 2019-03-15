@extends('main')

@section('content')



    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Proses Penerimaan Barang</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktifitas Produksi</span>
                / <a href="{{route('penerimaan.index')}}">Penerimaan Barang</a>
                / <span class="text-primary font-weight-bold">Proses Penerimaan Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Proses Penerimaan Barang </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('penerimaan.index')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <input type="hidden" name="order" id="order" value="{{$order}}">
                                <div class="table-responsive">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-hover" cellspacing="0" id="tbl_receiptitem">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th width="30%">Nama Barang</th>
                                                <th width="20%">Satuan</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="30%">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            {{--<button class="btn btn-primary" type="button">Simpan</button>--}}
                            <a href="{{route('penerimaan.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var tbl_receiptitem;
        $(document).ready(function(){
            tbl_receiptitem = $('#tbl_receiptitem').DataTable({
                responsive  : true,
                "paging"    : true,
                "ordering"  : false,
                "info"      : false,
                "searching" : true,
                processing  : true,
                serverSide  : true,
                ajax        : {
                                    url: "{{ route('penerimaan.listterimabarang') }}",
                                    type: "get",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        "order": $("#order").val()
                                    }
                                },
                columns     : [
                                    {data: 'barang'},
                                    {data: 'satuan'},
                                    {data: 'jumlah'},
                                    {data: 'action'}
                                ]
            });
        });

    </script>
@endsection
