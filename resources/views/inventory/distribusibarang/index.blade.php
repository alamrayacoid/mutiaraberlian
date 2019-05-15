@extends('main')

@section('content')

<!-- modal distribusi barang -->
@include('inventory.distribusibarang.distribusi.modal')
<!-- modal history -->
@include('inventory.distribusibarang.history.modal')
<!-- modal penerimaan -->
@include('inventory.distribusibarang.penerimaan.modal')

<article class="content">

    <div class="title-block text-primary">
        <h1 class="title"> Pengelolaan Distribusi Barang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> / <span class="text-primary" style="font-weight: bold;">Pengelolaan Distribusi Barang</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <ul class="nav nav-pills mb-3" id="Tabzs">
                    <li class="nav-item">
                        <a href="#distribusibarang" class="nav-link active" data-target="#distribusibarang" aria-controls="distribusibarang" data-toggle="tab" role="tab">Distribusi Barang</a>
                    </li>
                    <li class="nav-item">
                        <a href="#history" class="nav-link" data-target="#history" aria-controls="history" data-toggle="tab" role="tab">Riwayat Distribusi Barang</a>
                    </li>
                    <li class="nav-item">
                        <a href="#penerimaan" class="nav-link" data-target="#penerimaan" aria-controls="penerimaan" data-toggle="tab" role="tab">Penerimaan Distribusi Barang</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @include('inventory.distribusibarang.distribusi.index')
                    @include('inventory.distribusibarang.history.index')
                    @include('inventory.distribusibarang.penerimaan.index')

                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<!-- script for distribusibarang  -->
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        cur_date = new Date();
        first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
        last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
        $('#date_from').datepicker('setDate', first_day);
        $('#date_to').datepicker('setDate', last_day);
        tabledistribusi();

        $('#date_from').on('change', function() {
            tabledistribusi();
        });
        $('#date_to').on('change', function() {
            tabledistribusi();
        });
        $('#btn_search_date').on('click', function() {
            tabledistribusi();
        });
        $('#btn_refresh_date').on('click', function() {
            $('#date_from').datepicker('setDate', first_day);
            $('#date_to').datepicker('setDate', last_day);
        });
        // retrieve data-table
        tabledistribusi();

        $('.datepicker').datepicker();
        $(document).on('click', '.btn-enable-distribusi', function() {
            $.toast({
                heading: 'Information',
                text: 'Data Berhasil di Aktifkan.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>'+
            '<button class="btn btn-warning btn-edit-distribusi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
            '<button class="btn btn-danger btn-disable-distribusi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')

        })

        $(document).on('click', '.btn-simpan-modal', function() {
            $.toast({
                heading: 'Success',
                text: 'Data Berhasil di Simpan',
                bgColor: '#00b894',
                textColor: 'white',
                loaderBg: '#55efc4',
                icon: 'success'
            })
        })
    });

    function tabledistribusi()
  	{
  		$('#table_distribusi').dataTable().fnDestroy();
  		table = $('#table_distribusi').DataTable({
  			responsive: true,
  			serverSide: true,
  			ajax: {
  				url: baseUrl + '/inventory/distribusibarang/table',
  				type: "get",
  				data: {
  					"_token": "{{ csrf_token() }}",
                    "date_from" : $('#date_from').val(),
  					"date_to" : $('#date_to').val()
  				}
  			},
  			columns: [
  				{data: 'DT_RowIndex'},
  				{data: 'tanggal'},
  				{data: 'tujuan'},
  				{data: 'sd_nota'},
  				{data: 'action', name: 'action'}
  			],
  			pageLength: 10,
  			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
  		});
  	}

    function printNota(id)
	{
		window.location.href='{{ url('inventory/distribusibarang/nota') }}?id='+id;
	}

    function hapus(id){
      $.confirm({
          animation: 'RotateY',
          closeAnimation: 'scale',
          animationBounce: 1.5,
          icon: 'fa fa-exclamation-triangle',
          title: 'Peringatan!',
          content: 'Apa anda yakin mau menonaktifkan data ini?',
          theme: 'disable',
          buttons: {
              info: {
                  btnClass: 'btn-blue',
                  text: 'Ya',
                  action: function() {
                    $.ajax({
                      type: 'get',
                      data: {id},
                      dataType: 'json',
                      url: baseUrl + '/inventory/distribusibarang/hapus',
                      success : function(response){
                        if (response.status == 'berhasil') {
                          $.toast({
                              heading: 'Information',
                              text: 'Data Berhasil di Nonaktifkan.',
                              bgColor: '#0984e3',
                              textColor: 'white',
                              loaderBg: '#fdcb6e',
                              icon: 'info'
                          })
                          table.ajax.reload();
                        } else if (response.status == 'failed') {
                          messageFailed('Failed', response.ex);
                        } else {
                          messageFailed('Failed', 'Data Berhasil Gagal di Nonaktifkan');
                        }
                      }
                    });
                  }
              },
              cancel: {
                  text: 'Tidak',
                  action: function() {
                      // tutup confirm
                  }
              }
          }
      });
    }

    $('#rekrut_from').on('change', function() {
      tabledistribusi();
    })
    $('#rekrut_to').on('change', function() {
      tabledistribusi();
    })

    function edit(id)
    {
        window.location.href = baseUrl + '/inventory/distribusibarang/edit/'+id;
    }

</script>

