@extends('main')

@section('content')

    @include('marketing.marketingarea.penerimaanpiutang.modal.detail')
    @include('marketing.marketingarea.penerimaanpiutang.modal.bayar')

    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Pembayaran dari Konsigner </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Kelola Data Konsinyasi </span>
            </p>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Pembayaran dari Konsigner </h3>
                            </div>
                            <div class="header-block pull-right">
                                <input type="hidden" id="userType" value="{{ Auth::user()->getCompany->c_type }}">
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formManagemenAgen">{{ csrf_field() }}
                            <div class="card-block">
                                <section>
                                    <div class="row mb-3">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Pilih Konsigner</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12">
                                            <select class="select2 form-control form-control-sm" id="konsigner" name="konsigner">
                                                <option value="semua">Semua Konsigner</option>
                                                @foreach ($konsigner as $kons)
                                                    <option value="{{ $kons->c_id }}">{{ $kons->c_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <button type="button" class="btn btn-primary" onclick="getDataPP()">Cari</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_penerimaanpiutang">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th class="text-center">Konsigner</th>
                                                <th class="text-center">Tanggal</th>
                                                <th class="text-center">Nota</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </section>
                            </div>
                            <div class="card-footer text-right">

                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var table_penerimaanpiutang;
        $(document).ready(function () {
            $('#konsigner').val('semua');
            let konsigner = $('#konsigner').val();
            setTimeout(function () {
                table_penerimaanpiutang = $('#table_penerimaanpiutang').DataTable({
                    serverSide: true,
                    processing: true,
                    searching: false,
                    paging: false,
                    responsive: true,
                    ajax: {
                        url: "{{ route('konsinyasiAgen.getData') }}",
                        type: "get",
                        data: {
                            konsigner: konsigner
                        }
                    },
                    columns: [
                        {data: 'c_name'},
                        {data: 'sc_date'},
                        {data: 'sc_nota'},
                        {data: 'sisa'},
                        {data: 'aksi'}
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
                });
            }, 500);
        })

        function getDataPP() {
            $('#table_penerimaanpiutang').dataTable().fnClearTable();
            $('#table_penerimaanpiutang').dataTable().fnDestroy();
            let konsigner = $('#konsigner').val();
            table_penerimaanpiutang = $('#table_penerimaanpiutang').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
                paging: false,
                responsive: true,
                ajax: {
                    url: "{{ route('konsinyasiAgen.getData') }}",
                    type: "get",
                    data: {
                        konsigner: konsigner
                    }
                },
                columns: [
                    {data: 'c_name'},
                    {data: 'sc_date'},
                    {data: 'sc_nota'},
                    {data: 'sisa'},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function detailnotapiutang(id){
            loadingShow();
            axios.get('{{ route("mmapenerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    "id": id
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                $('#table_detailpp > tbody').empty();
                $('#table_detailpembayaranpp > tbody').empty();
                $.each(detail, function (index, value) {
                    let no = "<td>"+(index + 1)+"</td>";
                    let nama = "<td>"+value.i_name+"</td>";
                    let qty = "<td>"+value.scd_qty+"</td>";
                    let satuan = "<td>"+value.u_name+"</td>";
                    let harga = "<td class='text-right'>"+convertToRupiah(value.scd_value)+"</td>";
                    let diskon = "<td class='text-right'>"+convertToRupiah(value.scd_discvalue)+"</td>";
                    let total = "<td class='text-right'>"+convertToRupiah(value.scd_totalnet)+"</td>";
                    $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                });
                let terbayar = 0;
                $.each(pay, function (index, value) {
                    terbayar = terbayar + parseInt(value.scp_pay);
                    let no = "<td>"+(index + 1)+"</td>";
                    let tanggal = "<td>"+value.scp_date+"</td>";
                    let nominal = "<td class='text-right'>"+convertToRupiah(value.scp_pay)+"</td>";
                    $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                });
                $('#nota_dtpp').val(detail[0].sc_nota);
                $('#date_dtpp').val(detail[0].sc_datetop);
                $('#agent_dtpp').val(detail[0].c_name);
                $('#total_dtpp').val(parseInt(detail[0].sc_total)-terbayar);
                $('#modalDetailpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                alert('error');
            })
        }

        function bayarnotapiutang(id){
            loadingShow();
            axios.get('{{ route("mmapenerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    "id": id
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                let method = response.data.jenis;
                $('#table_bayarpp > tbody').empty();
                $('#table_bayarpembayaranpp > tbody').empty();
                $('#paymentpp').empty();
                $.each(detail, function (index, value) {
                    let no = "<td>"+(index + 1)+"</td>";
                    let nama = "<td>"+value.i_name+"</td>";
                    let qty = "<td>"+value.scd_qty+"</td>";
                    let satuan = "<td>"+value.u_name+"</td>";
                    let harga = "<td class='text-right'>"+convertToRupiah(value.scd_value)+"</td>";
                    let diskon = "<td class='text-right'>"+convertToRupiah(value.scd_discvalue)+"</td>";
                    let total = "<td class='text-right'>"+convertToRupiah(value.scd_totalnet)+"</td>";
                    $('#table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                });
                let terbayar = 0;
                $.each(pay, function (index, value) {
                    terbayar = terbayar + parseInt(value.scp_pay);
                    let no = "<td>"+(index + 1)+"</td>";
                    let tanggal = "<td>"+value.scp_date+"</td>";
                    let nominal = "<td class='text-right'>"+convertToRupiah(value.scp_pay)+"</td>";
                    $('#table_bayarpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                });
                $('#paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                $.each(method, function (index, value) {
                    if ($('#userType').val() == 'PUSAT') {
                        $('#paymentpp').append('<option value="'+ value.pm_id +'">'+ value.get_akun.ak_nomor +' - '+ value.pm_name +'</option>');
                    }
                    else {
                        $('#paymentpp').append("<option value='"+value.get_akun.ak_id +"'>"+ value.get_akun.ak_nama +"</option>");
                    }
                });
                $('#nota_paypp').val(detail[0].sc_nota);
                $('#date_paypp').val(detail[0].sc_datetop);
                $('#agent_paypp').val(detail[0].c_name);
                $('#total_paypp').val(parseInt(detail[0].sc_total)-terbayar);
                $('#modalBayarpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                alert('error');
            })
        }

        function bayarPP() {
            let nota = $('#nota_paypp').val();
            let bayar = $('#bayarpaypp').val();
            let tanggal = $('#datepaypp').val();
            let paymentmethod = $('#paymentpp').val();
            if (bayar == 0 || bayar == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            if (tanggal == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            if (paymentmethod == 'disable' || paymentmethod == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            loadingShow();
            axios.get('{{ route("mmapenerimaanpiutang.bayarPiutang") }}', {
                params:{
                    "nota": nota,
                    "bayar": bayar,
                    "tanggal": tanggal,
                    "paymentmethod": paymentmethod
                }
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Pembayaran sudah tersimpan");
                    table_penerimaanpiutang.ajax.reload();
                    $('#modalBayarpp').modal('hide');
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert("error");
            })
        }
    </script>
@endsection
