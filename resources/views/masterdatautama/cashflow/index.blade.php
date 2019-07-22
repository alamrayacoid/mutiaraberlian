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
            <h1 class="title"> Master Cashflow</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary" style="font-weight: bold;">Master Cashflow</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Master Cashflow </h3>
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
                                    <table class="table table-hover display nowrap" cellspacing="0" id="table_cashflow">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th width="10%">No</th>
                                                <th>Nama Cashflow</th>
                                                <th>Type Cashflow</th>
                                                <th>Status Cashflow</th>
                                                <th>Aksi</th>
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
    @include('masterdatautama.cashflow.modal_create')
    @include('masterdatautama.cashflow.modal_edit')

@endsection
@section('extra_script')
    <script type="text/javascript">
        var tb_cashflow;
        $(document).ready(function() {
            $('#table_cashflow').dataTable().fnDestroy();
            dataCashflow();
        })

        function dataCashflow() {
            tb_cashflow = $('#table_cashflow').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{url('/masterdatautama/mastercashflow/get-data')}}",
                    type: "get",
                    data: {
                      "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', className: 'text-center'},
                    {data: 'ac_nama'},
                    {data: 'ac_type', className: 'text-center'},
                    {data: 'ac_status', className: 'text-center'},
                    {data: 'action', className: 'text-center'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function saveCashflow() {
            var data = $('#form_create').serialize();
            axios.post('{{url('/masterdatautama/mastercashflow/save')}}', data)
            .then(function(resp) {
                if (resp.data.status == 'success') {
                    messageSuccess('Berhasil!', 'Data berhasil disimpan');
                    tb_cashflow.ajax.reload();
                    $('#modal_create').modal('hide');
                }else{
                    messageFailed('Gagal!', 'Data gagal disimpan')
                }
            })
            .catch(function(error) {

            })
        }

        function edit(id) {
            loadingShow()
            axios.get("{{url('/masterdatautama/mastercashflow/edit')}}"+"/"+id)
            .then(function(resp) {
                var select = '';
                $('#ac_id').val(resp.data.id)
                $('#edit_nama').val(resp.data.data.ac_nama)

                var ocf = icf = fcf = '';

                if (resp.data.data.ac_type == 'OCF')
                    ocf = 'selected'
                else if(resp.data.data.ac_type == 'ICF')
                    icf = 'selected'
                else if(resp.data.data.ac_type == 'FCF')
                    fcf = 'selected'

                select = '<option value="OCF" '+ocf+'>OCF</option>'
                select += '<option value="ICF" '+icf+'>ICF</option>'
                select += '<option value="FCF" '+fcf+'>FCF</option>'

                $('#edit_type').empty();
                $('#edit_type').append(select)

                loadingHide()
            })
            $('#modal_edit').modal('show')
        }

        function updateCashflow() {
            var data = $('#form_update').serialize()
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin mengubah data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function() {
                            axios.post('{{url('/masterdatautama/mastercashflow/update')}}', data)
                            .then(function(resp) {
                                if (resp.data.status == 'success') {
                                    messageSuccess('Berhasil!', 'Data berhasil diperbarui')
                                    $('#modal_edit').modal('hide')
                                    tb_cashflow.ajax.reload()
                                }else{
                                    messageFailed('Gagal!', 'Data gagal diperbarui')
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function(response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }

        function hapus(id) {
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
                        action: function() {
                            axios.get('{{url('/masterdatautama/mastercashflow/delete')}}'+'/'+id+'')
                            .then(function(resp) {
                                if (resp.data.status == 'success') {
                                    messageSuccess('Berhasil!', 'Data berhasil dihapus')
                                    tb_cashflow.ajax.reload()
                                }else{
                                    messageFailed('Gagal!', 'Data gagal dihapus')
                                }
                            })
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function(response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }
    </script>
@endsection
