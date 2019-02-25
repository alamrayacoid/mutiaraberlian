@extends('main')

@section('content')
    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Master Cabang</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary" style="font-weight: bold;">Master Cabang</span>
            </p>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Data Cabang </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a class="btn btn-primary" href="{{route('cabang.create')}}">
                                    <i class="fa fa-plus"></i>&nbsp;Tambah Data
                                </a>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display nowrap" cellspacing="0"
                                           id="table_cabang">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>Nama Cabang</th>
                                            <th>Alamat Cabang</th>
                                            <th>No Telp</th>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var tb_cabang;
        setTimeout(function () {
            TableCabang();
        }, 500);

        function TableCabang() {
            tb_cabang = $('#table_cabang').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('cabang.list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'c_name', name: 'c_name'},
                    {data: 'c_address', name: 'c_address'},
                    {data: 'c_tlp', name: 'c_tlp'},
                    {data: 'action', name: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function EditCabang(idx) {
            window.location = baseUrl + "/masterdatautama/cabang/edit/" + idx;
        }

        function DeleteCabang(idx) {
            var url_hapus = baseUrl + "/masterdatautama/cabang/delete/" + idx;

            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin menghapus data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "get",
                                url: url_hapus,
                                beforeSend: function() {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'berhasil') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                        tb_cabang.ajax.reload();
                                    } else {
                                        loadingHide();
                                        messageFailed('Gagal', response.message);
                                    }
                                },
                                error: function (e) {
                                    loadingHide();
                                    messageWarning('Peringatan', e.message);
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {

                        }
                    }
                }
            });
        }
    </script>
@endsection
