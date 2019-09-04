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
                            <h3 class="title"> Master Gaji Pokok Pegawai </h3>
                        </div>
                        <div class="header-block pull-right">
                            {{-- <button type="button" class="btn btn-primary" id="e-create"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button> --}}
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row form-table mb-3">
                                <div class="table-responsive col-12">
                                    <table class="table table-hover table-striped display nowrap" style="width: 100%" cellspacing="0" id="table_gajipokok">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th width="25%">NIP</th>
                                                <th width="40%">Nama</th>
                                                <th width="35%">Gaji Pokok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $item->e_nip }}<input type="hidden" class="e_id" id="{{ $item->e_id }}" name="e_id[]" value="{{ $item->e_id }}"></td>
                                                    <td>{{ $item->e_name }}</td>
                                                    <td><input type="text" class="form-control form-control-sm gaji rupiah" id="gaji{{ $item->e_id }}" name="gaji[]" value="{{ $item->e_salary }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 pull-right text-right">
                                    <button type="button" class="btn btn-primary btn-simpan" onclick="simpanMasterGaji()">Simpan</button>
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
    var table_mastergaji;
    $(document).ready(function(){
        table_mastergaji = $('#table_gajipokok').DataTable();
    })

    function simpanMasterGaji(){
        loadingShow();
        let e_id = [];
        let value = [];

        $('.gaji').each(function(idx){
            value[idx] = $(this).val();
        })
        $('.e_id').each(function(idx){
            e_id[idx] = $(this).val();
        })
        axios.post('{{ route("salary.saveGajiPokok") }}', {
            "gaji": value,
            "e_id": e_id,
            "_token": "{{ csrf_token() }}"
        }).then(function(response){
            loadingHide();
            if (response.data.status == 'sukses') {
                messageSuccess("Berhasil", "Data berhasil disimpan");
                getMasterGajiPokok();
            } else if (response.data.status == 'gagal') {
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }

    function getMasterGajiPokok(){
        loadingShow();
        axios.get('{{ route("salary.getMasterGajiPokok") }}', {

        }).then(function(response){
            loadingHide();
            let data = response.data;
            table_mastergaji.clear().draw();
            $.each(data, function(idx, val){
                table_mastergaji.row.add([
                    val.e_nip + '<input type="hidden" class="e_id" id="'+val.e_id+'" name="e_id[]" value="'+val.e_id+'">',
                    val.e_name,
                    '<input type="text" class="form-control form-control-sm gaji rupiah" id="gaji'+val.e_id+'" name="gaji[]" value="'+val.e_salary+'">',
                ]).draw(false);
            });

            $('.gaji').inputmask("currency", {
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

            table_mastergaji.columns.adjust();
        }).catch(function(error){
            loadingHide();
            alert('error');
        })
    }
</script>
@endsection
