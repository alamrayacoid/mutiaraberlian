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
                                <button class="btn btn-primary" id="e-create" data-toggle="modal" data-target="#modal_create">
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
                                            <th>Status</th>
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
        var table;
        $('document').ready(function () {
            setTimeout(function () {
                table = $('#table_pembayaran').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('masterdatautama.getData') }}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex'},
                        {data: 'pm_name'},
                        {data: 'ak_nomor'},
                        {data: 'ak_nama'},
                        {data: 'status'},
                        {data: 'aksi'},
                    ]
                })
            }, 250)
        })

        $('#modal_create').on('shown.bs.modal', function () {
            $('#nama').val('');
            $('#akun').val('');
            $('#note').val('');
        })

        function simpan() {
            let nama = $('#nama').val();
            let akun = $('#akun').val();
            let note = $('#note').val();

            if (nama == '' || akun == '' || note == ''){
                loadingHide();
                messageWarning('Perhatian', 'Form wajib diisi');
                return false;
            }

            axios.post('{{ route("masterdatautama.save") }}', {
                "nama": nama,
                "akun": akun,
                "note": note,
                "_token": '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    $('#modal_create').modal('hide');
                    table.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.status);

                }
            }).catch(function (error) {
                alert('error');
            })
        }

        function hapus(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin mau menghapus data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text:'Ya',
                        action : function(){
                            axios.post('{{ route("masterdatautama.delete") }}', {
                                "id": id,
                                "_token": "{{ @csrf_token() }}"
                            }).then(function(response){
                                if (response.data.status == 'sukses'){
                                    messageSuccess("Berhasil", "Data berhasil dihapus");
                                    table.ajax.reload();
                                } else if (response.data.status == 'gagal'){
                                    messageFailed("Gagal", response.data.status);
                                }
                            }).catch(function(error){
                                alert("error");
                            })
                        }
                    },
                    cancel:{
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

        function enable(id){
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin akan mengaktifkan data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text:'Ya',
                        action : function(){
                            axios.post('{{ route("masterdatautama.enable") }}', {
                                "id": id,
                                "_token": "{{ @csrf_token() }}"
                            }).then(function(response){
                                if (response.data.status == 'sukses'){
                                    messageSuccess("Berhasil", "Data berhasil diaktifkan");
                                    table.ajax.reload();
                                } else if (response.data.status == 'gagal'){
                                    messageFailed("Gagal", response.data.status);
                                }
                            }).catch(function(error){
                                alert("error");
                            })
                        }
                    },
                    cancel:{
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

        function disable(id){
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin akan menonaktifkan data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text:'Ya',
                        action : function(){
                            axios.post('{{ route("masterdatautama.disable") }}', {
                                "id": id,
                                "_token": "{{ @csrf_token() }}"
                            }).then(function(response){
                                if (response.data.status == 'sukses'){
                                    messageSuccess("Berhasil", "Data berhasil dinonaktifkan");
                                    table.ajax.reload();
                                } else if (response.data.status == 'gagal'){
                                    messageFailed("Gagal", response.data.status);
                                }
                            }).catch(function(error){
                                alert("error");
                            })
                        }
                    },
                    cancel:{
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

        function edit(id){

        }

    </script>
@endsection
