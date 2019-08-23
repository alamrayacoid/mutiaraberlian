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

    function filterCashbon(mode){
        let pegawai = $('#id_pegawai').val();
        let cashbontop = $('#cashbontop').val();
        let cashbonbot = $('#cashbonbot').val();

        if (mode == 'all') {
            pegawai = null;
            cashbonbot = null;
            cashbontop = null;
        }

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

    function terimaCashbon(id, cashbon, saldo){
        $('.cashbonnow').val(cashbon);
        $('#cashbonsisa').val(cashbon);
        $('.saldopegawai').val(saldo);
        $('.saldoawalpegawai').val(saldo);
        $('#terima_cashbon').val(0);
        $('.id_pegawai').val(id);
        $('#penerimaan_cashbon').modal('show');
    }

    $('#terima_cashbon').on('keyup', function(){
        let cashbon = $('.cashbonnow').val();
        let terima = $('#terima_cashbon').val();
        saldoawal = parseInt($('.saldoawalpegawai').val());
        saldo = 0;
        sisa = cashbon - terima;
        if (sisa < 0) {
            saldo = saldoawal + (sisa * (-1));
            sisa = 0;
        } else {
            saldo = saldoawal;
        }
        $('#saldopegawai').val(saldo);
        $('#cashbonsisa').val(sisa);
    })

    function simpanPenerimaanCashbon(){
        loadingShow();
        axios.post('{{ route("cashbon.savePenerimaan") }}', {
            "terima": $('#terima_cashbon').val(),
            "pegawai": $('#penerimaan_idpegawai').val(),
            "_token": '{{ csrf_token() }}'
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess('Berhasil', 'Data berhasil disimpan');
                $('#penerimaan_cashbon').modal('hide');
                table_cashbon.ajax.reload();
            } else if (response.data.status == 'gagal') {
                messageWarning('Gagal', response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function tambahCashbon(id, cashbon, saldo){
        $('.cashbonnow').val(cashbon);
        $('.cashbonawal').val(cashbon);
        $('.saldopegawai').val(saldo);
        $('.saldoawalpegawai').val(saldo);
        $('#addcashbon').val(0);
        $('.id_pegawai').val(id);
        $('#pembayaran_cashbon').modal('show');
    }

    $('#addcashbon').on('keyup', function(){
        let cashbonAwal = parseInt($('.cashbonawal').val());
        let cashbon = cashbonAwal;
        let saldoAwal = $('.saldoawalpegawai').val();
        let saldo = saldoAwal;
        let tambah = $('#addcashbon').val();
        let sisa = saldo - tambah;
        console.log(saldo, sisa);
        if (sisa < 0) {
            sisa = sisa * (-1);
            saldo = 0;
            cashbon = cashbonAwal + sisa;
        } else {
            saldo = sisa;
            cashbon = cashbonAwal;
        }
        $('.cashbonnow').val(cashbon);
        $('.saldopegawai').val(saldo);
    })

    function simpanPembayaranCashbon(){
        loadingShow();
        axios.post('{{ route("cashbon.savePembayaran") }}', {
            "bayar": $('#addcashbon').val(),
            "pegawai": $('#pembayaran_idpegawai').val(),
            "_token": '{{ csrf_token() }}'
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess('Berhasil', 'Data berhasil disimpan');
                $('#pembayaran_cashbon').modal('hide');
                table_cashbon.ajax.reload();
            } else if (response.data.status == 'gagal') {
                messageWarning('Gagal', response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }
</script>
@endsection
