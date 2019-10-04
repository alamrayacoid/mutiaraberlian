@extends('main')

@section('content')
    @include('notifikasiotorisasi.otorisasi.revisi.orderproduksi.detail')
    @include('notifikasiotorisasi.otorisasi.revisi.produk.modal-detail')
    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Otorisasi Promosi </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Notifikasi & Otorisasi</span>
                / <a href="{{route('otorisasi')}}">Otorisasi</a>
                / <span class="text-primary font-weight-bold">Otorisasi Promosi</span>
            </p>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header bordered p-2">
                    <div class="header-block">
                        <h3 class="title">Data Produk</h3>
                    </div>
                    <div class=""></div>
                </div>
                <div class="card-block">
                    <section>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_promotion">
                                <thead class="bg-primary">
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Kode</th>
                                    <th>Judul Promosi</th>
                                    <th>Jenis</th>
                                    <th>Rencana</th>
                                    <th>Biaya Promosi</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </section>

    </article>
    @include('marketing.manajemenmarketing.modal')
    @include('notifikasiotorisasi.otorisasi.promotion.approvemodal')
@endsection
@section('extra_script')
    <script type="text/javascript">
        var table;
        $(document).ready(function () {
            setTimeout(function(){
                table = $('#table_promotion').DataTable({
                    responsive: true,
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('promotion.data') }}",
                        type: "get"
                    },
                    columns: [
                        {data: 'DT_RowIndex'},
                        {data: 'p_reff'},
                        {data: 'p_name'},
                        {data: 'jenis'},
                        {data: 'p_additionalinput'},
                        {data: 'p_budget'},
                        {data: 'action'}
                    ],
                });
            }, 500);
        })

        function ApprovePromosi(id, budget){
            $.ajax({
                url: "{{ route('promotion.getListPaymentMethod') }}",
                type: 'get',
                beforeSend: function() {
                    loadingShow()
                },
                success: function(resp) {
                    console.log(resp);
                    $('#cashAccountPP').empty();
                    $('#cashAccountPP').select2({
                        data: resp.listPaymentMethod,
                        dropdownAutoWidth : true
                    });
                    $('#cashAccountPP').prop('selectedIndex', 0).trigger('select2:select');
                },
                error: function(err) {
                    messageWarning('Error', 'Terjadi kesalahan : ' + err);
                },
                complete: function() {
                    loadingHide();
                }
            })

            $('#approve_usulan').val(convertToRupiah(budget));
            $('#id_promosi').val(id);
            $('#modal_approve').modal('show');
        }

        function setuju() {
            loadingShow();
            axios.post('{{ route("promotion.approve") }}', {
                'id': $('#id_promosi').val(),
                'realisasi': convertToAngka($('#approve_realisasi').val()),
                'cashAccount': $('#cashAccountPP').val(),
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    $('#modal_approve').modal('hide');
                    messageSuccess("Berhasil", "Promosi disetujui");
                    table.ajax.reload();
                } else if (response.data.status == 'unauth'){
                    messageWarning("Perhatian", "Anda tidak memiliki akses");
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", "Data gagal disimpan");
                }
            })
        }

        function TolakPromosi(id){
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin akan menolak pengajuan ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            axios.post('{{ route("promotion.reject") }}', {
                                "id": id
                            }).then(function (response) {
                                if (response.data.status == 'success'){
                                    messageSuccess("Berhasil", "Promosi berhasil ditolak");
                                    table.ajax.reload();
                                } else if (response.data.status == 'unauth'){
                                    messageSuccess("Peringatan", "Anda tidak memiliki akses");
                                } else if (response.data.status == 'gagal'){
                                    messageFailed("Gagal", "data gagal dihapus")
                                }
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

        function DetailPromosi(id) {
            loadingShow();
            axios.get('{{ route("monthpromotion.detailpromosi") }}', {
                params:{
                    "id": id
                }
            }).then(function (response) {
                loadingHide();
                let data = response.data.data;
                $('#detail_judulpromosi').val(data.p_name);
                $('#detail_kodepromosi').val(data.p_reff);
                let jenis = '';
                if (data.p_type == 'B'){
                    jenis = 'Bulanan';
                    $('.tahun').hide();
                    $('.bulan').show();
                    $('#detail_bulanpromosi').val(data.p_additionalinput);
                } else if (data.p_type == 'T'){
                    jenis = 'Tahunan';
                    $('.tahun').show();
                    $('.bulan').hide();
                    $('#detail_tahunpromosi').val(data.p_additionalinput);
                }
                $('#detail_jenispromosi').val(jenis);
                $('#detail_biayausulanpromosi').val(parseInt(data.p_budget));
                $('#detail_biayasetujuipromosi').val(parseInt(data.p_budgetrealization));
                let status = '';
                if (data.p_isapproved == 'P'){
                    status = 'Diajukan';
                } else if (data.p_isapproved == 'Y'){
                    status = 'Disetujui';
                }
                $('#detail_statuspromosi').val(status);
                $('#detail_outputpromosi').val(data.p_outputplan);
                $('#detail_outcomepromosi').val(data.p_outcomeplan);
                $('#detail_impactpromosi').val(data.p_impactplan);
                $('#detail_catatanpromosi').val(data.p_note);
                $('#detailpromosi').modal('show');
            }).catch(function (error) {
                loadingHide();
            });

        }
    </script>
@endsection