<!-- script for history-distribusi -->
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $('#date_from_ht').datepicker('setDate', first_day);
        $('#date_to_ht').datepicker('setDate', last_day);

        $('#date_from_ht').on('change', function() {
            tablehistory();
        });
        $('#date_to_ht').on('change', function() {
            tablehistory();
        });
        $('#btn_search_date_ht').on('click', function() {
            tablehistory();
        });
        $('#btn_refresh_date_ht').on('click', function() {
            $('#date_from_ht').datepicker('setDate', first_day);
            $('#date_to_ht').datepicker('setDate', last_day);
        });
        // retrieve data-table
        tablehistory();
    });

    // retrieve DataTable history
    function tablehistory()
    {
        $('#table_history').dataTable().fnDestroy();
        table = $('#table_history').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: baseUrl + '/inventory/distribusibarang/table-history',
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "date_from" : $('#date_from_ht').val(),
                    "date_to" : $('#date_to_ht').val()
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'tanggal'},
                {data: 'tujuan'},
                {data: 'sd_nota'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function showDetailHt(idx)
    {
        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/detail-ht/" + idx,
            type: "get",
            success: function(response) {
                console.log(response);
                $('#nota_ht').val(response.sd_nota);
                $('#date_ht').val(response.dateFormated);
                $('#origin_ht').val(response.get_origin.c_name);
                $('#dest_ht').val(response.get_destination.c_name);
                $('#table_detail_ht tbody').empty();
                $.each(response.get_distribution_dt, function (index, val) {
                    no = '<td>'+ (index + 1) +'</td>';
                    kodeXnamaBrg = '<td>'+ val.get_item.i_code +' / '+ val.get_item.i_name +'</td>';
                    qty = '<td class="digits">'+ val.sdd_qty +'</td>';
                    unit = '<td>'+ val.get_unit.u_name +'</td>';
                    appendItem = no + kodeXnamaBrg + qty + unit;
                    $('#table_detail_ht > tbody:last-child').append('<tr>'+ appendItem +'</tr>');
                });
                //mask digits
                $('.digits').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: '', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalHistory').modal('show');

            },
            error: function(xhr, status, error) {
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
                // console.log(err.message);
            }
        });
    }

</script>

<!-- script for penerimaan-distribusi -->
<script type="text/javascript">
    var tableAc;
    $(document).ready(function() {
        $('#date_from_ac').datepicker('setDate', first_day);
        $('#date_to_ac').datepicker('setDate', last_day);

        $('#date_from_ac').on('change', function() {
            tableAcceptance();
        });
        $('#date_to_ac').on('change', function() {
            tableAcceptance();
        });
        $('#btn_search_date_ac').on('click', function() {
            tableAcceptance();
        });
        $('#btn_refresh_date_ac').on('click', function() {
            $('#date_from_ac').datepicker('setDate', first_day);
            $('#date_to_ac').datepicker('setDate', last_day);
        });
        // retrieve data-table
        tableAcceptance();

        $('#btn_confirmAc').on('click', function() {
            confirmAcceptance();
        })
    });

    // retrieve DataTable penerimaan
    function tableAcceptance()
    {
        $('#table_acceptance').dataTable().fnDestroy();
        tableAc = $('#table_acceptance').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: baseUrl + '/inventory/distribusibarang/table-acceptance',
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "date_from" : $('#date_from_ac').val(),
                    "date_to" : $('#date_to_ac').val()
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'tanggal'},
                {data: 'tujuan'},
                {data: 'sd_nota'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function showDetailAc(idx)
    {
        loadingShow();
        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/detail-ac/" + idx,
            type: "get",
            success: function(response) {
                console.log(response);
                $('#id_ac').val(response.sd_id);
                $('#nota_ac').val(response.sd_nota);
                $('#date_ac').val(response.dateFormated);
                $('#origin_ac').val(response.get_origin.c_name);
                $('#dest_ac').val(response.get_destination.c_name);
                $('#table_detail_ac tbody').empty();
                $.each(response.get_distribution_dt, function (index, val) {
                    no = '<td>'+ (index + 1) +'</td>';
                    kodeXnamaBrg = '<td>'+ val.get_item.i_code +' / '+ val.get_item.i_name +'</td>';
                    qty = '<td class="digits">'+ val.sdd_qty +'</td>';
                    unit = '<td>'+ val.get_unit.u_name +'</td>';
                    appendItem = no + kodeXnamaBrg + qty + unit;
                    $('#table_detail_ac > tbody:last-child').append('<tr>'+ appendItem +'</tr>');
                });
                //mask digits
                $('.digits').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: '', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalAcceptance').modal('show');
                loadingHide();
            },
            error: function(xhr, status, error) {
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
                loadingHide();
            }
        });
    }

    function confirmAcceptance()
    {
        loadingShow();
        let stockdistId = $('#id_ac').val();

        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/set-acceptance/" + stockdistId,
            type: "post",
            success: function (response) {
                loadingHide();
                if (response.status == 'berhasil') {
                    messageSuccess('Selamat', 'Konfirmasi penerimaan berhasil dilakukan !');
                    $('#modalAcceptance').modal('hide');
                    tableAc.ajax.reload();
                } else if (response.status == 'gagal') {
                    messageWarning('Perhatian', response.message);
                }
            },
            error: function(xhr, status, error) {
                loadingHide();
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }
</script>
@endsection
