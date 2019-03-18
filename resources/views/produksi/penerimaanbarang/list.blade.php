@extends('main')

@section('content')

    @include('produksi.penerimaanbarang.penerimaan')

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
                                                <th width="10%">QTY Terima</th>
                                                <th width="30%" class="text-center">Aksi</th>
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
                "info"      : true,
                "searching" : true,
                processing  : true,
                serverSide  : true,
                ajax        : {
                                    url: "{{ url('/produksi/penerimaanbarang/getlistitem') }}"+'/'+$("#order").val(),
                                    type: "get"
                                },
                columns     : [
                                    {data: 'barang'},
                                    {data: 'satuan'},
                                    {data: 'jumlah'},
                                    {data: 'terima'},
                                    {data: 'action'}
                                ]
            });
        });

        function receipt(id, item) {
            var sisa = 0;
            var terima = 0;
            axios.get(baseUrl+'/produksi/penerimaanbarang/terimalistitem'+'/'+id+'/'+item)
                .then(function (response) {
                    // handle success
                    if (response.data.status == "Success") {
                        if (response.data.data.terima == null) {
                            terima = 0;
                        } else {
                            terima = response.data.data.terima
                        }

                        sisa += parseInt(response.data.data.jumlah) - parseInt(terima);

                        $("#idOrder").val(response.data.data.id);
                        $("#idItem").val(response.data.data.item);
                        $("#txtBarang").text(response.data.data.barang);
                        $("#txtSatuan").text(response.data.data.satuan);
                        $("#txtJumlah").text(response.data.data.jumlah);
                        $("#txtTerima").text(terima);
                        $("#txtSisa").text(sisa);
                        $("#penerimaanOrderProduksi").modal('show');
                    } else {
                        messageFailed("Gagal", "Terjadi kesalahan sistem")
                    }
                    console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    messageWarning('Error', error);
                })
                .then(function () {
                    // always executed
                });
        }

    </script>
@endsection
