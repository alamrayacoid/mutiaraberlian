@extends('main')

@section('content')

    <!-- Modal Terima Order -->
    @include('marketing.penjualanpusat.terimaorder.modal')
    @include('marketing.penjualanpusat.targetrealisasi.modal')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Penjualan Pusat </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> /
                <span class="text-primary" style="font-weight: bold;">Manajemen Penjualan Pusat</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12" id="choosetab">

                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item" id="tab1">
                            <a href="" class="nav-link active" data-target="#terimaorder" aria-controls="terimaorder"
                               data-toggle="tab" role="tab">Terima Order Penjualan</a>
                        </li>
                        <li class="nav-item" id="tab2">
                            <a href="" class="nav-link" data-target="#promosi_tahunan" aria-controls="promosi_tahunan"
                               data-toggle="tab" role="tab">Distribusi Penjualan</a>
                        </li>
                        <li class="nav-item" id="tab3">
                            <a href="" class="nav-link" data-target="#returnpenjualan" aria-controls="returnpenjualan"
                               data-toggle="tab" role="tab">Return Penjualan Agen </a>
                        </li>
                        <li class="nav-item" id="tab4">
                            <a href="" class="nav-link" data-target="#targetrealisasi" aria-controls="targetrealisasi"
                               data-toggle="tab" role="tab">Target & Realisasi Penjualan</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('marketing.penjualanpusat.terimaorder.index')
                        @include('marketing.penjualanpusat.returnpenjualan.index')
                        @include('marketing.penjualanpusat.targetrealisasi.index')

                    </div>

                </div>

            </div>

        </section>

    </article>
    <!-- Modal -->
    <div id="edittarget" class="modal fade animated fadeIn" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title">Edit Target</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Nama Barang : </label>
                            <span id="edit_namabarang">Agarillus</span>
                        </div>
                        <div class="col-md-12">
                            <label>Periode : </label>
                            <span id="edit_periode">Maret 2019</span>
                        </div>
                        <div class="col-md-12">
                            <label>Cabang : </label>
                            <span id="edit_cabang">Cabang</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label" for="edit_targetawal">Target Awal</label>
                            <input type="number" class="form-control" id="edit_targetawal" readonly>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label col-12" style="margin-left: -10px;"
                                   for="edit_satuanawal">Satuan</label>
                            <select class="form-control form-control-sm col-12 select2 satuan" style="width: 100%;"
                                    id="edit_satuanawal"></select>
                        </div>
                    </div>
                    <input type="hidden" class="edit_id">
                    <input type="hidden" class="edit_dt">
                    <form class="form-group row" id="form_updatetarget">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label" for="edit_targetbaru">Target Baru</label>
                            <input type="number" class="form-control" id="edit_targetbaru" name="targetbaru">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label col-12" style="margin-left: -10px;"
                                   for="edit_satuanbaru">Satuan</label>
                            <select class="form-control form-control-sm col-12 select2 satuan" name="satuantarget"
                                    style="width: 100%;" id="edit_satuanbaru"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateTarget()">Update</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('extra_script')
    <script type="text/javascript">
        var table_sup;

        $(document).ready(function () {
          		table_sup = $('#table_approval').DataTable({
          			responsive: true,
          			serverSide: true,
          			ajax: {
          				url: baseUrl + '/marketing/penjualanpusat/tableterima',
          				type: "get",
          				data: {
          					"_token": "{{ csrf_token() }}"
          				}
          			},
          			columns: [
          				{data: 'DT_RowIndex'},
          				{data: 'tanggal'},
          				{data: 'c_name'},
          				{data: 'po_nota'},
          				{data: 'total'},
          				{data: 'action', name: 'action'}
          			],
          			pageLength: 10,
          			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 100]]
          		});

            var table_bar = $('#table_tahunan').DataTable();
            var table_pus = $('#table_bulanan').DataTable();
            var table_par = $('#table_targetrealisasi').DataTable();

            $("#cari_namabarang").autocomplete({
                source: function (request, response) {
                    var id = [''];
                    $.ajax({
                        url: "{{ url('marketing/penjualanpusat/targetrealisasi/cari-barang') }}",
                        data: {
                            term: $("#cari_namabarang").val(),
                            idItem: id
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $('#cari_idbarang').val(data.item.id);
                }
            });

            $(document).on('click', '.btn-rejected', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Apa anda yakin?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Promosi Ditolak.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
                                ini.parents('.btn-group').html('<button class="btn btn-danger btn-sm btn-cancel-reject">Batalkan Penelokan</button>');
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
            });

            $("#datepicker").datepicker({
                format: "mm/yyyy",
                viewMode: "months",
                minViewMode: "months"
            });

            $(document).on('click', '.btn-cancel-reject', function () {
                $(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>' +
                    '<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
            })

            $(document).on('click', '.btn-approval', function () {
                $.toast({
                    heading: 'Information',
                    text: 'Promosi Diterima.',
                    bgColor: '#0984e3',
                    textColor: 'white',
                    loaderBg: '#fdcb6e',
                    icon: 'info'
                })
                $(this).parents('.btn-group').html('<button class="btn btn-primary btn-sm btn-cancel-approve">Batalkan Penerimaan</button>')
            })

            $(document).on('click', '.btn-cancel-approve', function () {
                $(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>' +
                    '<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
            })
            targetReal();
        });

        function targetReal() {
            tb_target = $('#table_target').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('targetReal.list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'st_periode'},
                    {data: 'c_name'},
                    {data: 'i_name'},
                    {data: 'target'},
                    {data: 'realisasi'},
                    {data: 'status'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function editTarget(st_id, dt_id) {
            loadingShow();
            $.ajax({
                data: {id: '"' + st_id + '"', dt_id: '"' + dt_id + '"'},
                type: "get",
                url: '{{ url("marketing/penjualanpusat/targetrealisasi/get-target") }}',
                success: function (response) {
                    $('.edit_dt').val(dt_id);
                    $('.edit_id').val(st_id);
                    $("#edit_satuanawal").select2('destroy');
                    $("#edit_satuanbaru").select2('destroy');
                    $('#edit_satuanawal').find('option').remove();
                    $('#edit_satuanbaru').find('option').remove();
                    var data = response.data;
                    var satuan = response.satuan;
                    $('#edit_namabarang').html(data.i_name);
                    $('#edit_periode').html(data.periode);
                    $('#edit_cabang').html(data.c_name);
                    $('#edit_targetawal').val(data.std_qty);
                    $("#edit_satuanawal").select2({
                        data: satuan
                    })
                    $("#edit_satuanbaru").select2({
                        data: satuan
                    })
                    $('#edit_satuanawal').val(data.std_unit);
                    $('#edit_satuanawal').trigger('change');
                    loadingHide();
                    $('#edittarget').modal('show');
                },
                error: function (e) {
                    $.toast({
                        heading: 'Warning',
                        text: e.message,
                        bgColor: '#00b894',
                        textColor: 'white',
                        loaderBg: '#55efc4',
                        icon: 'warning',
                        stack: false
                    });
                }
            })
        }

        function updateTarget() {
            var dt = $('.edit_dt').val();
            var id = $('.edit_id').val();
            $.ajax({
                data: $('#form_updatetarget').serialize(),
                type: "post",
                url: '{{ url("marketing/penjualanpusat/targetrealisasi/updateTarget/") }}/' + id + '/' + dt,
                success: function (response) {
                    if (response.status == 'sukses') {
                        messageSuccess('Berhasil', 'Data berhasil diperbarui');
                        $('#edittarget').modal('hide');
                        tb_target.ajax.reload();
                    } else {
                        messageSuccess('Gagal', 'Silahkan coba beberapa saat lagi');
                    }
                },
                error: function (e) {
                    $.toast({
                        heading: 'Warning',
                        text: e.message,
                        bgColor: '#00b894',
                        textColor: 'white',
                        loaderBg: '#55efc4',
                        icon: 'warning',
                        stack: false
                    });
                }
            })
        }

        function cariTarget() {
            var barang = $('#cari_idbarang').val();
            var periode = $('.cari_periode').val();
            tb_target.destroy();
            tb_target = $('#table_target').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: '{{ url("marketing/penjualanpusat/targetrealisasi/get-periode") }}',
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        barang: barang,
                        periode: periode
                    }
                },
                columns: [
                    {data: 'st_periode'},
                    {data: 'c_name'},
                    {data: 'i_name'},
                    {data: 'target'},
                    {data: 'realisasi'},
                    {data: 'status'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function setNull(id) {
            $('#'+id).val('');
        }

        function getdetail(id){
          var html = '';
          $.ajax({
            type: 'get',
            data: {id},
            dataType: 'JSON',
            url: "{{route('penjualanpusat.getdetail')}}",
            success : function(response){
              $('#dtanggal').val(response.data.po_date);
              $('#dagen').val(response.data.c_name);
              $('#dnota').val(response.data.po_nota);
              $('#dtotal').val(response.total);
              for (var i = 0; i < response.dt.length; i++) {
                html += '<tr>'+
                        '<td>'+ response.dt[i].i_code + ' - ' + response.dt[i].i_name +'</td>'+
                        '<td>'+response.dt[i].u_name+'</td>'+
                        '<td class="input-rupiah">'+convertToRupiah(parseInt(response.dt[i].pod_price))+'</td>'+
                        '<td class="input-rupiah">'+convertToRupiah(parseInt(response.dt[i].pod_totalprice))+'</td>'+
                        '</tr>';
              }

              $('#showdetail').html(html);
            }
          });
        }
    </script>
@endsection
