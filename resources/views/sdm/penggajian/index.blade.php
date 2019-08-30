@extends('main')

@section('content')

<!-- modal scoreboard pegawai -->
@include('sdm.penggajian.cashbon.modal')
@include('sdm.penggajian.reward.modal')
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
                        <a href="#tabreward" class="nav-link" data-target="#tabreward"
                            aria-controls="tabreward" data-toggle="tab" role="tab">Reward & Punishment</a>
                    </li>
                    <li class="nav-item">
                        <a href="#list_tunjangan" class="nav-link" data-target="#list_tunjangan"
                            aria-controls="list_tunjangan" data-toggle="tab" role="tab">Tunjangan</a>
                    </li>
                    <li class="nav-item">
                        <a href="#list_payrollmanajemen" class="nav-link" data-target="#list_payrollmanajemen"
                            aria-controls="list_payrollmanajemen" data-toggle="tab" role="tab">Salary</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('sdm.penggajian.cashbon.tab_cashbon')
                    @include('sdm.penggajian.tunjangan.tab_tunjangan')
                    @include('sdm.penggajian.reward.tab_reward')
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
    var table_masterreward;
    var table_masterpunishment;
    var table_rewardpunishment;
    var table_detailrewardpunishment;
	$(document).ready(function(){
		var table_bar = $('#table_tunjangan').DataTable();
		var table_rab = $('#table_payrollmanajemen').DataTable();

        setTimeout(function(){
            filterCashbon();
        },500);

        setTimeout(function(){
            table_masterreward = $('#table_masterreward').DataTable({
                responsive: true,
                searching: false,
                paging: false
            });

            table_masterpunishment = $('#table_masterpunishment').DataTable({
                responsive: true,
                searching: false,
                paging: false
            });

            table_rewardpunishment = $('#table_rewardpunishment').DataTable();
            table_detailrewardpunishment = $('#table_detailrewardpunishment').DataTable();

            table_masterreward.columns.adjust();
            table_masterpunishment.columns.adjust();
            table_detailrewardpunishment.columns.adjust();
            table_rewardpunishment.columns.adjust();
        },700);

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

        $("#periode_reward").datepicker( {
            format: "mm-yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
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
        table_cashbon.columns.adjust();
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

// Reward
    function getMasterReward(){
        loadingShow();
        $('.add_masterbenefits').val('');
        axios.get('{{ route("reward.getDataMasterReward") }}', {
            params:{

            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_masterreward.clear().draw();
            $.each(data, function(idx, val){
                table_masterreward.row.add([
                    val.b_name,
                    '<center><button type="button" class="btn btn-warning btn-sm" onclick="editMasterReward('+val.b_id+')">Edit</button></center>'
                ]).draw().node();
            });
            table_masterreward.columns.adjust();
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function getMasterPunishment(){
        loadingShow();
        $('.add_masterbenefits').val('');
        axios.get('{{ route("reward.getDataMasterPunishment") }}', {
            params:{

            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_masterpunishment.clear().draw();
            $.each(data, function(idx, val){
                table_masterpunishment.row.add([
                    val.b_name,
                    '<center><button type="button" class="btn btn-warning btn-sm" onclick="editMasterReward('+val.b_id+')">Edit</button></center>'
                ]).draw().node();
            });
            table_masterpunishment.columns.adjust();
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function tambahMasterBenefits(type){
        loadingShow();
        let reward = '';
        if (type == 'R') {
            reward = $('#add_masterreward').val();
        }
        else if (type == 'P') {
            reward = $('#add_masterpunishment').val();
        }
        if (reward == null || reward == '') {
            loadingHide();
            messageWarning("Perhatian", "Input tidak boleh kosong!!");
            return false;
        }
        axios.post('{{ route("reward.saveMasterBenefits") }}', {
            "_token": "{{ csrf_token() }}",
            "nama": reward,
            "type": type
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                if (type == 'R') {
                    getMasterReward();
                }
                else if (type == 'P') {
                    getMasterPunishment();
                }
            } else if (response.data.status == 'gagal') {
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function getDataRewardPunishment(){
        loadingShow();
        axios.get('{{ route("reward.getDataRewardPunishment") }}', {
            params:{
                periode: $('#periode_reward').val()
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_rewardpunishment.clear().draw();
            $.each(data, function(idx, val){
                if (val.reward == null) {
                    val.reward = 0;
                }
                if (val.punishment == null) {
                    val.punishment = 0;
                }
                table_rewardpunishment.row.add([
                    val.e_nip,
                    val.e_name,
                    '<span class="pull-right">'+convertToRupiah(val.reward)+'</span>',
                    '<span class="pull-right">'+convertToRupiah(val.punishment)+'</span>',
                    '<center><button type="button" class="btn btn-primary btn-sm" onclick="detailRewardPunishment(\''+val.e_id+'\')">Detail</button>'+
                    '<button type="button" class="btn btn-warning btn-sm" onclick="editRewardPunishment(\''+val.e_id+'\')">Edit</button></center>'
                ]).draw().node();
            });
            table_rewardpunishment.columns.adjust();
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function detailRewardPunishment(id){
        loadingShow();
        axios.get('{{ route("reward.getDetailRewardPunishment") }}', {
            params:{
                "e_id": id,
                "periode": $('#periode_reward').val()
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            $('.nama_pegawai').html(data[0].e_name);
            $('.nip_pegawai').html(data[0].e_nip);
            table_detailrewardpunishment.clear().draw();
            $.each(data, function(idx, val){
                if (val.b_name != null) {
                    if (val.b_type == 'P') {
                        val.b_type = 'Punishment';
                    }
                    if (val.b_type == 'R') {
                        val.b_type = 'Reward';
                    }
                    table_detailrewardpunishment.row.add([
                        val.b_name,
                        val.b_type,
                        convertToRupiah(val.ebd_value)
                    ]).draw().node();
                }
            });
            table_detailrewardpunishment.columns.adjust();
            $('#modal_detailmasterreward').modal('show');
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function editRewardPunishment(id){
        let reward = $('#periode_reward').val();
        location.href = baseUrl + '/sdm/penggajian/reward-punishment/edit-reward-punishment/' + id + '/' + reward;
    }
</script>
@endsection
