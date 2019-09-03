@extends('main')

@section('content')

<!-- modal scoreboard pegawai -->

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
                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Edit Tunjangan Pegawai </h3>
                        </div>
                        <div class="header-block pull-right">
                            {{-- <button type="button" class="btn btn-primary" id="e-create"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button> --}}
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label>Nama Pegawai: <span class="nama_pegawai">{{ $data[0]->e_name }}</span></label>
                                </div>
                                <div class="col-12">
                                    <label>NIP Pegawai: <span class="nip_pegawai">{{ $data[0]->e_nip }}</span></label>
                                </div>
                                <div class="col-12">
                                    <label>Periode: <span class="nip_pegawai">{{ $tanggal }}</span></label>
                                </div>
                            </div>

                            <div class="row mb-3 row-tunjangan">
                                <div class="col-2">
                                    <label>Jenis Tunjangan</label>
                                </div>
                                <div class="col-8">
                                    <select class="form-control form-control-sm select2" id="jenis_tunjangan">
                                        <option>== Pilih Jenis Tunjangan ==</option>
                                        @foreach ($tunjangan as $rew)
                                            <option value="{{ $rew->b_id }}">{{ $rew->b_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary" onclick="tambahBenefits('T')">Tambah</button>
                                </div>
                            </div>

                            <div class="row form-table">
                                <div class="table-responsive col-12">
                                    <table class="table table-hover table-striped display nowrap" style="width: 100%" cellspacing="0" id="table_edittunjangan">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th width="40%">Nama</th>
                                                <th width="20%">Jenis</th>
                                                <th width="30%">Jumlah</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 pull-right text-right">
                                    <button type="button" class="btn btn-primary btn-simpan" onclick="simpanEditTunjangan()">Simpan</button>
                                </div>
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
    var table_editrewardpunishment;
    $(document).ready(function(){
        table_editrewardpunishment = $('#table_edittunjangan').DataTable({
            paging: false,
            responsive: true,
            info: false
        });

        setTimeout(function(){
            getDataTunjangan();
        },500)
    })

    function tambahBenefits(type){
        loadingShow();
        let jenis = $('#jenis_tunjangan').val();

        if (jenis == null || jenis == '') {
            loadingHide();
            messageWarning("Perhatian", "Input tidak boleh kosong!!");
            return false;
        }
        axios.post('{{ route("reward.saveEmployeeBenefits") }}', {
            "_token": "{{ csrf_token() }}",
            "b_id": jenis,
            "type": type,
            "employe": '{{ $data[0]->e_id }}',
            "periode": '{{ $tanggal }}'
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                getDataTunjangan();
            } else if (response.data.status == 'gagal') {
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function getDataTunjangan(){
        loadingShow();
        axios.get('{{ route("tunjangan.getDataTunjanganPegawai") }}', {
            params:{
                "_token": "{{ csrf_token() }}",
                "employe": '{{ $data[0]->e_id }}',
                "periode": '{{ $tanggal }}'
            }
        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_editrewardpunishment.clear().draw();
            $.each(data, function(idx, val){
                table_editrewardpunishment.row.add([
                    val.b_name + '<input type="hidden" class="eb_id" name="eb_id[]" value="'+val.eb_id+'"><input type="hidden" class="ebd_detailid" name="ebd_detailid[]" value="'+val.ebd_detailid+'">',
                    val.b_type,
                    '<input type="text" class="form-control form-control-sm value input-rupiah" name="value[]" value="'+val.ebd_value+'">',
                    '<center><button type="button" class="btn btn-danger btn-sm" onclick="hapusTunjangan('+val.eb_id+', '+val.ebd_detailid+')"><i class="fa fa-close"></i></button></center>'
                ]).draw(false);
            });
            table_editrewardpunishment.columns.adjust();
            $('.value').inputmask("currency", {
                radixPoint: ",",
                groupSeparator: ".",
                digits: 0,
                autoGroup: true,
                prefix: ' Rp ', //Space after $, this will not truncate the first character.
                rightAlign: true,
                autoUnmask: true,
                nullable: false,
                // unmaskAsNumber: true,
            });

        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function simpanEditTunjangan(){
        loadingShow();

        let value = [];
        let eb_id = [];
        let ebd_detailid = [];

        $('.value').each(function(idx){
            value[idx] = $(this).val();
        })
        $('.eb_id').each(function(idx){
            eb_id[idx] = $(this).val();
        })
        $('.ebd_detailid').each(function(idx){
            ebd_detailid[idx] = $(this).val();
        })

        axios.post('{{ route("reward.saveDataRewardPunishmentPegawai") }}', {
            "eb_id": eb_id,
            "ebd_detailid": ebd_detailid,
            "value": value,
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                getDataTunjangan();
            } else if (response.data.status == 'gagal') {
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function hapusTunjangan(id, detail){
        return $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 2.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menghapus data ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        axios.post('{{ route("reward.deleteDataRewardPunishmentPegawai") }}', {
                            "id": id,
                            "detailid": detail
                        }).then(function(response){
                            loadingHide();
                            if (response.data.status == 'sukses') {
                                messageSuccess("Berhasil", "Data berhasil dihapus");
                                getDataTunjangan();
                            } else if (response.data.status == 'gagal') {
                                messageWarning("Gagal", response.data.message);
                            }
                        }).catch(function(error){
                            loadingHide();
                            alert('error');
                        })
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function () {
                        // tutup confirm
                    }
                }
            }
        });
    }
</script>
@endsection
