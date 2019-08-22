@extends('main')

@section('content')

<!-- modal scoreboard pegawai -->
@include('sdm.penggajian.cashbon.modal')
@include('sdm.penggajian.payrollmanajemen.modal_tambah')
<!-- end -->

<article class="content">

    <div class="title-block text-primary">
        <h1 class="title"> Kelola Penggajian </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas SDM</span> / <span
                class="text-primary" style="font-weight: bold;">Kelola Pengajian</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#list_manajemen" class="nav-link active" data-target="#list_manajemen"
                            aria-controls="list_manajemen" data-toggle="tab" role="tab">Cashbon</a>
                    </li>
                    <li class="nav-item">
                        <a href="#list_tunjangan" class="nav-link" data-target="#list_tunjangan"
                            aria-controls="list_tunjangan" data-toggle="tab" role="tab">Reward & Punishment</a>
                    </li>
                    <li class="nav-item">
                        <a href="#list_produksi" class="nav-link" data-target="#list_produksi"
                            aria-controls="list_produksi" data-toggle="tab" role="tab">Tunjangan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#list_payrollmanajemen" class="nav-link" data-target="#list_payrollmanajemen"
                            aria-controls="list_payrollmanajemen" data-toggle="tab" role="tab">Salary</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('sdm.penggajian.cashbon.tab_cashbon')
                    @include('sdm.penggajian.tunjangan.tab_tunjangan')
                    @include('sdm.penggajian.produksi.tab_produksi')
                    @include('sdm.penggajian.payrollmanajemen.tab_payrollmanajemen')

                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var table_cashbon;
	$(document).ready(function(){
		var table_bar = $('#table_tunjangan').DataTable();
		var table_pus = $('#table_produksi').DataTable();
		var table_rab = $('#table_payrollmanajemen').DataTable();

        setTimeout(function(){
            filterCashbon();
        },500);

        $("#namapegawai").autocomplete({
            source: '{{ route("cashbon.getDataPegawai") }}',
            minLength: 1,
            select: function (event, data) {
                $("#id_pegawai").val(data.item.id);
            }
        });

        $('.rupiahnull').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: ' Rp ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: true,
            // unmaskAsNumber: true,
        });

    })

    function filterCashbon(){
        let pegawai = $('#id_pegawai').val();
        let cashbontop = $('#cashbontop').val();
        let cashbonbot = $('#cashbonbot').val();

        if ( $.fn.DataTable.isDataTable( '#table_cashbon' ) ) {
            $('#table_cashbon').dataTable().fnDestroy();
        }
        table_cashbon = $('#table_cashbon').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("cashbon.getData") }}',
                type: "get",
                data: {
                    "pegawai": pegawai,
                    "cashbontop": cashbontop,
                    "cashbonbot": cashbonbot
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'e_nip', name: 'e_nip'},
                {data: 'e_name', name: 'e_name'},
                {data: 'e_cashbon', name: 'e_cashbon'},
                {data: 'aksi', name: 'aksi'}
            ],
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function terimaCashbon(id, cashbon){
        $('.cashbonnow').val(cashbon);
        $('#cashbonsisa').val(cashbon);
        $('#penerimaan_cashbon').modal('show');
    }

    $('#terima_cashbon').on('keyup', function(){
        let cashbon = $('.cashbonnow').val();
        let terima = $('#terima_cashbon').val();
        saldo = 0;
        sisa = cashbon - terima;
        if (sisa < 0) {
            saldo = sisa * (-1);
            sisa = 0;
        }
        $('#saldopegawai').val(saldo);
        $('#cashbonsisa').val(sisa);
        console.log(saldo);
        console.log(terima);
        console.log(cashbon);
    })

    function tambahCashbon(id, cashbon){
        $('.cashbonnow').val(cashbon);
        $('#pembayaran_cashbon').modal('show');
    }
</script>
@endsection
